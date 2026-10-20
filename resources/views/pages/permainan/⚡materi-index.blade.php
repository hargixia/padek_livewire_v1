<?php

use App\Models\data_materi;
use App\Models\data_permainan;
use App\Models\data_file;

use App\Http\Controllers\api_support;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Daftar Materi - PADEK')] class extends Component
{
    use WithPagination;

    public $id_permainan;
    public bool $showForm = false;

    public $permainan_data;
    public $permainan_id = 1;
    public $pembuat;
    public string $thumbnail_id = '';
    public string $nama_materi = '';
    public string $deskripsi_materi = '';
    public string $isi_materi = '';


    public function mount($data)
    {
        $as = new api_support();
        $dekripsi = $as->my_decrypt($data);
        $splitter = $as->splitter(1, $dekripsi);

        $id = $splitter[1] ?? 0;
        $valid_id = $this->cek_permainan($id);

        if ($splitter[0] !== 'materi' || $valid_id == false) {
            return redirect()->to('/permainan')->with('gagal', 'Data permainan tidak valid.bukan materi');
        }else{
            $this->id_permainan = $this->permainan_data->id;
            $this->pembuat = $this->permainan_data->pembuat;
        }
    }

    public function cek_permainan($id)
    {
        $permainan = data_permainan::where('id',$id)->with('pembuat')->first();
        $result = false;
        if ($permainan) {
            $this->permainan_data = $permainan;
            $result = true;
        }

        return $result;
    }

    public function buatMateri($tipe){
        $as = new api_support();
        $parcel = "tambah_materi>>" . $this->id_permainan . ">>" . $tipe . ">>" . $this->nama_materi . ">>" . $this->deskripsi_materi;
        $enkripsi = $as->my_encrypt($parcel);
        return redirect()->route('permainan.materi-tambah',['data'=>$enkripsi]);
    }

    #[Computed]
    public function total(): int
    {
        return data_materi::where('permainan_id',$this->id_permainan)->count();
    }

    #[Computed]
    public function materis()
    {
        return data_materi::where('permainan_id',$this->id_permainan)->orderBy('urutan_materi','asc')->orderBy('updated_at','asc')->paginate(10);
    }

    public function openForm(): void
    {
        $this->resetValidation();
        $this->reset('permainan_id', 'nama_materi', 'deskripsi_materi', 'isi_materi');
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
    }

};
?>

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
    {{-- Judul halaman --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#d4131b] text-white shadow-[0_4px_0_#a30d14]">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $ikonBuku }}"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Daftar Materi {{ $permainan_data['nama_permainan'] }}</h1>
            <p class="text-sm text-[#7a5a45] sm:text-base">{{ $permainan_data['deskripsi_permainan'] ?? 'Belum ada deskripsi.' }}</p>
            <p class="text-sm text-[#7a5a45] sm:text-base">Dibuat oleh : {{ $pembuat['nama_lengkap'] ?? 'Tidak diketahui' }}</p>
        </div>
    </div>

    <livewire:pages::component.pesan1 />

    {{-- Keterangan jumlah + tombol tambah --}}
    <div class="mb-6 flex flex-col gap-3 rounded-2xl border-2 border-[#ecd9b0] bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="flex items-center gap-2 text-base font-medium text-[#7a5a45]">
            Tersedia
            <span class="inline-flex min-w-9 items-center justify-center rounded-full bg-[#f5b800] px-3 py-0.5 text-base font-extrabold text-[#3b1f14]">{{ $this->total }}</span>
            materi
        </p>

        <button type="button" wire:click="openForm"
                class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00]">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Materi
        </button>
    </div>

    {{-- Daftar materi (list ke bawah) --}}
    @if ($this->materis->isEmpty())
        <div class="flex min-h-[40vh] flex-col items-center justify-center rounded-3xl border-2 border-dashed border-[#ecd9b0] bg-white/60 p-8 text-center">
            <p class="text-lg font-extrabold">Belum ada materi</p>
            <p class="mt-1 max-w-xs text-sm text-[#7a5a45]">Yuk, tambahkan materi pertama untuk sebuah permainan.</p>
        </div>
    @else
        <ul class="space-y-3 sm:space-y-4">
            @foreach ($this->materis as $materi)
                @php
                    [$bg, $fg] = $tema[$materi->id % 3];
                @endphp

                <li wire:key="materi-{{ $materi->id }}">
                    <a href="/materi/{{ $materi->id }}" wire:navigate
                       class="group flex items-center gap-4 rounded-3xl border-2 border-[#ecd9b0] bg-white p-3 pr-4 transition hover:-translate-y-0.5 hover:border-[#d4131b]/40 hover:shadow-[0_5px_0_#ecd9b0] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20 sm:gap-5 sm:p-4 sm:pr-6">

                        {{-- Isi --}}
                        <div class="min-w-0 flex-1">
                            <h3 class="line-clamp-2 text-base font-extrabold leading-snug sm:text-lg">{{ $materi->nama_materi }}</h3>

                            <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-[#7a5a45]">
                                {{ $materi->deskripsi_materi ?: 'Belum ada deskripsi.' }}
                            </p>

                            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-[#7a5a45]">
                                <span class="inline-flex max-w-full items-center gap-1.5 rounded-full bg-[#fff1c2] px-2.5 py-0.5 font-bold text-[#6b4a00]">
                                    <svg class="size-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $ikonPuzzle }}"/></svg>
                                    <span class="truncate">Jenis Materi : {{ $materi->jenis_materi ?? 'Tidak Ada' }}</span>
                                </span>

                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $ikonKalender }}"/></svg>
                                    <time datetime="{{ $materi->created_at->toDateString() }}">{{ $materi->created_at->locale('id')->translatedFormat('d F Y') }}</time>
                                </span>
                            </div>
                        </div>

                        {{-- Panah --}}
                        <svg class="hidden size-6 shrink-0 text-[#b8a58a] transition group-hover:translate-x-1 group-hover:text-[#d4131b] sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">{{ $this->materis->links() }}</div>
    @endif

    {{-- Modal tambah materi --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-[#3b1f14]/50 p-4 sm:items-center"
             role="dialog" aria-modal="true" aria-labelledby="judul-form"
             @keydown.escape.window="$wire.closeForm()">

            <div class="absolute inset-0" wire:click="closeForm" aria-hidden="true"></div>

            <div class="relative max-h-[92dvh] w-full max-w-2xl overflow-y-auto rounded-3xl border-2 border-[#ecd9b0] bg-[#fff8e8] p-6 shadow-2xl">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h2 id="judul-form" class="text-xl font-extrabold tracking-tight">Tambah Materi</h2>
                        <p class="mt-1 text-sm text-[#7a5a45]">Lengkapi data materi di bawah ini.</p>
                    </div>
                    <button type="button" wire:click="closeForm" aria-label="Tutup"
                            class="rounded-xl p-2 text-[#7a5a45] transition hover:bg-white hover:text-[#3b1f14] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </div>

                {{-- Nama --}}
                <div>
                    <label for="nama_materi" class="{{ $label }}">Nama materi</label>
                    <input id="nama_materi" type="text" wire:model="nama_materi" placeholder="Contoh: Mengenal Angka 1 sampai 10"
                            class="{{ $base }} {{ $errors->has('nama_materi') ? $bad : $ok }}">
                    @error('nama_materi') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi_materi" class="{{ $label }}">Deskripsi singkat <span class="font-medium text-[#7a5a45]">(opsional)</span></label>
                    <input id="deskripsi_materi" type="text" wire:model="deskripsi_materi" placeholder="Ringkasan dalam satu kalimat"
                            class="{{ $base }} {{ $errors->has('deskripsi_materi') ? $bad : $ok }}">
                    @error('deskripsi_materi') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                </div>

                <div>

                </div>

                <div>
                    <label class="{{ $label }} mt-5">Pilih Jenis Materi</label>
                    <center>
                        <div class="inline-flex center gap-3">
                        <button wire:click="buatMateri('Teks')"
                            class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                            Teks
                        </button>
                        <button wire:click="buatMateri('Gambar')"
                            class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                            Gambar
                        </button>
                        <button wire:click="buatMateri('Video')"
                            class="flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                            Video
                        </button>
                    </div>
                    </center>
                </div>

            </div>
        </div>
    @endif
</div>
