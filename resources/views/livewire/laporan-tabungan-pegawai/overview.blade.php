{{-- The best athlete wants his opponent at his best. --}}
<div class="card">
    <div class="card-header border-0 align-items-center d-flex">
        <h5 class="card-title mb-0 flex-grow-1">Overview</h5>
        <div>
            <select wire:model="selectedJabatan" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jabatan">
                <option value="">Semua Jabatan</option>
                @foreach ($select_jabatan as $item)    
                <option value="{{ $item->ms_jabatan_id }}">{{ $item->nama_jabatan }}</option>
                @endforeach
            </select>
            {{-- <button data-bs-toggle="modal" data-bs-target="#ExportOverviewTabungan" wire:click.prevent="ExportOverviewTabungan"  class="btn btn-soft-success"><i class="ri-file-excel-2-line fs-17"></i> Export</button> --}}
        </div>
    </div><!-- end card header -->
    <div class="card-body pt-0">
        <div class="row g-0 text-center">
            <div class="col-4 col-sm-12">
                <div class="p-3 border border-dashed border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-info">
                            RP{{ number_format($totalSaldo, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-success"></i>
                        Saldo
                    </p>
                </div>
            </div>
            <!--end col-->
            <div class="col-4 col-sm-6">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-success">
                            RP{{ number_format($totalKredit, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-success"></i>
                        Kredit
                    </p>
                </div>
            </div>
            <!--end col-->
            <div class="col-4 col-sm-6">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold text-danger">
                            RP{{ number_format($totalDebit, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-pulse-line display-8 text-success"></i>
                        Debit
                    </p>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
</div>
