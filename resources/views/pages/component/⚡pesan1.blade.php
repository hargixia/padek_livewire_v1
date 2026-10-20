<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    {{-- Pesan sukses --}}
    @if (session()->has('sukses'))
        <div class="mb-4 flex items-center gap-3 rounded-2xl border-2 border-[#1f8a2e]/30 bg-[#dcf3df] px-4 py-3 text-sm font-semibold text-[#17691f]" role="status">
            <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
            {{ session('sukses') }}
        </div>
    @endif

    {{-- Pesan gagal --}}
    @if (session()->has('gagal'))
        <div class="mb-4 flex items-center gap-3 rounded-2xl border-2 border-[#761420]/30 bg-[#f01c35] px-4 py-3 text-sm font-semibold text-[#f7e7e9]" role="status">
            
            {{ session('gagal') }}
        </div>
    @endif
</div>
