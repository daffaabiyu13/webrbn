<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Radika Bintang Nusantara') | PT. Radika Bintang Nusantara</title>
    <meta name="description" content="@yield('meta_description', 'PT. Radika Bintang Nusantara - Authorized Supplier & System Integrator komponen elektrikal Schneider Electric untuk industri Oil & Gas, Mining, Building, Food & Beverages, dan Water Segment.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#416423',
                        'primary-dark': '#2A421A',
                        secondary: '#A5E17D',
                        'secondary-dark': '#88CE5F',
                        accent: '#A5E17D',
                        cream: '#F0FFE6',
                        offwhite: '#F0FFE6',
                    },
                    fontFamily: {
                        sans: ['Roboto', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .fade-up { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
        .fade-up.is-visible { opacity: 1; transform: translateY(0); }

        /* Skeleton loaders */
        .skeleton {
            background: linear-gradient(110deg, #e5e7eb 8%, #f3f4f6 18%, #e5e7eb 33%);
            background-size: 200% 100%;
            animation: shimmer 1.4s linear infinite;
        }
        .skeleton-dark {
            background: linear-gradient(110deg, #1e3a1c 8%, #2a4d27 18%, #1e3a1c 33%);
            background-size: 200% 100%;
            animation: shimmer 1.6s linear infinite;
        }
        @keyframes shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .skeleton-line { height: 0.85rem; border-radius: 999px; }

        /* Fade-in image once loaded */
        .img-fade { opacity: 0; transition: opacity .35s ease; }
        .img-fade.is-loaded { opacity: 1; }

        /* Top progress bar (navigation feedback) */
        #nav-progress {
            position: fixed; top: 0; left: 0; right: 0; height: 3px;
            background: #A5E17D; transform: scaleX(0); transform-origin: 0 0;
            transition: transform .25s ease; z-index: 100; pointer-events: none;
        }
        #nav-progress.is-loading { transform: scaleX(0.7); transition: transform 2.5s cubic-bezier(.1,.7,.6,.98); }
        #nav-progress.is-done    { transform: scaleX(1); transition: transform .3s ease-out; opacity: 0; transition-property: transform, opacity; }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="font-sans bg-offwhite text-[#1A1A1A] antialiased">

    <div id="nav-progress"></div>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script>
        // Reveal-on-scroll animation
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            document.querySelectorAll('.fade-up').forEach((el) => observer.observe(el));

            // Top progress bar on same-origin link clicks
            const bar = document.getElementById('nav-progress');
            document.addEventListener('click', (e) => {
                const a = e.target.closest('a[href]');
                if (!a) return;
                if (a.target === '_blank' || a.hasAttribute('download')) return;
                if (a.getAttribute('href').startsWith('#')) return;
                try {
                    const url = new URL(a.href, location.href);
                    if (url.origin !== location.origin) return;
                    if (url.pathname === location.pathname && url.search === location.search) return;
                } catch (_) { return; }
                bar.classList.remove('is-done');
                bar.classList.add('is-loading');
            });
            window.addEventListener('pageshow', () => {
                bar.classList.remove('is-loading');
                bar.classList.add('is-done');
                setTimeout(() => bar.classList.remove('is-done'), 400);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
