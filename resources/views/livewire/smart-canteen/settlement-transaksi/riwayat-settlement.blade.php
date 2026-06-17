<div class>
    <div class="row g-3 align-items-end mb-3">
        <!-- Filter Periode -->
        <div class="col-xxl-12 col-sm-12">
            <label class="form-label fw-semibold">Periode</label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="startDate" class="form-control" wire:model="startDate">
                <span class="text-muted">–</span>
                <input type="date" id="endDate" class="form-control" wire:model="endDate">
                <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="live-preview">
        <!-- Tabel Riwayat Settlement -->
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Tanggal</th>
                        <th>Settlement</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($riwayat as $index => $item)
                        <tr class="text-center">
                            <td class="text-start">
                                {{ $riwayat->firstItem() + $index }}.
                            </td>
                            <td style="white-space: nowrap;" class="text-uppercase text-start">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_settlement, 'd F Y') }}
                            </td>

                            <td class="text-start">
                                <span class="fs-14 text-success fw-semibold">
                                    Rp{{ number_format($item->total_settlement, 0, ',', '.') }}
                                    – {{ strtoupper($item->metode_pembayaran) }}
                                </span>
                                <p class="text-muted mb-0">
                                    {{ $item->deskripsi ?? '-' }}
                                </p>
                            </td>
                            <td style="white-space: nowrap;">{{ $item->ms_pengguna->nama ?? '-' }}</td>
                            <td>
                                <button 
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalDetailSettlement"
                                    title="Detail Settlement"
                                    class="btn btn-sm btn-info"
                                    wire:click="$emit('openDetailSettlement', {{ $item->ms_settlement_kantin_id }})"
                                >
                                    <i class="mdi mdi-eye"></i> Detail
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Tidak ada data settlement untuk periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>
