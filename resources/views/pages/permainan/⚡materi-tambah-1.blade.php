@php
    $label = 'mb-2 block text-sm font-bold text-[#3b1f14]';
    $base  = 'block w-full rounded-2xl border-2 bg-white px-4 py-3 text-base text-[#3b1f14] placeholder-[#b8a58a] outline-none transition focus:ring-4';
    $ok    = 'border-[#ecd9b0] focus:border-[#d4131b] focus:ring-[#d4131b]/15';
    $bad   = 'border-red-500 focus:ring-red-500/20';
    $err   = 'mt-2 text-sm font-semibold text-[#d4131b]';

    $ikonBuku    = 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25';
    $ikonPuzzle  = 'M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z';
    $ikonKalender = 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5';

    // warna tile pengganti thumbnail bergantian: merah, kuning, hijau
    $tema = [
        ['bg-[#fde8e8]', 'text-[#d4131b]'],
        ['bg-[#fff1c2]', 'text-[#b07d00]'],
        ['bg-[#dcf3df]', 'text-[#1f8a2e]'],
    ];

@endphp


<div>

    @php
        $label = 'mb-2 block text-sm font-bold text-[#3b1f14]';
        $base  = 'block w-full rounded-2xl border-2 bg-white px-4 py-3 text-base text-[#3b1f14] placeholder-[#b8a58a] outline-none transition focus:ring-4';
        $ok    = 'border-[#ecd9b0] focus:border-[#d4131b] focus:ring-[#d4131b]/15';
        $bad   = 'border-red-500 focus:ring-red-500/20';

        // warna ikon card bergantian: merah, kuning, hijau
        $tema = [
            ['bg-[#fde8e8]', 'text-[#d4131b]'],
            ['bg-[#fff1c2]', 'text-[#b07d00]'],
            ['bg-[#dcf3df]', 'text-[#1f8a2e]'],
        ];
    @endphp

    {{-- Judul halaman --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#d4131b] text-white shadow-[0_4px_0_#a30d14]">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $ikonBuku }}"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Materi : {{$datas[3]}}</h1>
            <p class="text-sm text-[#7a5a45] sm:text-base">Tipe Materi : {{$datas[2]}}</p>
        </div>
    </div>

    <div class="w-full max-w-md mx-auto mt-10">
  <!-- Tab Headers -->
  <div class="flex border-b border-gray-200">
    <!-- Tab 1 -->
    <label class="flex-1 text-center py-2.5 cursor-pointer font-medium text-sm text-gray-500 has-[:checked]:border-b-2 has-[:checked]:border-indigo-600 has-[:checked]:text-indigo-600 hover:text-gray-700">
      <input type="radio" name="tabs" id="tab-btn-1" class="peer sr-only" checked />
      Profile
    </label>

    <!-- Tab 2 -->
    <label class="flex-1 text-center py-2.5 cursor-pointer font-medium text-sm text-gray-500 has-[:checked]:border-b-2 has-[:checked]:border-indigo-600 has-[:checked]:text-indigo-600 hover:text-gray-700">
      <input type="radio" name="tabs" id="tab-btn-2" class="peer sr-only" />
      Dashboard
    </label>

    <!-- Tab 3 -->
    <label class="flex-1 text-center py-2.5 cursor-pointer font-medium text-sm text-gray-500 has-[:checked]:border-b-2 has-[:checked]:border-indigo-600 has-[:checked]:text-indigo-600 hover:text-gray-700">
      <input type="radio" name="tabs" id="tab-btn-3" class="peer sr-only" />
      Settings
    </label>
  </div>

  <!-- Tab Panels -->
  <div class="mt-4 p-4 bg-white rounded-lg shadow">
    <!-- Panel 1 (Shows when tab-btn-1 is checked) -->
    <div class="hidden has-[#tab-btn-1:checked]:block">
      <h2 class="text-lg font-bold text-gray-900">Profile Content</h2>
      <p class="mt-1 text-sm text-gray-500">Manage your public bio and profile information here.</p>
    </div>

    <!-- Panel 2 -->
    <div class="hidden has-[#tab-btn-2:checked]:block">
      <h2 class="text-lg font-bold text-gray-900">Dashboard Content</h2>
      <p class="mt-1 text-sm text-gray-500">View real-time analytics, user metrics, and operational logs.</p>
    </div>

    <!-- Panel 3 -->
    <div class="hidden has-[#tab-btn-3:checked]:block">
      <h2 class="text-lg font-bold text-gray-900">Settings Content</h2>
      <p class="mt-1 text-sm text-gray-500">Update security configuration, API keys, and notification rules.</p>
    </div>
  </div>
</div>


</div>
