@php
    $partners = ['Schneider Electric', 'CHINT Electrics', 'LS Electric', 'EBARA', 'Trafindo'];
@endphp

<section class="border-y border-gray-200 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 fade-up">
        <div class="text-center">
            <h2 class="text-3xl font-semibold text-primary">Brand Partner Kami</h2>
            <p class="mt-2 text-gray-600">Solusi terbaik dari brand terpercaya dunia</p>
        </div>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-4 sm:gap-6">
            @foreach ($partners as $partner)
                <div class="flex h-16 min-w-[150px] items-center justify-center rounded-xl border border-gray-200 bg-white px-6 text-base font-bold text-primary shadow-sm transition-shadow hover:shadow-md">
                    {{ $partner }}
                </div>
            @endforeach
        </div>
    </div>
</section>
