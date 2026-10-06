<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Daftar - PADEK')] class extends Component
{
    public string $nama_lengkap = '';
    public string $email = '';
    public string $jenis_kelamin = '';
    public string $tanggal_lahir = '';
    public string $sekolah = '';
    public string $kelas = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPassword = false;

    public function save()
    {
        $this->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today',
            'sekolah'       => 'required|string|max:255',
            'kelas'         => 'required|string|max:50',
            'password'      => 'required|min:6|confirmed',
        ], [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah terdaftar.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak valid.',
            'sekolah.required'       => 'Nama sekolah wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak sama.',
        ]);

        $user = User::create([
            'nama_lengkap'  => $this->nama_lengkap,
            'email'         => $this->email,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'sekolah'       => $this->sekolah,
            'kelas'         => $this->kelas,
            'password'      => Hash::make($this->password),
        ]);

        auth()->login($user);
        session()->regenerate();

        return $this->redirect('/dashboard', navigate: true);
    }
};
?>

@php
    $base  = 'block w-full rounded-2xl border-2 bg-white px-4 py-3 text-base text-[#3b1f14] placeholder-[#b8a58a] outline-none transition focus:ring-4';
    $ok    = 'border-[#ecd9b0] focus:border-[#d4131b] focus:ring-[#d4131b]/15';
    $bad   = 'border-red-500 focus:ring-red-500/20';
    $label = 'mb-2 block text-sm font-bold text-[#3b1f14]';
    $err   = 'mt-2 text-sm font-semibold text-[#d4131b]';
@endphp

<div class="flex min-h-screen flex-col bg-[#fff8e8] text-[#3b1f14] lg:grid lg:grid-cols-2">

    <livewire:pages::component.hero />

    <main class="relative z-10 -mt-8 flex flex-1 rounded-t-[2rem] bg-[#fff8e8] px-6 pb-10 pt-8 sm:px-10 lg:mt-0 lg:items-center lg:justify-center lg:rounded-none lg:py-12">
        <div class="mx-auto flex w-full max-w-md flex-col lg:mx-0">

            <div class="mb-7">
                <h2 class="text-3xl font-extrabold tracking-tight">Gabung petualangannya!</h2>
                <p class="mt-2 text-base text-[#7a5a45]">Isi data diri kamu dulu ya.</p>
            </div>

            <form wire:submit="save" class="space-y-5" novalidate>

                {{-- Nama lengkap --}}
                <div>
                    <label for="nama_lengkap" class="{{ $label }}">Nama lengkap</label>
                    <input id="nama_lengkap" type="text" wire:model="nama_lengkap" autocomplete="name" autofocus
                           placeholder="Nama sesuai identitas"
                           class="{{ $base }} {{ $errors->has('nama_lengkap') ? $bad : $ok }}">
                    @error('nama_lengkap') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input id="email" type="email" wire:model="email" autocomplete="email"
                           placeholder="nama@email.com"
                           class="{{ $base }} {{ $errors->has('email') ? $bad : $ok }}">
                    @error('email') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
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
                               placeholder="Nama sekolah"
                               class="{{ $base }} {{ $errors->has('sekolah') ? $bad : $ok }}">
                        @error('sekolah') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kelas" class="{{ $label }}">Kelas</label>
                        <input id="kelas" type="text" wire:model="kelas"
                               placeholder="5 SD"
                               class="{{ $base }} {{ $errors->has('kelas') ? $bad : $ok }}">
                        @error('kelas') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="{{ $label }}">Password</label>
                    <div class="relative">
                        <input id="password" wire:model="password" autocomplete="new-password"
                               type="{{ $showPassword ? 'text' : 'password' }}"
                               placeholder="Minimal 6 karakter"
                               class="{{ $base }} pr-28 {{ $errors->has('password') ? $bad : $ok }}">
                        <button type="button" wire:click="$toggle('showPassword')"
                                class="absolute inset-y-0 right-0 rounded-r-2xl px-4 text-sm font-bold text-[#d4131b] transition hover:text-[#a30d14] focus:outline-none focus-visible:underline">
                            {{ $showPassword ? 'Sembunyikan' : 'Tampilkan' }}
                        </button>
                    </div>
                    @error('password') <p class="{{ $err }}" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi password --}}
                <div>
                    <label for="password_confirmation" class="{{ $label }}">Ulangi password</label>
                    <input id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password"
                           type="{{ $showPassword ? 'text' : 'password' }}"
                           placeholder="Ketik ulang password"
                           class="{{ $base }} {{ $ok }}">
                </div>

                {{-- Tombol --}}
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-4 py-3.5 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                    <svg wire:loading wire:target="save" class="size-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/>
                        <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round" class="opacity-75"/>
                    </svg>
                    <span wire:loading.remove wire:target="save">Daftar Sekarang</span>
                    <span wire:loading wire:target="save">Menyiapkan petualanganmu...</span>
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-[#7a5a45]">
                Sudah punya akun?
                <a href="/masuk" wire:navigate class="font-extrabold text-[#d4131b] underline-offset-4 hover:underline focus:outline-none focus-visible:underline">Masuk di sini</a>
            </p>

            <livewire:pages::component.songket class="mt-8 h-3 rounded-full lg:hidden" />
        </div>
    </main>
</div>
