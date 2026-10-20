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

    <form wire:submit='save' class="space-y-4" novalidate>
        <textarea id="konten" wire:model='isi_materi'></textarea>

        <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="save">Simpan Permainan</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>

            <button type="button" wire:loading.attr="disabled" wire:click="kembali"
                    class="rounded-2xl border-2 border-[#ecd9b0] bg-white px-5 py-3 text-base font-extrabold text-[#7a5a45] transition hover:bg-[#fff1d6] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                    Batalkan
            </button>
        </div>
    </form>

    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/46vfywoddsizlr7b8mn55wnep9dqvsah9uvjzo4sz5ngpqkd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
    tinymce.init({
        selector: 'textarea#konten',
        plugins: [
        // Core editing features
        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
        // Premium features
        'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'tinymceai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | tinymceai-chat tinymceai-quickactions tinymceai-review | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
        ],
        tinymceai_token_provider: async () => {
        await fetch(`https://demo.api.tiny.cloud/1/46vfywoddsizlr7b8mn55wnep9dqvsah9uvjzo4sz5ngpqkd/auth/random`, { method: "POST", credentials: "include" });
        return { token: await fetch(`https://demo.api.tiny.cloud/1/46vfywoddsizlr7b8mn55wnep9dqvsah9uvjzo4sz5ngpqkd/jwt/tinymceai`, { credentials: "include" }).then(r => r.text()) };
        },
        uploadcare_public_key: '566b16212e151b2f0751',
    });
    </script>
</div>

