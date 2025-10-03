@props([
    'icon', 'title', 'dangerValue', 'successValue', 'dangerLabel', 'successLabel', 'dangerPercent', 'total', 'iconBg' => 'bg-danger', 'successBg' => 'bg-success', 'footer' => null
])
<div class="card info-card warning-card shadow border-0">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center {{ $iconBg }} text-white">
                <i class="{{ $icon }}"></i>
            </div>
            <div class="ps-3 w-100">
                <h6>
                    <span class="text-danger">{{ $dangerValue }}</span>
                    <small class="text-muted"> / </small>
                    <span class="text-success">{{ $successValue }}</span>
                </h6>
                <div class="text-muted small pt-1">
                    <span class="text-danger fw-bold">{{ $dangerLabel }}</span>
                    <small class="text-muted"> / </small>
                    <span class="text-success fw-bold">{{ $successLabel }}</span>
                </div>
                @if($total > 0)
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $dangerPercent }}%" aria-valuenow="{{ $dangerPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar {{ $successBg }}" role="progressbar" style="width: {{ 100 - $dangerPercent }}%" aria-valuenow="{{ 100 - $dangerPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="small text-muted mt-1">
                        <span class="text-danger fw-bold">{{ $dangerPercent }}%</span> del total de {{ number_format($total, 0, ',', '.') }} productos.
                    </div>
                @else
                    <div class="small text-muted mt-1">
                        No hay productos en esta bodega.
                    </div>
                @endif
                @if($footer)
                    <div class="mt-2">{!! $footer !!}</div>
                @endif
            </div>
        </div>
    </div>
</div>
