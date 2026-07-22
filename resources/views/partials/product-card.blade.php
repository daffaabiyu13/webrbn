{{-- Expects: $product (with category loaded) --}}
<div class="group flex flex-col overflow-hidden rounded-xl bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
    <div class="relative aspect-[3/2] overflow-hidden bg-cream">
        <img src="{{ $product->imageUrl() }}" alt="{{ $product->translated('name') }}"
             loading="lazy"
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @if ($product->is_featured)
            <span class="absolute top-3 right-3 rounded-full bg-accent px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow">{{ __('site.catalog.featured_badge') }}</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        @if ($product->category)
            <span class="mb-2 inline-flex w-fit rounded-full bg-secondary/15 px-3 py-1 text-xs font-semibold text-primary">
                {{ $product->category->translated('name') }}
            </span>
        @endif

        <h3 class="text-lg font-bold text-[#1A1A1A]">{{ $product->translated('name') }}</h3>
        <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $product->translated('short_description') }}</p>

        @php $specs = $product->translated('specifications'); @endphp
        @if (!empty($specs) && is_array($specs))
            <dl class="mt-4 space-y-1 border-t border-gray-100 pt-3 text-xs text-gray-500">
                @foreach (array_slice($specs, 0, 2, true) as $key => $value)
                    <div class="flex justify-between gap-2">
                        <dt class="font-medium text-gray-600">{{ $key }}</dt>
                        <dd class="text-right">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        <a href="{{ route('catalog.show', $product->slug) }}"
           class="mt-5 inline-flex items-center justify-center gap-2 rounded-lg border-2 border-primary px-4 py-2.5 text-sm font-semibold text-primary transition-colors hover:bg-primary hover:text-white">
            {{ __('site.catalog.view_detail') }}
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>
