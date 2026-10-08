<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Profile - PADEK')] class extends Component
{
    use WithFileUploads;

    public string $nama_lengkap = '';
    public string $email = '';
    public string $jenis_kelamin = '';
    public string $tanggal_lahir = '';
    public string $sekolah = '';
    public string $kelas = '';

    /** File foto yang baru dipilih (belum disimpan) */
    public $foto = null;

    public ?string $sukses = null;
    public ?string $peringatan = null;

    public function mount(): void
    {
        $user = auth()->user();

        $this->nama_lengkap  = $user->nama_lengkap ?? '';
        $this->email         = $user->email;
        $this->jenis_kelamin = $user->jenis_kelamin ?? '';
        $this->tanggal_lahir = $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '';
        $this->sekolah       = $user->sekolah ?? '';
        $this->kelas         = $user->kelas ?? '';

        // pesan dari halaman lain (mis. setelah klik tautan verifikasi di email)
        $this->sukses = session()->pull('sukses');
    }

    /* ---------- Data diri ---------- */

    public function simpan(): void
    {
        $this->bersihkanPesan();

        $this->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'required|max:255|unique:users,id,except,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today',
            'sekolah'       => 'required|string|max:255',
            'kelas'         => 'required|string|max:50',
        ], [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah dipakai akun lain.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak valid.',
            'sekolah.required'       => 'Nama sekolah wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
        ]);

        $user = auth()->user();
        $emailBerubah = $user->email !== $this->email;

        $data = [
            'nama_lengkap'  => $this->nama_lengkap,
            'email'         => $this->email,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'sekolah'       => $this->sekolah,
            'kelas'         => $this->kelas,
        ];

        // email baru harus diverifikasi ulang
        if ($emailBerubah) {
            $data['email_verified_at'] = null;
        }

        $user->forceFill($data)->save();

        $this->sukses = $emailBerubah
            ? 'Profil diperbarui. Karena email berubah, silakan verifikasi email baru kamu.'
            : 'Profil berhasil diperbarui.';
    }

    /* ---------- Verifikasi email ---------- */

    public function kirimVerifikasi(): void
    {
        $this->bersihkanPesan();

        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            $this->sukses = 'Email kamu sudah terverifikasi.';
            return;
        }

        if ($this->email !== $user->email) {
            $this->peringatan = 'Simpan perubahan email dulu, baru kirim email verifikasi.';
            return;
        }

        // batasi 1 kali per menit
        $kunci = 'verifikasi-email:' . $user->id;

        if (RateLimiter::tooManyAttempts($kunci, 1)) {
            $this->peringatan = 'Tunggu ' . RateLimiter::availableIn($kunci) . ' detik sebelum mengirim ulang.';
            return;
        }

        RateLimiter::hit($kunci, 60);
        $user->sendEmailVerificationNotification();

        $this->sukses = 'Email verifikasi sudah dikirim ke ' . $user->email . '. Cek kotak masuk (atau folder spam).';
    }

    /* ---------- Foto profil ---------- */

    public function updatedFoto(): void
    {
        $this->bersihkanPesan();

        try {
            $this->validateOnly('foto', $this->aturanFoto(), $this->pesanFoto());
        } catch (ValidationException $e) {
            $this->reset('foto');
            throw $e;
        }
    }

    public function simpanFoto(): void
    {
        $this->bersihkanPesan();
        $this->validate(['foto' => 'required|' . $this->aturanFoto()['foto']], $this->pesanFoto());

        $user = auth()->user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $this->foto->store('foto-profil', 'public');
        $user->forceFill(['foto' => $path])->save();

        $this->reset('foto');
        $this->sukses = 'Foto profil berhasil diperbarui.';
    }

    public function batalFoto(): void
    {
        $this->reset('foto');
        $this->resetValidation('foto');
    }

    public function hapusFoto(): void
    {
        $this->bersihkanPesan();

        $user = auth()->user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->forceFill(['foto' => null])->save();
        }

        $this->sukses = 'Foto profil dihapus.';
    }

    /* ---------- Helper ---------- */

    protected function aturanFoto(): array
    {
        return ['foto' => 'image|mimes:jpg,jpeg,png,webp|max:2048'];
    }

    protected function pesanFoto(): array
    {
        return [
            'foto.required' => 'Pilih foto dulu.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max'      => 'Ukuran foto maksimal 2 MB.',
            'foto.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya tidak lebih dari 2 MB.',
        ];
    }

    protected function bersihkanPesan(): void
    {
        $this->sukses = null;
        $this->peringatan = null;
    }
};
?>

@php
    $user         = auth()->user();
    $terverifikasi = $user->hasVerifiedEmail();
    $emailBerubah  = $email !== $user->email;

    $label = 'mb-2 block text-sm font-bold text-[#3b1f14]';
    $base  = 'block w-full rounded-2xl border-2 bg-white px-4 py-3 text-base text-[#3b1f14] placeholder-[#b8a58a] outline-none transition focus:ring-4';
    $ok    = 'border-[#ecd9b0] focus:border-[#d4131b] focus:ring-[#d4131b]/15';
    $bad   = 'border-red-500 focus:ring-red-500/20';
    $err   = 'mt-2 text-sm font-semibold text-[#d4131b]';

    $btnKuning = 'flex items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-5 py-3 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70';
    $btnPutih  = 'flex items-center justify-center gap-2 rounded-2xl border-2 border-[#ecd9b0] bg-white px-5 py-3 text-base font-extrabold text-[#7a5a45] transition hover:bg-[#fff1d6] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20';
@endphp

<div class="mx-auto max-w-5xl">

    {{-- Judul halaman --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#d4131b] text-white shadow-[0_4px_0_#a30d14]">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Profile</h1>
            <p class="text-sm text-[#7a5a45] sm:text-base">Atur data diri dan foto kamu</p>
        </div>
    </div>

    {{-- Pesan --}}
    @if ($sukses)
        <div class="mb-4 flex items-start gap-3 rounded-2xl border-2 border-[#1f8a2e]/30 bg-[#dcf3df] px-4 py-3 text-sm font-semibold text-[#17691f]" role="status">
            <svg class="mt-0.5 size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
            {{ $sukses }}
        </div>
    @endif
    @if ($peringatan)
        <div class="mb-4 flex items-start gap-3 rounded-2xl border-2 border-[#f5b800]/60 bg-[#fff1c2] px-4 py-3 text-sm font-semibold text-[#6b4a00]" role="alert">
            <svg class="mt-0.5 size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v3.75m0 3.75h.008M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            {{ $peringatan }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ===== Foto profil ===== --}}
        <section class="self-start rounded-3xl border-2 border-[#ecd9b0] bg-white p-6 text-center" aria-labelledby="judul-foto">
            <h2 id="judul-foto" class="text-lg font-extrabold">Foto Profil</h2>

            <div class="mt-5 flex justify-center">
                @if ($foto && ! $errors->has('foto'))
                    <img src="{{ $foto->temporaryUrl() }}" alt="Pratinjau foto baru"
                         class="size-32 rounded-full border-4 border-[#f5b800] object-cover sm:size-36">
                @else
                    @props(['user' => null])

                    @php
                        $namaAvatar = $user?->nama_lengkap ?: ($user?->email ?? '?');
                        $fotoAvatar = $user?->foto_url;
                    @endphp

                    @if ($fotoAvatar)
                        <img src="{{ $fotoAvatar }}" alt="Foto {{ $namaAvatar }}"
                            {{ $attributes->merge(['class' => 'shrink-0 rounded-full object-cover']) }}>
                    @else
                        <div {{ $attributes->merge(['class' => 'flex shrink-0 items-center justify-center rounded-full bg-[#f5b800] font-extrabold text-[#3b1f14]']) }} aria-hidden="true">{{ strtoupper(mb_substr($namaAvatar, 0, 1)) }}</div>
                    @endif
                @endif
            </div>

            <p class="mt-4 text-xs text-[#7a5a45]">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>

            <p wire:loading wire:target="foto" class="mt-3 text-sm font-bold text-[#d4131b]" role="status">Mengunggah foto...</p>
            @error('foto') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror

            <div class="mt-4 flex flex-col gap-3">
                @if ($foto && ! $errors->has('foto'))
                    <button type="button" wire:click="simpanFoto" wire:loading.attr="disabled" wire:target="simpanFoto" class="{{ $btnKuning }}">
                        <span wire:loading.remove wire:target="simpanFoto">Simpan Foto</span>
                        <span wire:loading wire:target="simpanFoto">Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="batalFoto" class="{{ $btnPutih }}">Batal</button>
                @else
                    <input id="foto" type="file" wire:model="foto" accept="image/jpeg,image/png,image/webp" class="peer sr-only">
                    <label for="foto" class="{{ $btnKuning }} cursor-pointer peer-focus-visible:ring-4 peer-focus-visible:ring-[#f5b800]/50">
                        {{ $user->foto ? 'Ganti Foto' : 'Pilih Foto' }}
                    </label>

                    @if ($user->foto)
                        <button type="button" wire:click="hapusFoto" wire:confirm="Hapus foto profil?"
                                class="rounded-2xl px-5 py-2 text-sm font-bold text-[#d4131b] transition hover:bg-[#fff1d6] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20">
                            Hapus Foto
                        </button>
                    @endif
                @endif
            </div>
        </section>

        {{-- ===== Data diri ===== --}}
        <section class="rounded-3xl border-2 border-[#ecd9b0] bg-white p-6 lg:col-span-2" aria-labelledby="judul-data">
            <h2 id="judul-data" class="text-lg font-extrabold">Data Diri</h2>

            <form wire:submit="simpan" class="mt-5 space-y-5" novalidate>

                {{-- Nama lengkap --}}
                <div>
                    <label for="nama_lengkap" class="{{ $label }}">Nama lengkap</label>
                    <input id="nama_lengkap" type="text" wire:model="nama_lengkap" autocomplete="name"
                           class="{{ $base }} {{ $errors->has('nama_lengkap') ? $bad : $ok }}">
                    @error('nama_lengkap') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Email + verifikasi --}}
                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input id="email" type="email" wire:model.live.debounce.400ms="email" autocomplete="email"
                           class="{{ $base }} {{ $errors->has('email') ? $bad : $ok }}">
                    @error('email') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror

                    @if ($emailBerubah)
                        <p class="mt-2 text-xs font-medium text-[#7a5a45]">Mengubah email membuat statusnya perlu diverifikasi ulang setelah disimpan.</p>
                    @endif

                    @if ($terverifikasi)
                        <p class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-[#dcf3df] px-3 py-1 text-sm font-bold text-[#17691f]">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Email terverifikasi
                        </p>
                    @else
                        <div class="mt-3 flex flex-col gap-3 rounded-2xl border-2 border-[#f5b800]/60 bg-[#fff1c2] p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-sm">
                                <p class="font-extrabold text-[#6b4a00]">Email belum terverifikasi</p>
                                <p class="mt-0.5 text-[#7a5a45]">
                                    @if ($emailBerubah)
                                        Simpan perubahan email dulu, lalu kirim email verifikasi.
                                    @else
                                        Kami akan mengirim tautan verifikasi ke {{ $user->email }}.
                                    @endif
                                </p>
                            </div>

                            <button type="button" wire:click="kirimVerifikasi" wire:loading.attr="disabled" wire:target="kirimVerifikasi"
                                    @disabled($emailBerubah)
                                    class="shrink-0 rounded-2xl border-2 border-[#d4131b] bg-white px-4 py-2.5 text-sm font-extrabold text-[#d4131b] transition hover:bg-[#fff8e8] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#d4131b]/20 disabled:cursor-not-allowed disabled:opacity-50">
                                <span wire:loading.remove wire:target="kirimVerifikasi">Kirim Email Verifikasi</span>
                                <span wire:loading wire:target="kirimVerifikasi">Mengirim...</span>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Jenis kelamin + tanggal lahir --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <fieldset>
                        <legend class="{{ $label }}">Jenis kelamin</legend>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach (['L' => 'Laki-laki', 'P' => 'Perempuan'] as $value => $text)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="jenis_kelamin" value="{{ $value }}" class="peer sr-only">
                                    <span class="flex items-center justify-center rounded-2xl border-2 bg-white px-2 py-3 text-sm font-semibold text-[#7a5a45] transition
                                                 peer-checked:border-[#d4131b] peer-checked:bg-[#d4131b] peer-checked:text-white
                                                 peer-focus-visible:ring-4 peer-focus-visible:ring-[#d4131b]/20
                                                 {{ $errors->has('jenis_kelamin') ? 'border-red-500' : 'border-[#ecd9b0] hover:border-[#d4131b]/50' }}">
                                        {{ $text }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('jenis_kelamin') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                    </fieldset>

                    <div>
                        <label for="tanggal_lahir" class="{{ $label }}">Tanggal lahir</label>
                        <input id="tanggal_lahir" type="date" wire:model="tanggal_lahir" autocomplete="bday"
                               class="{{ $base }} {{ $errors->has('tanggal_lahir') ? $bad : $ok }}">
                        @error('tanggal_lahir') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Sekolah + kelas --}}
                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label for="sekolah" class="{{ $label }}">Sekolah</label>
                        <input id="sekolah" type="text" wire:model="sekolah"
                               class="{{ $base }} {{ $errors->has('sekolah') ? $bad : $ok }}">
                        @error('sekolah') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kelas" class="{{ $label }}">Kelas</label>
                        <input id="kelas" type="text" wire:model="kelas"
                               class="{{ $base }} {{ $errors->has('kelas') ? $bad : $ok }}">
                        @error('kelas') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" wire:loading.attr="disabled" wire:target="simpan" class="{{ $btnKuning }} w-full sm:w-auto">
                        <span wire:loading.remove wire:target="simpan">Simpan Perubahan</span>
                        <span wire:loading wire:target="simpan">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
