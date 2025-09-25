import "./bootstrap";
import * as bootstrap from "bootstrap";

window.bootstrap = bootstrap; // biar bisa dipakai di semua tempat

import Swal from "sweetalert2";

document.addEventListener("livewire:init", () => {
    // Toast sederhana
    Livewire.on(
        "swal",
        ({ title = "OK", text = "", icon = "success", timer = 1800 }) => {
            Swal.fire({
                title,
                text,
                icon,
                timer,
                showConfirmButton: false,
                timerProgressBar: true,
                toast: true,
                position: "top-end",
            });
        }
    );

    // Dialog konfirmasi hapus
    Livewire.on(
        "confirm-delete",
        ({
            id,
            title = "Hapus data?",
            text = "Tindakan ini tidak bisa dibatalkan.",
        }) => {
            Swal.fire({
                title,
                text,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch("delete-confirmed", { id });
                }
            });
        }
    );
    // ------ TAMBAHKAN: Validasi agregat (popup error) ------
    Livewire.on("swal-validation", ({ messages = [] }) => {
        const html =
            '<ul style="text-align:left;margin:0;padding-left:1rem;">' +
            messages.map((m) => `<li>${m}</li>`).join("") +
            "</ul>";
        Swal.fire({ icon: "error", title: "Validasi gagal", html });
    });

    // ------ TAMBAHKAN: Sukses submit (tracking & PIN) ------
    Livewire.on("swal-success", async ({ tracking_no, pin, go_url }) => {
        const text = `No: ${tracking_no}\nPIN: ${pin}`;
        const html = `
      <div class="text-start small">
        No Laporan: <code>${tracking_no}</code><br>
        PIN: <code>${pin}</code>
      </div>
      <div class="mt-2 small text-muted">Simpan nomor & PIN ini untuk melacak status.</div>
    `;

        const res = await Swal.fire({
            icon: "success",
            title: "Aspirasi terkirim!",
            html,
            showCancelButton: true,
            confirmButtonText: "Salin & Lacak",
            cancelButtonText: "Tutup",
        });

        if (res.isConfirmed) {
            // Copy ke clipboard
            try {
                await navigator.clipboard.writeText(text);
            } catch (e) {
                // fallback untuk browser lama
                const ta = document.createElement("textarea");
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand("copy");
                ta.remove();
            }

            // Redirect ke halaman Tracking + prefill nomor
            const url = new URL(go_url, window.location.origin);
            url.searchParams.set("no", tracking_no); // aman: hanya nomor. (PIN tidak ditaruh di URL)
            window.location.href = url.toString();
        }
    });

    // Modal controller
    Livewire.on("modal:show", ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        const m = bootstrap.Modal.getOrCreateInstance(el);
        m.show();
    });
    Livewire.on("modal:hide", ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        const m = bootstrap.Modal.getOrCreateInstance(el);
        m.hide();
    });

    // Konfirmasi TERIMA (verifikasi)
    window.addEventListener("confirm-verify", (e) => {
        const id = e.detail?.id;
        Swal.fire({
            title: "Verifikasi aspirasi?",
            text: "Setelah verifikasi, aspirasi masuk tahap tindak lanjut.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, verifikasi",
            cancelButtonText: "Batal",
        }).then((res) => {
            if (res.isConfirmed) {
                Livewire.dispatch("verify-approved", {
                    id,
                });
            }
        });
    });

    // Konfirmasi TOLAK
    window.addEventListener("confirm-reject", (e) => {
        const id = e.detail?.id;
        Swal.fire({
            title: "Tolak aspirasi ini?",
            text: "Aspirasi akan ditandai ditolak & ditutup.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, tolak",
            cancelButtonText: "Batal",
        }).then((res) => {
            if (res.isConfirmed) {
                Livewire.dispatch("reject-approved", {
                    id,
                });
            }
        });
    });

    // Toast helper yg sudah kamu punya:
    // Livewire.on('swal', ({title='OK', text='', icon='success', timer=1800}) => { ... });
});

// ------ Inisialisasi Bootstrap Tooltip ------
const initTooltips = () => {
    // destroy dulu biar gak dobel saat re-init
    document.querySelectorAll(".tooltip").forEach((tp) => tp.remove());
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
        new bootstrap.Tooltip(el);
    });
};

// Pertama kali halaman siap
document.addEventListener("DOMContentLoaded", initTooltips);

// Saat Livewire siap & setelah navigasi/patch (biar tooltip hidup di elemen baru)
document.addEventListener("livewire:init", initTooltips);
document.addEventListener("livewire:navigated", initTooltips);
document.addEventListener("livewire:init", () => {
    Livewire.on("modal-show", ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        const modal = bootstrap.Modal.getOrCreateInstance(el);
        modal.show();
    });

    Livewire.on("modal-hide", ({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        const modal = bootstrap.Modal.getInstance(el);
        if (modal) modal.hide();
    });
});
document.addEventListener("DOMContentLoaded", () => {
    /* ====== Tooltip ====== */
    const tips = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tips.forEach((el) => new bootstrap.Tooltip(el));

    /* ====== Sidebar Toggle ====== */
    const body = document.body;
    const sidebar = document.getElementById("sidebar");
    const backdrop = document.getElementById("sidebarBackdrop");
    const btnOpen = document.getElementById("sidebarOpen");
    const btnClose = document.getElementById("sidebarClose");

    // Desktop: toggle collapse via keyboard shortcut (Ctrl+B) & saved state
    const KEY = "dash.sidebar.collapsed";

    // Apply saved state (desktop only)
    const applySavedState = () => {
        const collapsed = localStorage.getItem(KEY) === "1";
        if (window.matchMedia("(min-width: 992px)").matches) {
            body.classList.toggle("sidebar-collapsed", collapsed);
        } else {
            body.classList.remove("sidebar-collapsed");
        }
    };
    applySavedState();
    window.addEventListener("resize", applySavedState);

    // Shortcut Ctrl+B (desktop)
    document.addEventListener("keydown", (e) => {
        if (e.ctrlKey && e.key.toLowerCase() === "b") {
            if (window.matchMedia("(min-width: 992px)").matches) {
                const now = !body.classList.contains("sidebar-collapsed");
                body.classList.toggle("sidebar-collapsed", now);
                localStorage.setItem(KEY, now ? "1" : "0");
            }
        }
    });

    // Mobile open/close
    const openMobile = () => {
        if (!sidebar) return;
        sidebar.classList.add("show");
        backdrop?.classList.add("show");
    };
    const closeMobile = () => {
        sidebar?.classList.remove("show");
        backdrop?.classList.remove("show");
    };
    btnOpen?.addEventListener("click", openMobile);
    btnClose?.addEventListener("click", closeMobile);
    backdrop?.addEventListener("click", closeMobile);

    /* ====== Livewire + Bootstrap Modal (opsional) ======
     Agar event Livewire bisa buka/tutup modal #raperdaModal
  */
    const modEl = document.getElementById("raperdaModal");
    let modalInst = null;
    if (modEl) modalInst = new bootstrap.Modal(modEl, { backdrop: "static" });

    // Event yang dipanggil dari komponen Livewire (emit)
    window.addEventListener("lw:open-raperda", () => modalInst?.show());
    window.addEventListener("lw:close-raperda", () => modalInst?.hide());
});

document.addEventListener("livewire:init", () => {
    const el = document.getElementById("pinModal");
    if (!el) return;
    const modal = new bootstrap.Modal(el, {
        backdrop: "static",
    });

    // Buka modal
    Livewire.on("show-pin-modal", () => {
        modal.show();
        // fokus input setelah modal tampil
        setTimeout(() => {
            el.querySelector('input[type="password"]')?.focus();
        }, 150);
    });

    // Tutup modal
    Livewire.on("hide-pin-modal", () => {
        modal.hide();
    });

    // Pastikan kalau user klik tombol close native, state Livewire ikut beres
    el.addEventListener("hidden.bs.modal", () => {
        Livewire.dispatch("modal-hidden"); // opsional jika mau didengar di komponen
    });
});

// ====== COPY BUTTON (delegasi; aman untuk Livewire re-render) ======
const legacyCopy = (text) => {
    const ta = document.createElement("textarea");
    ta.value = text;
    ta.style.position = "fixed";
    ta.style.left = "-9999px";
    document.body.appendChild(ta);
    ta.select();
    try {
        document.execCommand("copy");
    } catch (e) {}
    document.body.removeChild(ta);
};

const showCopyFeedback = (btn, ok = true) => {
    // toggle UI
    btn.classList.toggle("btn-outline-primary", !ok);
    btn.classList.toggle("btn-success", ok);
    btn.classList.toggle("copied-anim", ok);
    btn.querySelector(".when-idle")?.classList.toggle("d-none", ok);
    btn.querySelector(".when-copied")?.classList.toggle("d-none", !ok);

    // tooltip on-demand
    const tip = window.bootstrap?.Tooltip?.getOrCreateInstance(btn, {
        trigger: "manual",
        placement: "top",
    });

    const setTip = (text) => {
        if (!tip) return;
        if (typeof tip.setContent === "function") {
            tip.setContent({ ".tooltip-inner": text });
        } else {
            // kompatibilitas Bootstrap < 5.3
            btn.setAttribute("data-bs-original-title", text);
        }
    };

    if (tip) {
        setTip(ok ? "Tersalin!" : "Gagal menyalin");
        tip.show();
    }

    setTimeout(() => {
        // reset
        btn.classList.remove("btn-success", "copied-anim");
        btn.classList.add("btn-outline-primary");
        btn.querySelector(".when-idle")?.classList.remove("d-none");
        btn.querySelector(".when-copied")?.classList.add("d-none");
        if (tip) {
            if (typeof tip.hide === "function") tip.hide();
            setTip("Salin nomor laporan");
        }
    }, 1200);
};

document.addEventListener(
    "click",
    (e) => {
        const btn = e.target.closest(".copy-btn");
        if (!btn) return;

        const sel = btn.getAttribute("data-copy-target");
        const el = document.querySelector(sel);
        if (!el) return;

        const text = (el.textContent || "").trim();
        if (!text) return;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard
                .writeText(text)
                .then(() => showCopyFeedback(btn, true))
                .catch(() => {
                    legacyCopy(text);
                    showCopyFeedback(btn, true);
                });
        } else {
            legacyCopy(text);
            showCopyFeedback(btn, true);
        }
    },
    { passive: true }
);

// === Progress Tahapan (robust) ============================================
(function () {
    // kecilkan noise re-init
    let _tahapanTimer = null;
    const scheduleInit = (root = document) => {
        clearTimeout(_tahapanTimer);
        _tahapanTimer = setTimeout(() => initTahapanOnce(root), 60);
    };

    function initTahapanOnce(root = document) {
        const section = root.getElementById
            ? root.getElementById("tahapan")
            : document.getElementById("tahapan");
        if (!section) return false;

        const wrap = section.querySelector(".lapor-steps");
        const lineEl = section.querySelector(".steps-line");
        const progressEl = section.querySelector(".steps-progress");
        const steps = Array.from(section.querySelectorAll(".step"));
        const dots = steps
            .map((s) => s.querySelector(".step-dot"))
            .filter(Boolean);

        if (
            !wrap ||
            !lineEl ||
            !progressEl ||
            steps.length === 0 ||
            dots.length === 0
        )
            return false;

        const total = dots.length;
        const rawStep = parseInt(section.dataset.step || "1", 10);
        const current = Math.min(Math.max(rawStep, 1), total);

        // tandai aktif
        steps.forEach((s, i) => s.classList.toggle("active", i < current));

        function layoutLine() {
            if (window.innerWidth < 768) {
                lineEl.style.width = "0px";
                progressEl.style.width = "0px";
                return;
            }
            const wrapRect = wrap.getBoundingClientRect();
            const first = dots[0].getBoundingClientRect();
            const last = dots[total - 1].getBoundingClientRect();

            const firstCenterX = first.left - wrapRect.left + first.width / 2;
            const lastCenterX = last.left - wrapRect.left + last.width / 2;
            const centerY = first.top - wrapRect.top + first.height / 2;
            const h = 6;

            lineEl.style.left = firstCenterX + "px";
            lineEl.style.top = Math.round(centerY - h / 2) + "px";
            lineEl.style.width =
                Math.max(0, Math.round(lastCenterX - firstCenterX)) + "px";
            lineEl.style.height = h + "px";
        }

        function paintProgress() {
            if (window.innerWidth < 768) {
                progressEl.style.width = "0px";
                return;
            }

            const wrapRect = wrap.getBoundingClientRect();
            const first = dots[0].getBoundingClientRect();
            const last = dots[total - 1].getBoundingClientRect();

            const firstCenterX = first.left - wrapRect.left + first.width / 2;
            const lastRightX = last.left - wrapRect.left + last.width;

            let targetX;
            if (current < total) {
                const nextRect = dots[current].getBoundingClientRect();
                const nextCenter =
                    nextRect.left - wrapRect.left + nextRect.width / 2;
                targetX = nextCenter;
            } else {
                targetX = lastRightX;
            }
            const widthPx = Math.max(0, Math.round(targetX - firstCenterX));

            // Hindari animasi ulang jika sama
            if (progressEl.dataset.lastWidth == widthPx) return;
            progressEl.dataset.lastWidth = widthPx;

            // Reset → paksa reflow → animate
            progressEl.style.transition = "none";
            progressEl.style.width = "0px";
            void progressEl.offsetWidth; // reflow
            progressEl.style.transition = "width 600ms ease";
            requestAnimationFrame(() => {
                progressEl.style.width = widthPx + "px";
            });
        }

        // --- jalankan: layout dulu, lalu trigger animasi ketika terlihat
        layoutLine();

        if ("IntersectionObserver" in window) {
            const io = new IntersectionObserver(
                (entries) => {
                    if (entries[0].isIntersecting) {
                        paintProgress();
                        io.disconnect();
                    }
                },
                { threshold: 0.2 }
            );
            io.observe(section);
        } else {
            requestAnimationFrame(paintProgress);
        }

        // resize handler (cleanup bila re-init)
        const onResize = () => {
            layoutLine();
            requestAnimationFrame(paintProgress);
        };
        window.addEventListener("resize", onResize, { passive: true });
        wrap.__tahapanCleanup?.();
        wrap.__tahapanCleanup = () =>
            window.removeEventListener("resize", onResize);

        return true;
    }

    // 1) pertama kali load
    document.addEventListener("DOMContentLoaded", () => {
        initTahapanOnce();
    });

    // 2) Observe DOM untuk munculnya #tahapan (Livewire ganti mode, dsb)
    const tahapanObserver = new MutationObserver(() => scheduleInit());
    tahapanObserver.observe(document.documentElement, {
        childList: true,
        subtree: true,
    });

    // 3) Livewire hook (lebih cepat sinkron setelah morph)
    document.addEventListener("livewire:load", () => {
        if (window.Livewire?.hook) {
            Livewire.hook("morph.updated", () => scheduleInit());
        }
    });
})();
