{{-- The best athlete wants his opponent at his best. --}}
<div class="card">
    <div class="card-header border-0 align-items-center d-flex">
        <h5 class="card-title mb-0 flex-grow-1">Overview</h5>
        <div>
            <!-- Filter Periode -->
            <select wire:model="selectedPeriode" style="cursor: pointer" 
                class="form-select"
                data-bs-toggle="tooltip" data-bs-trigger="hover" 
                data-bs-placement="top" title="Pilih Periode">
                <option value="hari_ini">Hari Ini</option>
                <option value="kemarin">Kemarin</option>
                <option value="1_bulan">1 Bulan Terakhir</option>
                <option value="3_bulan">3 Bulan Terakhir</option>
            </select>
        </div>
    </div><!-- end card header -->

    <div class="card-body pt-0">
        <div class="row g-0 text-center">
            <!-- Total Semua -->
            <div class="col-12">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-primary">
                            RP{{ number_format($totalSemua, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-primary"></i>
                        Total Transaksi
                    </p>
                </div>
            </div>

            <!-- Total Siswa -->
            <div class="col-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-danger">
                            RP{{ number_format($totalUmum, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-user-3-line display-8 text-danger"></i>
                        Transaksi Umum
                    </p>
                </div>
            </div>

            <!-- Total Siswa -->
            <div class="col-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-success">
                            RP{{ number_format($totalSiswa, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-user-3-line display-8 text-success"></i>
                        Transaksi Siswa
                    </p>
                </div>
            </div>

            <!-- Total Pegawai -->
            <div class="col-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-14 text-info">
                            RP{{ number_format($totalPegawai, 0, ',', '.') }}
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
