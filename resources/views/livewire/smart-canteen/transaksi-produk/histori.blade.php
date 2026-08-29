<div wire:ignore.self style="min-width: 500px" class="offcanvas offcanvas-end bg-light"
    id="offcanvasHistori" tabindex="-1" data-bs-scroll="true" aria-labelledby="offcanvasHistoriLabel">

    {{-- Header --}}
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            {{-- Kiri --}}
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-exchange-funds-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1" id="offcanvasHistoriLabel">
                        Riwayat Transaksi
                    </h5>

                    <small class="text-muted">
                        {{ $namaKantin ?? 'Kantin' }}
                        @if ($selectedJenjang)
                            · {{ $namaJenjang ?? 'Jenjang' }}
                        @endif
                    </small>
                </div>
            </div>

            {{-- Kanan --}}
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>

        </div>
    </div>

    {{-- Body --}}
    <div class="offcanvas-body">
        {{-- Filter --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="card-body">
                {{-- FILTER --}}
                <div class="row g-3 align-items-end mb-3">
                    {{-- Metode Pembayaran --}}
                    <div class="col-xxl-4 col-md-6">
                        <label for="filterMetode"
                            class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Metode
                        </label>

                        <select id="filterMetode" class="form-select"
                            wire:model="selectedMetode"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                            title="Pilih Metode Transaksi">

                            <option value="">Semua</option>
                            <option value="Tunai">Tunai</option>
                            <option value="EduPay">EduPay</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>

                    {{-- Periode --}}
                    <div class="col-xxl-8">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Periode
                        </label>

                        <div class="d-flex align-items-center gap-2">

                            <input type="date"
                                id="startDate"
                                class="form-control"
                                wire:model="startDate">

                            <span class="text-muted">–</span>

                            <input type="date"
                                id="endDate"
                                class="form-control"
                                wire:model="endDate">

                            <button type="button"
                                class="btn btn-soft-secondary"
                                wire:click="resetTanggal"
                                title="Reset Tanggal">

                                <i class="ri-refresh-line"></i>

                            </button>

                        </div>
                    </div>

                </div>
                <div class="live-preview">
                    @if ($riwayat->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Pembayaran</th>
                                    <th class="text-end pe-3">Total</th>
                                    <th class="text-center" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($riwayat as $i => $d)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ $riwayat->firstItem() + $i }}
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

                                    {{-- Produk --}}
                                    <td>
                                        {{ $d->metode_pembayaran ?? '-' }}
                                    </td>

                                    {{-- Total --}}
                                    <td class="text-end pe-3">
                                        {{-- Sudah Settlement --}}
                                        @if ($d->status_settlement === 'sudah')
                                            <i class="ri-check-fill text-primary me-1"
                                                title="Sudah Settlement"
                                                aria-label="Sudah Settlement"
                                            ></i>

                                            {{-- Transaksi Kas Kantin --}}
                                        @elseif ($d->status_settlement === null)
                                            <i class="ri-check-double-fill text-primary me-1"
                                                title="Kas Kantin"
                                                aria-label="Kas Kantin"
                                            ></i>

                                            {{-- Belum Settlement --}}
                                        @elseif ($d->status_settlement === 'belum')
                                            <i class="ri-time-fill text-danger me-1"
                                                title="Menunggu Settlement"
                                                aria-label="Menunggu Settlement"
                                            ></i>
                                        @endif

                                        <span class="fw-medium fs-12">
                                            Rp{{ number_format($d->total_transaksi, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    {{-- Aksi --}}
                                    <td class="text-center">
                                        @if ( $d->status_transaksi !== 'dibatalkan' && \Carbon\Carbon::parse($d->tanggal_transaksi)->isToday() && in_array($d->status_settlement, ['belum', null], true))
                                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3"
                                                wire:click.prevent="$emit('loadKoreksiTransaksi', {{ $d->ms_transaksi_kantin_id }})" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKoreksi"
                                                title="Koreksi Transaksi" aria-label="Koreksi Transaksi">
                                                <i class="ri-mark-pen-line me-1"></i>Koreksi
                                            </button>

                                        @else
                                            <span
                                                class="text-muted"
                                                title="Transaksi tidak dapat dikoreksi">
                                                <i class="ri-lock-line"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted fs-13">
                                    Menampilkan
                                    <span class="fw-semibold">
                                        {{ $riwayat->firstItem() ?? 0 }}
                                    </span>
                                    -
                                    <span class="fw-semibold">
                                        {{ $riwayat->lastItem() ?? 0 }}
                                    </span>
                                    dari
                                    <span class="fw-semibold">
                                        {{ $riwayat->total() }}
                                    </span>
                                    transaksi
                                </div>
                                <div>
                                    {{ $riwayat->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-5 px-3">
                            <lord-icon
                                src="https://cdn.lordicon.com/msoeawqm.json"
                                trigger="loop"
                                colors="primary:#405189,secondary:#08a88a"
                                style="width:75px;height:75px">
                            </lord-icon>

                            <h5 class="mt-3 mb-1">
                                Belum Ada Transaksi
                            </h5>

                            <p class="text-muted mb-0">
                                Belum terdapat riwayat transaksi pada periode yang dipilih.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>