<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JAGARPERDA KALSEL')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles') {{-- halaman boleh nyuntik style tambahan --}}

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    {{-- choices --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

</head>

<body>

    {{-- HEADER (navbar + wave + optional hero placeholder) --}}
    @include('partials.header')

    {{-- ==================== MAIN: BERANDA (simple, 3x3 triangle) ==================== --}}
    <main class="container section @yield('main-class')">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    @livewireScripts
    @stack('scripts') {{-- halaman boleh nyuntik script tambahan --}}
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // === Progress Tahapan ===
            (function() {
                const section = document.getElementById("tahapan");
                if (!section) return; // <-- stop kalau #tahapan tidak ada

                const wrap = section.querySelector(".lapor-steps");
                if (!wrap) return; // <-- stop kalau struktur step tidak ada

                const lineEl = wrap.querySelector(".steps-line");
                const progressEl = wrap.querySelector("#stepsProgress");
                const steps = Array.from(wrap.querySelectorAll(".step"));
                if (steps.length === 0) return; // <-- stop kalau tidak ada step

                const dots = steps.map((s) => s.querySelector(".step-dot"));
                const total = steps.length;

                let current = Math.min(
                    Math.max(parseInt(section.dataset.step || "1", 10), 1),
                    total
                );

                function markActive(idx) {
                    steps.forEach((s, i) => s.classList.toggle("active", i < idx));
                }

                function layoutLine() {
                    if (window.innerWidth < 768) {
                        lineEl.style.width = "0px";
                        return;
                    }

                    const wrapRect = wrap.getBoundingClientRect();
                    const first = dots[0].getBoundingClientRect();
                    const last = dots[total - 1].getBoundingClientRect();

                    const firstCenterX = first.left - wrapRect.left + first.width / 2;
                    const lastCenterX = last.left - wrapRect.left + last.width / 2;

                    const trackLeft = firstCenterX;
                    const trackWidth = Math.max(0, Math.round(lastCenterX - firstCenterX));

                    const centerY = first.top - wrapRect.top + first.height / 2;
                    const lineH = 6;
                    const trackTop = Math.round(centerY - lineH / 2);

                    lineEl.style.left = trackLeft + "px";
                    lineEl.style.top = trackTop + "px";
                    lineEl.style.width = trackWidth + "px";
                    lineEl.style.height = lineH + "px";
                }

                function paintProgress(idx) {
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
                    if (idx < total) {
                        const nextRect = dots[idx].getBoundingClientRect();
                        const nextCenter = nextRect.left - wrapRect.left + nextRect.width / 2;
                        targetX = nextCenter;
                    } else {
                        targetX = lastRightX;
                    }

                    const widthPx = Math.max(0, Math.round(targetX - firstCenterX));
                    progressEl.style.width = widthPx + "px";
                }

                function apply(idx) {
                    markActive(idx);
                    layoutLine();
                    requestAnimationFrame(() => paintProgress(idx));
                }

                const io = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((e) => {
                            if (e.isIntersecting) {
                                apply(current);
                                io.disconnect();
                            }
                        });
                    }, {
                        threshold: 0.2
                    }
                );
                io.observe(wrap);

                window.addEventListener("resize", () => apply(current));
            })();

            // === Choices.js untuk Raperda Select ===
            const rapSelectEl = document.querySelector("#raperdaSelect");
            if (rapSelectEl) {
                new Choices(rapSelectEl, {
                    searchEnabled: false,
                    itemSelectText: "",
                    shouldSort: false,
                    allowHTML: false,
                    placeholder: true,
                    placeholderValue: "Pilih Raperda",
                });
            }

        });
    </script>

</body>

</html>
