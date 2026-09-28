{{-- The best athlete wants his opponent at his best. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

            {{-- TITLE --}}
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                        <i class="ri-wallet-3-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-0">
                        Overview Kantin
                    </h5>
                </div>
            </div>

            {{-- FILTER PERIODE --}}
            <div>
                <select wire:model="selectedPeriode"
                    class="form-select" style="cursor: pointer"
                    data-bs-toggle="tooltip" data-bs-trigger="hover"
                    data-bs-placement="top" title="Pilih Periode">

                    <option value="hari_ini">Hari Ini</option>
                    <option value="kemarin">Kemarin</option>
                    <option value="1_bulan">1 Bulan Terakhir</option>
                    <option value="3_bulan">3 Bulan Terakhir</option>
                </select>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body pt-0">
        <div class="row g-0 text-center">
            {{-- TOTAL SEMUA --}}
            <div class="col-12">
                <div class="p-3 border border-dashed">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-primary">
                            Rp{{ number_format($totalSemua, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-primary"></i>
                        Total Transaksi
                    </p>
                </div>
            </div>

            {{-- TRANSAKSI UMUM --}}
            <div class="col-4">
                <div class="p-3 border border-dashed border-top-0 border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-danger">
                            Rp{{ number_format($totalUmum, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-user-3-line display-8 text-danger"></i>
                        Transaksi Umum
                    </p>
                </div>
            </div>

            {{-- TRANSAKSI SISWA --}}
            <div class="col-4">
                <div class="p-3 border border-dashed border-top-0 border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-success">
                            Rp{{ number_format($totalSiswa, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-user-3-line display-8 text-success"></i>
                        Transaksi Siswa
                    </p>
                </div>
            </div>

            {{-- TRANSAKSI PEGAWAI --}}
            <div class="col-4">
                <div class="p-3 border border-dashed border-top-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-info">
                            Rp{{ number_format($totalPegawai, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-briefcase-4-line display-8 text-info"></i>
                        Transaksi Pegawai
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>