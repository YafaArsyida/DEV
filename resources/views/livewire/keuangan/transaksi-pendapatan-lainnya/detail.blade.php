<div
    wire:ignore.self
    class="modal fade"
    id="detailTransaksiPendapatanLainnya"
    tabindex="-1"
    aria-labelledby="detailTransaksiPendapatanLainnyaLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-eye-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Detail Transaksi Pendapatan Lainnya
                        </h5>

                        <small class="text-muted">
                            Informasi lengkap transaksi dan jurnal
                        </small>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal"
                >
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            <div class="modal-body">
                @if ($transaksi)
                    <div class="mb-4">
                        @if ($transaksi->status_transaksi === 'dibatalkan')
                            <div class="alert alert-danger d-flex align-items-center gap-2 mb-0">
                                <i class="ri-close-circle-fill fs-18"></i>
                                <div>
                                    <strong>Transaksi Dibatalkan</strong>
                                    <div class="small">
                                        Transaksi ini sudah dibatalkan dan memiliki jurnal reversal.
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success d-flex align-items-center gap-2 mb-0">
                                <i class="ri-checkbox-circle-fill fs-18"></i>
                                <div>
                                    <strong>Transaksi Berhasil</strong>
                                    <div class="small">
                                        Transaksi masih aktif.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card border shadow-none mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-file-list-3-line me-1"></i>
                                Informasi Transaksi
                            </h6>
                        </div>

                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th style="width: 180px;">
                                        Jenis Transaksi
                                    </th>
                                    <td>
                                        {{ $transaksi->akuntansi_rekening->nama_rekening ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Nominal
                                    </th>
                                    <td>
                                        <span class="fw-semibold fs-14
                                            {{ $transaksi->status_transaksi === 'dibatalkan'
                                                ? 'text-muted text-decoration-line-through'
                                                : 'text-success' }}">
                                            Rp{{ number_format($transaksi->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Metode Pembayaran
                                    </th>
                                    <td>
                                        {{ $transaksi->metode_pembayaran ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Tanggal Transaksi
                                    </th>
                                    <td>
                                        {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                            $transaksi->tanggal,
                                            'd F Y H:i:s'
                                        ) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Petugas
                                    </th>
                                    <td>
                                        {{ $transaksi->ms_pengguna->nama ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Keterangan
                                    </th>
                                    <td>
                                        {{ $transaksi->deskripsi ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Status
                                    </th>
                                    <td>
                                        @if ($transaksi->status_transaksi === 'dibatalkan')
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ri-close-circle-line me-1"></i>
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ri-checkbox-circle-line me-1"></i>
                                                Berhasil
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($transaksi->akuntansi_jurnal)
                        <div class="card border shadow-none mb-4">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">
                                            <i class="ri-book-2-line me-1"></i>
                                            Jurnal Transaksi oleh {{ $transaksi->akuntansi_jurnal->ms_pengguna->nama ?? '-' }}
                                        </h6>

                                        <small class="text-muted">
                                            {{ $transaksi->akuntansi_jurnal->deskripsi ?? '-' }}
                                        </small>
                                    </div>

                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $transaksi->akuntansi_jurnal->nomor_jurnal }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Rekening</th>
                                                <th class="text-center">Posisi</th>
                                                <th class="text-end">Nominal</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($transaksi->akuntansi_jurnal->akuntansi_jurnal_detail as $detail)
                                                <tr class="fw-medium">
                                                    <td>
                                                        {{ $detail->akuntansi_rekening->nama_rekening }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if ($detail->posisi === 'debit')
                                                            Debit
                                                        @else
                                                            Kredit
                                                        @endif
                                                    </td>
                                                    <td class="text-end fs-12 fw-medium">
                                                        Rp{{ number_format($detail->nominal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($transaksi->akuntansi_jurnal_reversal)
                        <div class="card border border-danger-subtle shadow-none">
                            <div class="card-header bg-danger-subtle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-danger">
                                            <i class="ri-arrow-go-back-line me-1"></i>
                                            Jurnal Reversal oleh {{ $transaksi->akuntansi_jurnal_reversal->ms_pengguna->nama ?? '-' }}
                                        </h6>

                                        <small class="text-muted">
                                            {{ $transaksi->akuntansi_jurnal_reversal->deskripsi ?? '-' }}
                                        </small>
                                    </div>

                                    <span class="badge bg-danger text-white">
                                        {{ $transaksi->akuntansi_jurnal_reversal->nomor_jurnal }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Rekening</th>
                                                <th class="text-center">Posisi</th>
                                                <th class="text-end">Nominal</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($transaksi->akuntansi_jurnal_reversal->akuntansi_jurnal_detail as $detail)
                                                <tr class="fw-medium">
                                                    <td>
                                                        {{ $detail->akuntansi_rekening->nama_rekening }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if ($detail->posisi === 'debit')
                                                            Debit
                                                        @else
                                                            Kredit
                                                        @endif
                                                    </td>

                                                    <td class="text-end fs-12 fw-medium">
                                                        Rp{{ number_format($detail->nominal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
