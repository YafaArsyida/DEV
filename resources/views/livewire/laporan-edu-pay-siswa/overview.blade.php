{{-- The best athlete wants his opponent at his best. --}}
<div class="card mb-1">
    <div class="card-header border-0 align-items-center d-flex">
        <h5 class="card-title mb-0 flex-grow-1">Overview</h5>
        <div>
            <select wire:model="selectedKelas" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                <option value="">Semua Kelas</option>
                @foreach ($select_kelas as $item)    
                <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                @endforeach
            </select>
            {{-- <button data-bs-toggle="modal" data-bs-target="#ExportOverviewTabungan" wire:click.prevent="ExportOverviewTabungan"  class="btn btn-soft-success"><i class="ri-file-excel-2-line fs-17"></i> Export</button> --}}
        </div>
    </div><!-- end card header -->
    <div class="card-body pt-0">
        <div class="row g-0 text-center">
            <div class="col-6 col-sm-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-success">
                            RP{{ number_format($total_topup, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-success"></i>
                        Top Up Offline
                    </p>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-warning">
                            RP{{ number_format($total_topup_online, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-warning"></i>
                        Top Up Online
                    </p>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-secondary">
                            RP{{ number_format($total_pengembalian_dana, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-secondary"></i>
                        Pengembalian dana
                    </p>
                </div>
            </div>
            <div class="col-6 col-sm-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-danger">
                            RP{{ number_format($total_penarikan, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-danger"></i>
                        Penarikan
                    </p>
                </div>
            </div>
            <!--end col-->
            <div class="col-6 col-sm-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-danger">
                            RP{{ number_format($total_pembayaran, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-danger"></i>
                        Pembayaran
                    </p>
                </div>
            </div>
            <!--end col-->
            <div class="col-6 col-sm-4">
                <div class="p-3 border border-dashed border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-info">
                            RP{{ number_format($saldo, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-info"></i>
                        Saldo
                    </p>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
</div>
