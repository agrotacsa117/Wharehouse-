@props([
    'title', 'percent', 'color' => 'primary', 'usedLabel' => 'Ocupado', 'freeLabel' => 'Disponible', 'icon' => 'fa-solid fa-boxes-stacked', 'ocupados' => null, 'max' => null
])
<div class="card shadow border-0 text-center p-3 animate__animated animate__fadeInUp">
    <div class="mx-auto mb-2" style="width:110px;height:110px;position:relative;">
        <svg width="110" height="110">
            <circle cx="55" cy="55" r="48" stroke="#e9ecef" stroke-width="10" fill="none"/>
            <circle cx="55" cy="55" r="48" stroke="var(--bs-{{ $color }})" stroke-width="10" fill="none"
                stroke-dasharray="301.59" stroke-dashoffset="{{ 301.59 - (301.59 * $percent / 100) }}" stroke-linecap="round"/>
        </svg>
        <div class="position-absolute top-50 start-50 translate-middle">
            <i class="{{ $icon }} text-{{ $color }}" style="font-size:2rem;"></i>
            <div class="fw-bold fs-4 text-{{ $color }}">{{ $percent }}%</div>
        </div>
    </div>
    <div class="fw-bold mb-1">{{ $title }}</div>
    @if($ocupados !== null && $max !== null)
        <div class="small mb-1 text-secondary">{{ $ocupados }} / {{ $max }} racks</div>
    @endif
    <div class="small">
        <span class="text-{{ $color }} fw-bold">{{ $usedLabel }}</span>
        <span class="text-muted">/</span>
        <span class="text-success fw-bold">{{ $freeLabel }}</span>
    </div>
</div>
