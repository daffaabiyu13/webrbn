@php
    $partners = ['Schneider Electric', 'CHINT Electrics', 'LS Electric', 'EBARA', 'Trafindo'];
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
                    {{ __('site.footer.tagline') }}
                </p>
                <span class="mt-4 inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold">
                    {{ __('site.footer.authorized') }}
                </span>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.footer.quick_links') }}</h3>
                <ul class="mt-4 space-y-2 text-sm text-white/80">
                    <li><a href="{{ route('home') }}" class="hover:text-secondary transition-colors">{{ __('site.nav.home') }}</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-secondary transition-colors">{{ __('site.nav.catalog') }}</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-secondary transition-colors">{{ __('site.nav.about') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.footer.contact') }}</h3>
                <ul class="mt-4 space-y-3 text-sm text-white/80">
                    <li class="flex gap-2"><span>&#128222;</span><a href="tel:081232048578" class="hover:text-secondary">081232048578</a></li>
                    <li class="flex gap-2"><span>&#9993;</span><a href="mailto:pt.radikabintang@gmail.com" class="hover:text-secondary break-all">pt.radikabintang@gmail.com</a></li>
                    <li class="flex gap-2"><span>&#128205;</span><span>{{ __('site.footer.address') }}</span></li>
                    <li class="flex gap-2"><span>&#127970;</span><span>{{ __('site.footer.branch') }}</span></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary">{{ __('site.footer.partners') }}</h3>
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
            {{ __('site.footer.copyright') }}
        </div>
    </div>
</footer>
