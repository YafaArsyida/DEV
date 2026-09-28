<div wire:ignore.self class="modal fade"
    id="ModalDetailSettlement" tabindex="-1"
    aria-labelledby="ModalDetailSettlementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- HEADER --}}
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-chart-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalDetailSettlementLabel">
                            Detail Settlement
                        </h5>

                        <small class="text-muted">
                            Rincian transaksi settlement kantin
                        </small>
                    </div>
                </div>

                <button type="button" class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
            {{-- BODY --}}
            <div class="modal-body">
                @if ($settlement)
                    {{-- INFORMASI SETTLEMENT --}}
                    <div class="card border-0 bg-light rounded-4 mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Tanggal --}}
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">
                                        Tanggal Settlement
                                    </div>

                                    <div class="fw-semibold">
                                        {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                            $settlement->tanggal_settlement,
                                            'd F Y'
                                        ) }}
                                    </div>
                                </div>

                                {{-- Kantin --}}
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">
                                        Kantin
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $settlement->ms_kantin->nama_kantin ?? '-' }}
                                    </div>
                                </div>

                                {{-- Jenjang --}}
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">
                                        Sumber Dana
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $settlement->ms_jenjang->nama_jenjang ?? '-' }}
                                    </div>
                                </div>

                                {{-- Petugas --}}
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">
                                        Petugas
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $settlement->ms_pengguna->nama ?? '-' }}
                                    </div>
                                </div>

                                {{-- Total --}}
                                <div class="col-md-6">

                                    <div class="text-muted small mb-1">
                                        Total Settlement
                                    </div>

                                    <div class="fw-bold fs-18">
                                        Rp{{ number_format($settlement->total_settlement, 0, ',', '.') }}
                                    </div>

                                </div>

                                {{-- Metode --}}
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">
                                        Metode Pembayaran
                                    </div>

                                    @if (strtolower($settlement->metode_pembayaran) === 'tunai')
                                        <span class="badge bg-primary-subtle text-primary rounded-pill">
                                            <i class="ri-money-dollar-circle-line me-1"></i>
                                            Tunai
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary rounded-pill">
                                            <i class="ri-bank-card-line me-1"></i>
                                            {{ ucfirst($settlement->metode_pembayaran) }}
                                        </span>
                                    @endif

                                </div>

                                {{-- Deskripsi --}}
                                @if ($settlement->deskripsi)

                                    <div class="col-12">

                                        <div class="text-muted small mb-1">
                                            Deskripsi
                                        </div>

                                        <div class="text-muted">
                                            {{ $settlement->deskripsi }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th class="text-end pe-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detailList as $i => $d)
                                    <tr>
                                        {{-- No --}}
                                        <td class="ps-3 text-muted">
                                            {{ $i + 1 }}
                                        </td>

                                        {{-- Tanggal --}}
                                        <td style="white-space: nowrap;">
                                            <div class="fw-medium">
                                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                                    $d->tanggal_transaksi,
                                                    'd F Y'
                                                ) }}
                                            </div>

                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($d->tanggal_transaksi)->format('H:i') }}
                                            </small>
                                        </td>

                                        {{-- Pelanggan --}}
                                        <td>
                                            <div class="fw-semibold text-dark">
                                                @if ($d->user_type === 'siswa')

                                                    <span class="badge bg-primary-subtle text-primary rounded-pill me-1">
                                                        Siswa
                                                    </span>

                                                    {{ $d->ms_siswa->nama_siswa ?? '-' }}

                                                @elseif ($d->user_type === 'pegawai')
                                                    <span class="badge bg-info-subtle text-info rounded-pill me-1">
                                                        Pegawai
                                                    </span>

                                                    {{ $d->ms_pegawai->nama_pegawai ?? '-' }}
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                                        {{ ucfirst($d->user_type ?? 'Umum') }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if ($d->dt_transaksi_kantin->count())
                                                <small class="text-muted d-block mt-1">
                                                    {{ $d->dt_transaksi_kantin
                                                        ->map(function ($detail) {
                                                            return ($detail->ms_produk_kantin->nama_produk_kantin ?? 'Produk')
                                                                . ' x'
                                                                . $detail->jumlah_produk;
                                                        })
                                                        ->join(', ') }}
                                                </small>

                                            @endif
                                        </td>

                                        {{-- Total --}}
                                        <td class="text-end pe-3">
                                            <span class="fw-medium fs-12">
                                                Rp{{ number_format($d->total_transaksi, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty

                                    <tr>

                                        <td colspan="4">

                                            <div class="text-center py-4">

                                                <lord-icon
                                                    src="https://cdn.lordicon.com/msoeawqm.json"
                                                    trigger="loop"
                                                    colors="primary:#405189,secondary:#08a88a"
                                                    style="width:65px;height:65px">
                                                </lord-icon>

                                                <h6 class="mt-2 fw-semibold">
                                                    Tidak Ada Transaksi
                                                </h6>

                                                <p class="text-muted small mb-0">
                                                    Tidak terdapat transaksi dalam settlement ini.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">

                <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">

                    <i class="ri-close-line me-1"></i>
                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>