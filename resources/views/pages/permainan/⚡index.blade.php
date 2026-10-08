<?php

use App\Models\data_permainan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Daftar Permainan - PADEK')] class extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public string $nama_permainan = '';
    public string $deskripsi_permainan = '';

    // konfirmasi hapus
    public ?int $hapusId = null;
    public string $hapusNama = '';

    #[Computed]
    public function total(): int
    {
        return data_permainan::count();
    }

    #[Computed]
    public function permainans()
    {
        return data_permainan::with('pembuat')->latest()->paginate(9);
    }

    public function openForm(): void
    {
        $this->resetValidation();
        $this->reset('nama_permainan', 'deskripsi_permainan');
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
    }

    public function save(): void
    {
        $this->validate([
            'nama_permainan'      => 'required|string|max:255',
            'deskripsi_permainan' => 'nullable|string|max:255',
        ], [
            'nama_permainan.required' => 'Nama permainan wajib diisi.',
            'nama_permainan.max'      => 'Nama permainan maksimal 255 karakter.',
            'deskripsi_permainan.max' => 'Deskripsi maksimal 255 karakter.',
        ]);

        data_permainan::create([
            'nama_permainan'      => $this->nama_permainan,
            'deskripsi_permainan' => $this->deskripsi_permainan ?: null,
            'pembuat_id'          => auth()->id(),
        ]);

        $this->closeForm();
        $this->resetPage();
        session()->flash('sukses', 'Permainan baru berhasil ditambahkan.');
    }

    public function konfirmasiHapus(int $id): void
    {
        $permainan = data_permainan::findOrFail($id);

        // hanya pembuat yang boleh menghapus
        abort_unless($permainan->pembuat_id === auth()->id(), 403);

        $this->hapusId   = $permainan->id;
        $this->hapusNama = $permainan->nama_permainan;
    }

    public function batalHapus(): void
    {
        $this->reset('hapusId', 'hapusNama');
    }

    public function hapus(): void
    {
        $permainan = data_permainan::find($this->hapusId);

        if ($permainan) {
            abort_unless($permainan->pembuat_id === auth()->id(), 403);

            $nama = $permainan->nama_permainan;
            $permainan->delete();

            // jika halaman saat ini jadi kosong, mundur ke halaman terakhir
            $halamanTerakhir = max(1, (int) ceil(data_permainan::count() / 9));
            if ($this->getPage() > $halamanTerakhir) {
                $this->setPage($halamanTerakhir);
            }

            session()->flash('sukses', "Permainan \"{$nama}\" berhasil dihapus.");
        }

        $this->batalHapus();
    }
};
?>

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

<div>

    {{-- Judul halaman --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#d4131b] text-white shadow-[0_4px_0_#a30d14]">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Daftar Permainan</h1>
            <p class="text-sm text-[#7a5a45] sm:text-base">Kumpulan permainan seru di PADEK</p>
        </div>
    </div>

    {{-- Pesan sukses --}}
    @if (session()->has('sukses'))
        <div class="mb-4 flex items-center gap-3 rounded-2xl border-2 border-[#1f8a2e]/30 bg-[#dcf3df] px-4 py-3 text-sm font-semibold text-[#17691f]" role="status">
            <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
            {{ session('sukses') }}
        </div>
    @endif

    {{-- Keterangan jumlah + tombol tambah --}}
    <div class="mb-6 flex flex-col gap-3 rounded-2xl border-2 border-[#ecd9b0] bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="flex items-center gap-2 text-base font-medium text-[#7a5a45]">
            Tersedia
            <span class="inline-flex min-w-9 items-center justify-center rounded-full bg-[#f5b800] px-3 py-0.5 text-base font-extrabold text-[#3b1f14]">{{ $this->total }}</span>
            permainan
        </p>

        <button type="button" wire:click="openForm"
                class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00]">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Permainan
        </button>
    </div>

    {{-- Daftar permainan --}}
    @if ($this->permainans->isEmpty())
        <div class="flex min-h-[40vh] flex-col items-center justify-center rounded-3xl border-2 border-dashed border-[#ecd9b0] bg-white/60 p-8 text-center">
            <p class="text-lg font-extrabold">Belum ada permainan</p>
            <p class="mt-1 max-w-xs text-sm text-[#7a5a45]">Yuk, buat permainan pertama untuk memulai petualangan.</p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->permainans as $permainan)
                @php
                    [$bg, $fg] = $tema[$permainan->id % 3];
                    $pembuat   = $permainan->pembuat?->nama_lengkap ?: ($permainan->pembuat?->email ?? 'Tidak diketahui');
                    $bolehHapus = $permainan->pembuat_id === auth()->id();
                @endphp

                <article wire:key="permainan-{{ $permainan->id }}"
                         class="group relative flex h-full flex-col rounded-3xl border-2 border-[#ecd9b0] bg-white p-5 transition hover:-translate-y-1 hover:shadow-[0_6px_0_#ecd9b0]">
                    @if ($bolehHapus)
                        <button type="button" wire:click="konfirmasiHapus({{ $permainan->id }})"
                                title="Hapus permainan" aria-label="Hapus permainan {{ $permainan->nama_permainan }}"
                                class="absolute right-3 top-3 flex size-9 items-center justify-center rounded-full bg-white text-[#7a5a45] opacity-0 shadow-sm ring-2 ring-[#ecd9b0] transition hover:bg-[#d4131b] hover:text-white hover:ring-[#d4131b] focus:outline-none focus-visible:opacity-100 focus-visible:ring-4 focus-visible:ring-[#d4131b]/30 group-hover:opacity-100 group-focus-within:opacity-100 [@media(hover:none)]:opacity-100">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        </button>
                    @endif


                    <div class="flex size-12 items-center justify-center rounded-2xl {{ $bg }} {{ $fg }}">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z"/></svg>
                    </div>

                    <h3 class="mt-4 text-lg font-extrabold leading-snug">{{ $permainan->nama_permainan }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-[#7a5a45]">
                        {{ $permainan->deskripsi_permainan ?: 'Belum ada deskripsi.' }}
                    </p>

                    <div class="mt-auto flex items-center justify-between gap-3 border-t-2 border-dashed border-[#ecd9b0] pt-4 text-xs text-[#7a5a45]">
                        <span class="flex shrink-0 items-center gap-1.5 font-medium">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            <time datetime="{{ $permainan->created_at->toDateString() }}">{{ $permainan->created_at->locale('id')->translatedFormat('d F Y') }}</time>
                        </span>

                        <span class="flex min-w-0 items-center gap-2" title="Dibuat oleh {{ $pembuat }}">
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-[#f5b800] text-[0.7rem] font-extrabold text-[#3b1f14]" aria-hidden="true">{{ strtoupper(mb_substr($pembuat, 0, 1)) }}</span>
                            <span class="truncate font-semibold text-[#3b1f14]">{{ $pembuat }}</span>
                        </span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">{{ $this->permainans->links() }}</div>
    @endif

    {{-- Modal tambah permainan --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-[#3b1f14]/50 p-4 sm:items-center"
             role="dialog" aria-modal="true" aria-labelledby="judul-form"
             @keydown.escape.window="$wire.closeForm()">

            <div class="absolute inset-0" wire:click="closeForm" aria-hidden="true"></div>

            <div class="relative w-full max-w-lg rounded-3xl border-2 border-[#ecd9b0] bg-[#fff8e8] p-6 shadow-2xl">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h2 id="judul-form" class="text-xl font-extrabold tracking-tight">Tambah Permainan</h2>
                        <p class="mt-1 text-sm text-[#7a5a45]">Isi nama dan deskripsi singkat permainan.</p>
                    </div>
                    <button type="button" wire:click="closeForm" aria-label="Tutup"
                            class="rounded-xl p-2 text-[#7a5a45] transition hover:bg-white hover:text-[#3b1f14] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4" novalidate>
                    <div>
                        <label for="nama_permainan" class="{{ $label }}">Nama permainan</label>
                        <input id="nama_permainan" type="text" wire:model="nama_permainan" autofocus placeholder="Contoh: Tebak Angka Ceria"
                               class="{{ $base }} {{ $errors->has('nama_permainan') ? $bad : $ok }}">
                        @error('nama_permainan') <p class="mt-2 text-sm font-semibold text-[#d4131b]" role="alert">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="deskripsi_permainan" class="{{ $label }}">Deskripsi singkat <span class="font-medium text-[#7a5a45]">(opsional)</span></label>
                        <textarea id="deskripsi_permainan" wire:model="deskripsi_permainan" rows="3" placeholder="Tulis ringkasan permainan dalam satu atau dua kalimat"
                                  class="{{ $base }} resize-none {{ $errors->has('deskripsi_permainan') ? $bad : $ok }}"></textarea>
                        @error('deskripsi_permainan') <p class="mt-2 text-sm font-semibold text-[#d4131b]" role="alert">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="closeForm"
                                class="rounded-2xl border-2 border-[#ecd9b0] bg-white px-5 py-3 text-base font-extrabold text-[#7a5a45] transition hover:bg-[#fff1d6] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                            <span wire:loading.remove wire:target="save">Simpan Permainan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal konfirmasi hapus --}}
    @if ($hapusId)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-[#3b1f14]/50 p-4 sm:items-center"
             role="alertdialog" aria-modal="true" aria-labelledby="judul-hapus"
             @keydown.escape.window="$wire.batalHapus()">

            <div class="absolute inset-0" wire:click="batalHapus" aria-hidden="true"></div>

            <div class="relative w-full max-w-sm rounded-3xl border-2 border-[#ecd9b0] bg-[#fff8e8] p-6 text-center shadow-2xl">
                <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-[#fde8e8] text-[#d4131b]">
                    <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </div>

                <h2 id="judul-hapus" class="mt-4 text-xl font-extrabold tracking-tight">Hapus permainan?</h2>
                <p class="mt-2 text-sm text-[#7a5a45]">
                    <span class="font-bold text-[#3b1f14]">{{ $hapusNama }}</span> akan dihapus permanen dan tidak bisa dikembalikan.
                </p>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                    <button type="button" wire:click="batalHapus"
                            class="rounded-2xl border-2 border-[#ecd9b0] bg-white px-5 py-3 text-base font-extrabold text-[#7a5a45] transition hover:bg-[#fff1d6] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                        Batal
                    </button>
                    <button type="button" wire:click="hapus" wire:loading.attr="disabled" wire:target="hapus"
                            class="rounded-2xl bg-[#d4131b] px-5 py-3 text-base font-extrabold text-white shadow-[0_5px_0_#a30d14] transition hover:brightness-110 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/40 active:translate-y-[3px] active:shadow-[0_2px_0_#a30d14] disabled:cursor-not-allowed disabled:opacity-70">
                        <span wire:loading.remove wire:target="hapus">Ya, Hapus</span>
                        <span wire:loading wire:target="hapus">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
