<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    {{-- Logo ringkas: bunga rafflesia + tulisan PADEK berwarna --}}
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
        <svg viewBox="0 0 100 100" class="size-9 shrink-0" aria-hidden="true">
            <ellipse cx="22" cy="86" rx="17" ry="7" fill="#1f8a2e" transform="rotate(-18 22 86)"/>
            <ellipse cx="78" cy="86" rx="17" ry="7" fill="#1f8a2e" transform="rotate(18 78 86)"/>
            <g fill="#d4131b" stroke="#a30d14" stroke-width="2">
                <circle cx="50" cy="24" r="22"/>
                <circle cx="74.7" cy="42" r="22"/>
                <circle cx="65.3" cy="71" r="22"/>
                <circle cx="34.7" cy="71" r="22"/>
                <circle cx="25.3" cy="42" r="22"/>
            </g>
            <circle cx="50" cy="50" r="17" fill="#e9b27a" stroke="#c9884a" stroke-width="2"/>
            <circle cx="50" cy="50" r="10" fill="#5a1f14"/>
        </svg>
        <span class="font-[family-name:Fredoka,ui-rounded,system-ui,sans-serif] text-2xl font-bold leading-none tracking-tight" aria-label="PADEK">
            <span class="text-[#d4131b]">P</span><span class="text-[#f5b800]">A</span><span class="text-[#1f8a2e]">D</span><span class="text-[#d4131b]">E</span><span class="text-[#d4131b]">K</span>
        </span>
    </div>
</div>
