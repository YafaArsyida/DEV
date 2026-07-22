<div class="container">

    {{-- Section Header --}}
    <div class="row justify-content-center mb-5">
        <div class="col-lg-7 text-center">

            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 mb-3">
                <i class="ri-heart-3-line align-middle me-1"></i>
                Pilihan Ekstrakurikuler
            </span>

            <h2 class="fw-semibold mb-3">
                Temukan Kegiatan yang
                <span class="text-danger">Kamu Sukai</span>
            </h2>

            <p class="text-muted mb-0">
                Pilih kegiatan yang sesuai dengan minat dan bakatmu.
                Pastikan masih tersedia tempat untuk menjadi bagian di dalamnya.
            </p>

        </div>
    </div>


    {{-- Kuota Cards --}}
    <div class="row g-4 justify-content-center">

        @foreach ($data as $item)

            <div class="col-lg-4 col-md-6">

                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                    <div class="card-body p-4">

                        {{-- Icon --}}
                        <div class="d-flex align-items-center justify-content-between mb-4">

                            <div class="avatar-sm">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-20">
                                    <i class="ri-star-smile-line"></i>
                                </div>
                            </div>

                            <span class="badge bg-light text-muted rounded-pill">
                                {{ $item->ms_ekstrakurikuler_id == 1 ? 'Kelas 3–6' : 'Semua Kelas' }}
                            </span>

                        </div>


                        {{-- Nama --}}
                        <h5 class="fw-semibold mb-2">
                            {{ $item->nama_ekstrakurikuler }}
                        </h5>


                        {{-- Kuota --}}
                        <div class="d-flex align-items-end gap-2 mb-3">

                            <h2 class="mb-0 fw-bold text-danger">
                                <span class="counter-value"
                                    data-target="{{ $item->kuota }}">
                                    {{ $item->kuota }}
                                </span>
                            </h2>

                            <span class="text-muted mb-1">
                                kursi tersedia
                            </span>

                        </div>


                        {{-- Progress --}}
                        <div class="progress rounded-pill"
                            style="height: 6px;">

                            <div class="progress-bar bg-danger"
                                role="progressbar"
                                style="width: 65%;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">
                                Kuota terbatas
                            </small>

                            <small class="text-danger fw-medium">
                                Segera daftar
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>