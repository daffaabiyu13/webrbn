@php $current = app()->getLocale(); @endphp
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" @click.away="open = false"
            class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition-colors hover:border-primary hover:text-primary">
        <span class="text-sm leading-none">{{ $current === 'en' ? '🇬🇧' : '🇮🇩' }}</span>
        <span class="uppercase">{{ $current }}</span>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open" x-cloak x-transition
         class="absolute right-0 mt-1 w-36 overflow-hidden rounded-lg border border-gray-100 bg-white shadow-lg ring-1 ring-black/5 z-50">
        @foreach (['id' => ['🇮🇩', __('site.lang.id')], 'en' => ['🇬🇧', __('site.lang.en')]] as $code => $meta)
            <a href="{{ route('locale.switch', ['locale' => $code]) }}"
               class="flex items-center gap-2 px-3 py-2 text-sm transition-colors {{ $current === $code ? 'bg-cream text-primary font-semibold' : 'text-gray-700 hover:bg-cream hover:text-primary' }}">
                <span class="text-base leading-none">{{ $meta[0] }}</span>
                <span>{{ $meta[1] }}</span>
                @if ($current === $code)
                    <svg class="ml-auto h-4 w-4 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.3 3.29 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
