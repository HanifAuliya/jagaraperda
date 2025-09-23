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
});
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
