{{-- The best athlete wants his opponent at his best. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                            <i class="ri-bar-chart-box-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Overview Tabungan
                        </h5>
                        {{-- <small class="text-muted">
                            Ringkasan data tabungan siswa berdasarkan kelas yang dipilih.
                        </small> --}}
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">

                <div >
                    <select
                        wire:model="selectedKelas"
                        class="form-select"
                        style="cursor:pointer;"
                        data-bs-toggle="tooltip"
                        title="Pilih Kelas">

                        <option value="">📚 Semua Kelas</option>

                        @foreach ($select_kelas as $item)
                            <option value="{{ $item->ms_kelas_id }}">
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Nanti jika export diaktifkan --}}
                {{-- 
                <button
                    class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1"
                    wire:click.prevent="ExportOverviewTabungan">

                    <i class="ri-file-excel-2-line"></i>
                    <span>Excel</span>
                </button>
                --}}

            </div>

        </div>
    </div>
    <div class="card-body">
        <div class="row g-0 text-center">
            <div class="col-4 col-sm-12">
                <div class="p-3 border border-dashed border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-primary">
                            Rp{{ number_format($totalSaldo, 0, ',', '.') }}
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
                        <span class="fw-semibold fs-12 text-success">
                            Rp{{ number_format($totalKredit, 0, ',', '.') }}
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
                        <span class="fw-semibold fs-12 text-danger">
                            Rp{{ number_format($totalDebit, 0, ',', '.') }}
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
