<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    {{--
    Avatar user: foto profil bila ada, jika tidak tampil inisial.
    Pemakaian: <x-padek.avatar :user="$user" class="size-10 text-lg" />
    --}}
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

</div>
