<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
     {{--
    Hero PADEK. Beri atribut "fit" pada komponen ini agar di HP gambarnya menyesuaikan sisa tinggi layar (halaman pas satu layar).
    - Mobile  : header di atas form (form menimpa sedikit ke atas gambar).
    - Desktop : panel kiri setinggi layar.
    Gambar: public/images/hero-padek.jpg
    --}}
    @props(['fit' => false])

    <aside class="{{ $fit ? 'flex flex-1 flex-col lg:flex-none' : '' }} relative overflow-hidden bg-gradient-to-b from-[#4db4ea] via-[#9bd8f2] to-[#d6f0fb] lg:sticky lg:top-0 lg:h-dvh">

        {{-- Cahaya & awan dekoratif --}}
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(255,255,255,.85),transparent_60%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-10 top-10 h-16 w-40 rounded-full bg-white/70 blur-lg" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-12 top-24 h-14 w-44 rounded-full bg-white/60 blur-lg" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col items-center px-6 pt-6 text-center sm:pt-8 lg:pt-14">

            {{-- Bunga rafflesia --}}
            <svg viewBox="0 0 100 100" class="h-12 w-12 drop-shadow-md sm:h-14 sm:w-14 lg:h-24 lg:w-24" aria-hidden="true">
                <ellipse cx="22" cy="86" rx="17" ry="7" fill="#1f8a2e" transform="rotate(-18 22 86)"/>
                <ellipse cx="78" cy="86" rx="17" ry="7" fill="#1f8a2e" transform="rotate(18 78 86)"/>
                <g fill="#d4131b" stroke="#a30d14" stroke-width="2">
                    <circle cx="50" cy="24" r="22"/>
                    <circle cx="74.7" cy="42" r="22"/>
                    <circle cx="65.3" cy="71" r="22"/>
                    <circle cx="34.7" cy="71" r="22"/>
                    <circle cx="25.3" cy="42" r="22"/>
                </g>
                <g fill="#f5b800" opacity=".9">
                    <circle cx="50" cy="14" r="1.6"/><circle cx="43" cy="22" r="1.4"/><circle cx="58" cy="20" r="1.4"/>
                    <circle cx="82" cy="40" r="1.6"/><circle cx="76" cy="50" r="1.4"/><circle cx="70" cy="34" r="1.4"/>
                    <circle cx="70" cy="82" r="1.6"/><circle cx="62" cy="78" r="1.4"/><circle cx="74" cy="72" r="1.4"/>
                    <circle cx="30" cy="82" r="1.6"/><circle cx="38" cy="78" r="1.4"/><circle cx="26" cy="72" r="1.4"/>
                    <circle cx="18" cy="40" r="1.6"/><circle cx="24" cy="50" r="1.4"/><circle cx="30" cy="34" r="1.4"/>
                </g>
                <circle cx="50" cy="50" r="17" fill="#e9b27a" stroke="#c9884a" stroke-width="2"/>
                <circle cx="50" cy="50" r="10" fill="#5a1f14"/>
            </svg>

            {{-- Wordmark PADEK: lapisan belakang putih (outline), lapisan depan berwarna --}}
            @php
                $huruf = [['P', 'text-[#d4131b]'], ['A', 'text-[#f5b800]'], ['D', 'text-[#1f8a2e]'], ['E', 'text-[#d4131b]'], ['K', 'text-[#d4131b]']];
            @endphp
            <h1 class="relative mt-2 inline-block font-[family-name:Fredoka,ui-rounded,system-ui,sans-serif] text-5xl font-bold leading-none tracking-tight drop-shadow-[0_0.04em_0_rgba(0,0,0,0.2)] sm:text-7xl lg:text-8xl xl:text-9xl"
                aria-label="PADEK">
                <span class="absolute inset-0 text-white [-webkit-text-stroke:0.2em_white]" aria-hidden="true">PADEK</span>
                <span class="relative" aria-hidden="true">
                    @foreach ($huruf as [$h, $warna])<span class="{{ $warna }}">{{ $h }}</span>@endforeach
                </span>
            </h1>

            <p class="mt-3 max-w-xs text-sm font-semibold text-[#5a2a1a] sm:max-w-sm sm:text-base lg:max-w-md lg:text-xl">
                Petualangan Asyik di Dunia Edukasi Kito
            </p>
        </div>

        {{-- Gambar maskot & pemandangan Minang --}}
        <img src="{{ asset('imgs/hero-padek.jpg') }}" alt="Maskot PADEK berbaju adat melambaikan tangan di depan Rumah Gadang dan Jam Gadang"
            class="relative mt-3 w-full object-cover object-top [mask-image:linear-gradient(to_bottom,transparent,black_30%)]
                    {{ $fit ? 'h-0 min-h-0 flex-1 lg:flex-none' : 'h-44 sm:h-64' }}
                    lg:absolute lg:inset-x-0 lg:bottom-0 lg:mt-0 lg:h-[62%]">

        <livewire:pages::component.songket class="absolute inset-x-0 bottom-0 z-20 hidden h-4 lg:block" />
    </aside>
</div>
