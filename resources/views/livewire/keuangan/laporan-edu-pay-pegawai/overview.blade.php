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
                            <i class="ri-wallet-3-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Overview EduPay
                        </h5>
                        {{-- <small class="text-muted">
                            Ringkasan transaksi EduPay berdasarkan kelas.
                        </small> --}}
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
                        title="Pilih Jabatan">

                        <option value="">📚 Semua Jabatan</option>

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

            <div class="col-6 col-lg-4">
                <div class="p-3 border border-dashed border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-success">
                            Rp{{ number_format($total_topup_tunai, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-arrow-up-circle-line text-success"></i>
                        Top Up Tunai
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-success">
                            Rp{{ number_format($total_topup_online, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-global-line text-success"></i>
                        Top Up Online
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="p-3 border border-dashed border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-success">
                            Rp{{ number_format($total_pengembalian_dana, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-refund-2-line text-success"></i>
                        Pengembalian Dana
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="p-3 border border-dashed border-top-0 border-end-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-danger">
                            Rp{{ number_format($total_penarikan, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-arrow-down-circle-line text-danger"></i>
                        Penarikan
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="p-3 border border-dashed border-top-0 border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-danger">
                            Rp{{ number_format($total_pembayaran, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-secure-payment-line text-danger"></i>
                        Pembayaran
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="p-3 border border-dashed border-top-0 border-start-0">
                    <h5 class="mb-1">
                        <span class="fw-semibold fs-12 text-danger">
                            Rp{{ number_format($total_kantin, 0, ',', '.') }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0">
                        <i class="ri-store-2-line text-danger"></i>
                        Kantin
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>