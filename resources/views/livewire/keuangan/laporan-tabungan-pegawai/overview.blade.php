{{-- The best athlete wants his opponent at his best. --}}
{{-- OVERVIEW --}}
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
                            Overview
                        </h5>
                    </div>

                </div>
            </div>

            {{-- FILTER --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">

                <div>
                    <select
                        wire:model="selectedJabatan"
                        class="form-select"
                        style="cursor:pointer"
                        data-bs-toggle="tooltip"
                        data-bs-trigger="hover"
                        data-bs-placement="top"
                        title="Pilih Jabatan">

                        <option value="">👤 Semua Jabatan</option>

                        @foreach ($select_jabatan as $item)
                            <option value="{{ $item->ms_jabatan_id }}">
                                {{ $item->nama_jabatan }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Jika export nanti diaktifkan --}}
                {{--
                <button
                    class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1">

                    <i class="ri-file-excel-2-line"></i>
                    <span>Excel</span>

                </button>
                --}}

            </div>

        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        <div class="row g-0 text-center">

            {{-- SALDO --}}
            <div class="col-4 col-sm-12">
                <div class="p-3 border border-dashed border-end-0">

                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-info">
                            Rp{{ number_format($totalSaldo, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-wallet-3-line display-8 text-info"></i>
                        Saldo
                    </p>

                </div>
            </div>

            {{-- KREDIT --}}
            <div class="col-4 col-sm-6">
                <div class="p-3 border border-dashed border-start-0">

                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-success">
                            Rp{{ number_format($totalKredit, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-arrow-up-circle-line display-8 text-success"></i>
                        Kredit
                    </p>

                </div>
            </div>

            {{-- DEBIT --}}
            <div class="col-4 col-sm-6">
                <div class="p-3 border border-dashed border-start-0">

                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-danger">
                            Rp{{ number_format($totalDebit, 0, ',', '.') }}
                        </span>
                    </h5>

                    <p class="text-muted mb-0">
                        <i class="ri-arrow-down-circle-line display-8 text-danger"></i>
                        Debit
                    </p>

                </div>
            </div>

        </div>

    </div>

</div>