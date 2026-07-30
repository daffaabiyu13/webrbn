<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') &mdash; PT. Radika Bintang Nusantara</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#416423">
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
    <style>[x-cloak]{display:none!important}</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans bg-offwhite text-[#1A1A1A] min-h-screen">
<div class="flex min-h-screen" x-data="{ sidebar: false }">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-primary text-white transition-transform md:relative md:translate-x-0"
           :class="sidebar ? 'translate-x-0' : ''">
        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
            @include('partials.brand-logo', ['size' => 'sm'])
            <span class="font-bold leading-tight">Admin Panel</span>
        </div>
        <nav class="px-3 py-6 space-y-1 text-sm">
            @php
                $menu = [
                    ['admin.dashboard', 'Dashboard', 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3v-7h6v7h3a1 1 0 001-1V10'],
                    ['admin.products.index', 'Produk', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4'],
                    ['admin.categories.index', 'Kategori', 'M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z'],
                    ['admin.settings.edit', 'Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.591 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.591c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.591c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.591 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.591-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.591c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.591c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.59-1.066zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ];
            @endphp
            @foreach ($menu as [$route, $label, $path])
                @php $active = request()->routeIs(str_replace('.index', '.*', $route)) || request()->routeIs($route); @endphp
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition-colors {{ $active ? 'bg-white/15 text-white' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $path }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach

            <div class="!mt-8 border-t border-white/10 pt-4">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium text-white/75 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M10 14L21 3m0 0h-7m7 0v7M5 5h6M5 5v14a1 1 0 001 1h14"/></svg>
                    Lihat Website
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 font-medium text-white/75 hover:bg-white/10 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-30 bg-black/40 md:hidden"></div>

    {{-- Content --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
            <button @click="sidebar = !sidebar" class="md:hidden text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-800">@yield('heading', 'Dashboard')</h1>
            <div class="flex items-center gap-3 text-sm text-gray-600">
                <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-white font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-5 rounded-lg border border-secondary/40 bg-secondary/10 px-4 py-3 text-sm font-medium text-primary">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Periksa kembali input berikut:</p>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
