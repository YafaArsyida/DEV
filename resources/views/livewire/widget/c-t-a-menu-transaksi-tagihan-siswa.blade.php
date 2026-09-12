<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        {{-- QUICK ACTION --}}
        <div class="px-4 py-3 bg-primary-subtle border-bottom d-flex align-items-center gap-3">

            <div class="avatar-xs flex-shrink-0">
                <div class="avatar-title bg-primary text-white rounded-circle">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>

            <div class="flex-grow-1 text-truncate">
                <span class="text-primary">
                    Siap mencatat pembayaran?
                </span>
                <strong class="text-primary">
                    Akses Transaksi Tagihan Siswa.
                </strong>
            </div>

            <div class="flex-shrink-0">
                <a
                    href="{{ route('transaksi.tagihan-siswa') }}"
                    class="btn btn-sm btn-primary rounded-pill px-3"
                >
                    Buka Sekarang
                    <i class="ri-arrow-right-line align-middle ms-1"></i>
                </a>
            </div>

        </div>

        {{-- CONTENT --}}
        <div class="p-4">
            <div class="row align-items-center g-4">
                {{-- TEXT --}}
                <div class="col-lg-12">

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                            <i class="ri-flashlight-line me-1"></i>
                            Akses Cepat
                        </span>
                    </div>

                    <h4 class="fw-bold mb-2">
                        Pencatatan Tagihan Lebih Cepat dan Terorganisir
                    </h4>

                    <p class="text-muted mb-4">
                        Kelola pembayaran SPP, DSP, dan berbagai tagihan siswa
                        dengan mudah, akurat, dan terdokumentasi dalam satu menu.
                    </p>

                    <a
                        href="{{ route('transaksi.tagihan-siswa') }}"
                        class="btn btn-success rounded-pill px-4"
                    >
                        <i class="ri-login-circle-line align-middle me-1"></i>
                        Masuk Menu Transaksi
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>