<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    {{-- Pita bermotif songket (merah + garis emas). Atur tinggi lewat class, contoh: <x-padek.songket class="h-3" /> --}}
    <div {{ $attributes->merge(['class' => 'border-y-2 border-[#f5b800]']) }}
        style="background-color:#b3121a;background-image:linear-gradient(45deg,transparent 44%,rgba(245,184,0,.75) 44%,rgba(245,184,0,.75) 56%,transparent 56%),linear-gradient(-45deg,transparent 44%,rgba(245,184,0,.75) 44%,rgba(245,184,0,.75) 56%,transparent 56%);background-size:12px 12px;"
        aria-hidden="true">
    </div>
</div>
