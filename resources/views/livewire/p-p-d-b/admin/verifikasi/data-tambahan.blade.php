<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-0">

    {{-- HEADER --}}
    <div class="card-header bg-transparent">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-question-answer-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Informasi Tambahan
                </h5>
                <small class="text-muted">
                    Jawaban atas pertanyaan tambahan dari orang tua/wali.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body p-3 p-md-4">
        @if ($jawaban->isEmpty())
            <div class="text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-question-answer-line"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Belum ada informasi tambahan
                </h6>

                <p class="text-muted small mb-0">
                    Orang tua/wali belum mengisi jawaban pertanyaan tambahan.
                </p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($jawaban as $item)
                    <div class="col-12">
                        <div>
                            <small class="text-muted d-block mb-2">
                                {{ $item->ppdb_pertanyaan->pertanyaan ?? 'Pertanyaan tidak tersedia' }}
                            </small>

                            <div class="fw-semibold text-break">{{ filled($item->jawaban) ? $item->jawaban : '—' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>