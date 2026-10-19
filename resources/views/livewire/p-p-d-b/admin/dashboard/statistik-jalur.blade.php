<div class="row g-3">
    @foreach ($statistik as $item)
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="text-muted mb-2">
                                {{ $item['label'] }}
                            </p>

                            <h4 class="fw-bold mb-1">
                                {{ $item['value'] }}
                            </h4>

                            <small class="text-muted">
                                {{ $item['description'] }}
                            </small>
                        </div>

                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }} rounded-3 fs-3">
                                <i class="mdi {{ $item['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>