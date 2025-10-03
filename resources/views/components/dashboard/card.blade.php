@props([
    'icon', 'title', 'value', 'subtitle' => null, 'iconBg' => 'bg-primary', 'valueClass' => 'text-primary', 'footer' => null
])
<div class="card info-card shadow border-0 bg-white animate__animated animate__fadeInDown">
    <div class="card-body d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center {{ $iconBg }} text-white me-3 shadow" style="width: 56px; height: 56px; font-size:2rem;">
            <i class="{{ $icon }}"></i>
        </div>
        <div>
            <h6 class="card-title mb-1 {{ $valueClass }}" style="font-size:1.1rem;">{{ $title }}</h6>
            <span class="fs-3 fw-bold {{ $valueClass }}">{{ $value }}</span>
            @if($subtitle)
                <div class="small text-muted mt-1">{!! $subtitle !!}</div>
            @endif
            @if($footer)
                <div class="mt-2">{!! $footer !!}</div>
            @endif
        </div>
    </div>
</div>
