<div wire:ignore.self style="min-width: 700px" class="offcanvas offcanvas-end bg-light"
    id="offcanvasSettlement" tabindex="-1" data-bs-scroll="true" aria-labelledby="offcanvasSettlementLabel">

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
                    <h5 class="fw-bold mb-1" id="offcanvasSettlementLabel">
                        Riwayat Settlement
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

                        <select id="filterMetode"
                            wire:model="selectedMetode"
                            class="form-select"
                            data-bs-toggle="tooltip"
                            data-bs-trigger="hover"
                            data-bs-placement="top"
                            title="Pilih Metode Settlement">

                            <option value="">Semua</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>

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

                {{-- DATA --}}
                <div class="live-preview">
                    @if ($riwayat->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 50px;">No</th>
                                        <th>Tanggal</th>
                                        <th>Sumber Dana</th>
                                        <th>Metode</th>
                                        <th>Petugas</th>
                                        <th>Settlement</th>
                                        <th class="text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($riwayat as $index => $settlement)
                                        <tr>
                                            {{-- No --}}
                                            <td class="ps-4">
                                                {{ $riwayat->firstItem() + $index }}.
                                            </td>

                                            {{-- Tanggal --}}
                                            <td class="text-start">
                                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                                    $settlement->tanggal_settlement ?? null,
                                                    'd F Y'
                                                ) }}
                                            </td>

                                            <td>
                                                {{ ucfirst($settlement->ms_jenjang->nama_jenjang) }}
                                            </td>

                                            <td>
                                                {{ ucfirst($settlement->metode_pembayaran) }}
                                            </td>

                                            {{-- Petugas --}}
                                            <td>
                                                <span class="fw-medium">
                                                    {{ $settlement->ms_pengguna->nama ?? '-' }}
                                                </span>
                                            </td>

                                            {{-- Nominal --}}
                                            <td>
                                                <div class="fw-semibold fs-12">
                                                    Rp{{ number_format($settlement->total_settlement, 0, ',', '.') }}
                                                </div>

                                            </td>

                                            {{-- Aksi --}}
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm rounded-pill px-3"
                                                    wire:click="$emit('openDetailSettlement', {{ $settlement->ms_settlement_kantin_id }})"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalDetailSettlement"
                                                    title="Detail Settlement">

                                                    <i class="ri-eye-line"></i> Detail 
                                                </button>
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
                                Belum Ada Settlement
                            </h5>

                            <p class="text-muted mb-0">
                                Belum terdapat riwayat settlement pada periode yang dipilih.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>