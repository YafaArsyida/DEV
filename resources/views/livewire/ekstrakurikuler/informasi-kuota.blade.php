<div class="container">
    <div class="row  justify-content-center text-center gy-4">
        @foreach ($data as $item)
        <div class="col-lg-3 col-6">
            <div>
                <h2 class="mb-2">
                    <span class="counter-value" data-target="{{ $item->kuota }}">{{ $item->kuota }}</span>
                </h2>
                <div class="text-muted">
                    Kuota {{ $item->nama_ekstrakurikuler }}
                    @if ($item->ms_ekstrakurikuler_id == 1)
                        <br><small class="text-muted fst-italic">(Khusus Kelas 3–6)</small>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>