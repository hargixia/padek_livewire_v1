{{--
    Layout utama (halaman setelah login): sidebar di desktop, menu di atas untuk smartphone.
    Simpan di: resources/views/layouts/app.blade.php  (dipakai Livewire sebagai 'layouts::app')
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PADEK' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fff8e8] text-[#3b1f14] antialiased">

@php
    $user    = auth()->user();
    $nama    = $user?->nama_lengkap ?: ($user?->email ?? 'Petualang');
    $inisial = strtoupper(mb_substr($nama, 0, 1));

    // Ikon (outline 24x24)
    $icons = [
        'dashboard' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
        'materi'    => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
        'skor'      => 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0',
        'users'     => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        'profile'   => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z',
        'logout'    => 'M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75',
    ];

    // Urutan menu. Profile sengaja paling bawah.
    $menu = [
        ['label' => 'Dashboard',       'href' => '/dashboard', 'match' => 'dashboard*', 'icon' => 'dashboard'],
        ['label' => 'Daftar Materi',   'href' => '/materi',    'match' => 'materi*',    'icon' => 'materi'],
        ['label' => 'Daftar Skor',     'href' => '/skor',      'match' => 'skor*',      'icon' => 'skor'],
        ['label' => 'Manajemen User',  'href' => '/users',     'match' => 'users*',     'icon' => 'users'],
        ['label' => 'Profile',         'href' => '/profile',   'match' => 'profile*',   'icon' => 'profile'],
    ];
    $menuUtama = array_slice($menu, 0, 4);
    $menuBawah = $menu[4];

    $svg = 'size-6 shrink-0';
@endphp

<div class="min-h-screen bg-[#fff8e8] text-[#3b1f14] lg:flex"
     x-data="{ collapsed: $persist(true).as('padek_sidebar_collapsed') }">

    {{-- ===== SIDEBAR (desktop) — bisa diciutkan jadi ikon saja ===== --}}
    <aside class="group/side hidden overflow-hidden border-r-2 border-[#ecd9b0] bg-white transition-[width] duration-200 lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-20 lg:shrink-0 lg:flex-col"
           data-collapsed="true"
           :data-collapsed="collapsed ? 'true' : 'false'"
           :style="collapsed ? null : 'width: 18rem'">

        {{-- Logo + tombol ciut/bentang --}}
        <div class="flex items-center justify-between gap-2 px-4 pb-4 pt-7 group-data-[collapsed=true]/side:flex-col group-data-[collapsed=true]/side:gap-4 group-data-[collapsed=true]/side:px-0">
            <livewire:pages::component.logo />

            <button type="button" @click="collapsed = !collapsed"
                    :aria-expanded="(!collapsed).toString()"
                    :title="collapsed ? 'Bentangkan menu' : 'Ciutkan menu'"
                    aria-label="Ciutkan atau bentangkan menu"
                    class="rounded-xl p-2 text-[#7a5a45] transition hover:bg-[#fff1d6] hover:text-[#3b1f14] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                <svg class="size-5 transition-transform group-data-[collapsed=true]/side:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto overflow-x-hidden px-4 py-2 group-data-[collapsed=true]/side:px-3" aria-label="Menu utama">
            @foreach ($menuUtama as $item)
            @php $aktif = request()->is($item['match']); @endphp
            <a href="{{ $item['href'] }}" wire:navigate @if($aktif) aria-current="page" @endif
               title="{{ $item['label'] }}" aria-label="{{ $item['label'] }}"
               class="flex items-center gap-3 whitespace-nowrap rounded-2xl px-4 py-3 text-base font-bold transition focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20 group-data-[collapsed=true]/side:justify-center group-data-[collapsed=true]/side:px-0
                      {{ $aktif ? 'bg-[#d4131b] text-white shadow-[0_4px_0_#a30d14]' : 'text-[#7a5a45] hover:bg-[#fff1d6] hover:text-[#3b1f14]' }}">
                <svg class="{{ $svg }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icons[$item['icon']] }}"/></svg>
                <span class="group-data-[collapsed=true]/side:hidden">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- Bagian bawah: Profile, info user, keluar --}}
        <div class="space-y-3 border-t-2 border-[#ecd9b0] p-4 group-data-[collapsed=true]/side:px-3">
            @php $aktif = request()->is($menuBawah['match']); @endphp
            <a href="{{ $menuBawah['href'] }}" wire:navigate @if($aktif) aria-current="page" @endif
               title="{{ $menuBawah['label'] }}" aria-label="{{ $menuBawah['label'] }}"
               class="flex items-center gap-3 whitespace-nowrap rounded-2xl px-4 py-3 text-base font-bold transition focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20 group-data-[collapsed=true]/side:justify-center group-data-[collapsed=true]/side:px-0
                      {{ $aktif ? 'bg-[#d4131b] text-white shadow-[0_4px_0_#a30d14]' : 'text-[#7a5a45] hover:bg-[#fff1d6] hover:text-[#3b1f14]' }}">
                <svg class="{{ $svg }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icons[$menuBawah['icon']] }}"/></svg>
                <span class="group-data-[collapsed=true]/side:hidden">{{ $menuBawah['label'] }}</span>
            </a>

            <div class="flex items-center gap-3 rounded-2xl bg-[#fff8e8] p-3 group-data-[collapsed=true]/side:flex-col group-data-[collapsed=true]/side:gap-1 group-data-[collapsed=true]/side:p-2">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-[#f5b800] text-lg font-extrabold text-[#3b1f14]" title="{{ $nama }}" aria-hidden="true">{{ $inisial }}</div>
                <div class="min-w-0 flex-1 group-data-[collapsed=true]/side:hidden">
                    <p class="truncate text-sm font-bold">{{ $nama }}</p>
                    <p class="truncate text-xs text-[#7a5a45]">{{ $user?->email }}</p>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" title="Keluar" aria-label="Keluar"
                            class="rounded-xl p-2 text-[#7a5a45] transition hover:bg-white hover:text-[#d4131b] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icons['logout'] }}"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <livewire:pages::component.songket class="h-3" />
    </aside>

    {{-- ===== HEADER + MENU ATAS (smartphone & tablet) ===== --}}
    <header class="sticky top-0 z-30 border-b-2 border-[#ecd9b0] bg-[#fff8e8]/95 backdrop-blur lg:hidden">
        <livewire:pages::component.songket class="h-2" />

        <div class="flex items-center justify-between px-4 pb-2 pt-3 sm:px-6">
            <livewire:pages::component.logo />

            <div class="flex items-center gap-2">
                <div class="flex size-9 items-center justify-center rounded-full bg-[#f5b800] text-base font-extrabold" title="{{ $nama }}" aria-hidden="true">{{ $inisial }}</div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" title="Keluar" aria-label="Keluar"
                            class="rounded-xl p-2 text-[#7a5a45] transition hover:bg-white hover:text-[#d4131b] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icons['logout'] }}"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Pill menu: bisa digeser ke samping, menu aktif otomatis terlihat --}}
        <nav class="flex gap-2 overflow-x-auto px-4 pb-3 [scrollbar-width:none] sm:px-6 [&::-webkit-scrollbar]:hidden"
             aria-label="Menu utama"
             x-data x-init="$nextTick(() => $el.querySelector('[aria-current]')?.scrollIntoView({ inline: 'center', block: 'nearest' }))">
            @foreach ($menu as $item)
                @php $aktif = request()->is($item['match']); @endphp
                <a href="{{ $item['href'] }}" wire:navigate @if($aktif) aria-current="page" @endif
                   class="flex shrink-0 items-center gap-2 rounded-full border-2 px-4 py-2 text-sm font-bold transition focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20
                          {{ $aktif ? 'border-[#d4131b] bg-[#d4131b] text-white shadow-[0_3px_0_#a30d14]' : 'border-[#ecd9b0] bg-white text-[#7a5a45]' }}">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icons[$item['icon']] }}"/></svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </header>

    {{-- ===== ISI HALAMAN ===== --}}
    <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-10">
        {{ $slot }}
    </main>
</div>

</body>
</html>
