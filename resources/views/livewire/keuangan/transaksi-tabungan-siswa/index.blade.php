<div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="offcanvasTabungan" aria-labelledby="offcanvasTabunganLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-chart-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Transaksi Tabungan Siswa
                    </h5>
                    {{-- <small class="text-muted">
                        {{ $nama_siswa ?? 'Siswa' }}
                    </small> --}}
                </div>
            </div>
            <!-- Kanan -->
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-lg-12">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-4 col-md-4 sticky-side-div">
                                @livewire('keuangan.transaksi-tabungan-siswa.data-siswa')
                            </div>
                            <!--end col-->
                            <div class="col-xxl-8 col-md-8">
                                @livewire('keuangan.transaksi-tabungan-siswa.data-tabungan')
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end tab-content-->
            </div>
        </div>
    </div>
</div>
