@php
    $partners = ['Schneider Electric', 'CHINT Electrics', 'LS Electric', 'ABB', 'Siemens', 'Trafindo'];
@endphp

<footer class="bg-primary text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="flex items-center gap-3">
                    @include('partials.brand-logo', ['size' => 'md'])
                    <span class="font-bold leading-tight">PT. Radika Bintang<br>Nusantara</span>
                </div>
                <p class="mt-4 text-sm text-white/80 leading-relaxed">
                    Authorized Supplier &amp; System Integrator of electrical components for Oil &amp; Gas, Mining, Building, Food &amp; Beverages, and Water Segment industries.
                </p>
                <span class="mt-4 inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold">
                    Authorized Schneider Electric Partner
                </span>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary">Quick Links</h3>
                <ul class="mt-4 space-y-2 text-sm text-white/80">
                    <li><a href="{{ route('home') }}" class="hover:text-secondary transition-colors">Home</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-secondary transition-colors">Catalog</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-secondary transition-colors">About</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary">Contact</h3>
                <ul class="mt-4 space-y-3 text-sm text-white/80">
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 h-4 w-4 flex-none text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.28a2 2 0 011.9 1.37l1 3.02a2 2 0 01-.5 2.06L8.4 10.79a12 12 0 004.8 4.8l1.34-1.28a2 2 0 012.06-.5l3.02 1a2 2 0 011.37 1.9V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                        </svg>
                        <span class="flex flex-col gap-0.5">
                            <a href="tel:+6282123345758" class="hover:text-secondary">+62 82 123 345 758</a>
                            <a href="tel:+6285330330396" class="hover:text-secondary">+62 853-3033-0396</a>
                        </span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 h-4 w-4 flex-none text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:pt.radikabintang@gmail.com" class="hover:text-secondary break-all">pt.radikabintang@gmail.com</a>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 h-4 w-4 flex-none text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Taman Puspa Anggaswangi Blok P1-12 Sukodono, Sidoarjo, East Java 61258</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="mt-0.5 h-4 w-4 flex-none text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Kalimantan Branch: Jl. Makmur, Graha Permata Indah No.59, Banjarbaru, South Kalimantan</span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary">Brand Partners</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($partners as $partner)
                        <span class="rounded-md bg-white/10 px-3 py-1.5 text-xs font-medium">{{ $partner }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-white/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-center text-xs text-white/70">
            Copyright &copy; 2025 PT. Radika Bintang Nusantara. All rights reserved.
        </div>
    </div>
</footer>
