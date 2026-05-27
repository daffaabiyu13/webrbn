<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Radika Bintang Nusantara') | PT. Radika Bintang Nusantara</title>
    <meta name="description" content="@yield('meta_description', 'PT. Radika Bintang Nusantara - Authorized Supplier & System Integrator komponen elektrikal Schneider Electric untuk industri Oil & Gas, Mining, Building, Food & Beverages, dan Water Segment.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2D6A27',
                        'primary-dark': '#1e4d1b',
                        secondary: '#6DBE45',
                        accent: '#F5A623',
                        cream: '#F5F5F0',
                        offwhite: '#F9FAF7',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .fade-up { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
        .fade-up.is-visible { opacity: 1; transform: translateY(0); }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="font-sans bg-offwhite text-[#1A1A1A] antialiased">

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
        });
    </script>
    @stack('scripts')
</body>
</html>
