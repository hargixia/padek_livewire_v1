<?php

use Livewire\Attributes\Title;
use Livewire\Component;

use App\Http\Controllers\api_support;

new #[Title('Masuk - PADEK')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public bool $showPassword = false;

    public function save()
    {

        $as = new api_support();

        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $this->password = $as->my_encrypt($this->password);

        if (! auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah.');
            $this->reset('password');
            return;
        }

        session()->regenerate();

        return $this->redirectIntended('/dashboard', navigate: true);
    }
};
?>

@php
    $base  = 'block w-full rounded-2xl border-2 bg-white px-4 py-3 text-base text-[#3b1f14] placeholder-[#b8a58a] outline-none transition focus:ring-4';
    $ok    = 'border-[#ecd9b0] focus:border-[#d4131b] focus:ring-[#d4131b]/15';
    $bad   = 'border-red-500 focus:ring-red-500/20';
    $label = 'mb-2 block text-sm font-bold text-[#3b1f14]';
@endphp

<div class="flex min-h-screen flex-col bg-[#fff8e8] text-[#3b1f14] lg:grid lg:grid-cols-2">

    <livewire:pages::component.hero/>

    <main class="relative z-10 -mt-8 flex flex-1 rounded-t-[2rem] bg-[#fff8e8] px-6 pb-10 pt-8 sm:px-10 lg:mt-0 lg:items-center lg:justify-center lg:rounded-none lg:py-12">
        <div class="mx-auto flex w-full max-w-md flex-col lg:mx-0">

            <div class="mb-7">
                <h2 class="text-3xl font-extrabold tracking-tight">Ayo masuk, Petualang!</h2>
                <p class="mt-2 text-base text-[#7a5a45]">Lanjutkan petualangan belajarmu hari ini.</p>
            </div>

            <form wire:submit="save" class="space-y-5" novalidate>

                {{-- Email --}}
                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input id="email" type="email" wire:model="email" autocomplete="email" autofocus
                           placeholder="nama@email.com"
                           class="{{ $base }} {{ $errors->has('email') ? $bad : $ok }}">
                    @error('email') <p class="mt-2 text-sm font-semibold text-[#d4131b]" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="{{ $label }}">Password</label>
                    <div class="relative">
                        <input id="password" wire:model="password" autocomplete="current-password"
                               type="{{ $showPassword ? 'text' : 'password' }}"
                               placeholder="Minimal 6 karakter"
                               class="{{ $base }} pr-28 {{ $errors->has('password') ? $bad : $ok }}">
                        <button type="button" wire:click="$toggle('showPassword')"
                                class="absolute inset-y-0 right-0 rounded-r-2xl px-4 text-sm font-bold text-[#d4131b] transition hover:text-[#a30d14] focus:outline-none focus-visible:underline">
                            {{ $showPassword ? 'Sembunyikan' : 'Tampilkan' }}
                        </button>
                    </div>
                    @error('password') <p class="mt-2 text-sm font-semibold text-[#d4131b]" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Ingat saya --}}
                <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-[#7a5a45]">
                    <input type="checkbox" wire:model="remember"
                           class="size-5 rounded-md border-2 border-[#ecd9b0] accent-[#d4131b]">
                    Ingat saya
                </label>

                {{-- Tombol --}}
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#f5b800] px-4 py-3.5 text-base font-extrabold text-[#3b1f14] shadow-[0_5px_0_#c48f00] transition hover:brightness-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#f5b800]/50 active:translate-y-[3px] active:shadow-[0_2px_0_#c48f00] disabled:cursor-not-allowed disabled:opacity-70">
                    <svg wire:loading wire:target="save" class="size-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/>
                        <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round" class="opacity-75"/>
                    </svg>
                    <span wire:loading.remove wire:target="save">Mulai Petualangan</span>
                    <span wire:loading wire:target="save">Memuat petualangan seru...</span>
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-[#7a5a45]">
                Belum punya akun?
                <a href="/daftar" wire:navigate class="font-extrabold text-[#d4131b] underline-offset-4 hover:underline focus:outline-none focus-visible:underline">Daftar dulu yuk</a>
            </p>

        </div>
    </main>
</div>
