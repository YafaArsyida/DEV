<div>
    <!-- Filter Periode -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Periode</label>
        <div class="d-flex align-items-center gap-2">
            <input type="date" id="startDate" class="form-control" wire:model="startDate" value="{{ $startDate }}">
            <span class="text-muted">–</span>
            <input type="date" id="endDate" class="form-control" wire:model="endDate" value="{{ $endDate }}">
            <div class="col-auto">
                <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal" title="Reset Tanggal">
                    <i class="ri-refresh-line fs-16"></i>
                </button>    
            </div>
        </div>
    </div>

    @if(auth()->user()->peran !== 'kantin')
    <div class="mb-3">
        <label class="form-label">Petugas Kantin</label>
        <select class="form-select" wire:model="selectedPetugas">
            <option value="">Semua Kantin</option>
            @foreach ($listPetugas as $p)
                <option value="{{ $p->ms_pengguna_id }}">{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    @endif


    <!-- Tabel Riwayat Settlement -->
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Settlement</th>
                    <th>Petugas TU</th>
                    <th>Detail</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($riwayat as $index => $item)
                    <tr class="text-center">
                        <td class="text-start">{{ $index + 1 }}.</td>

                        <td class="text-uppercase text-start">
                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y') }}
                        </td>

                        <td class="text-start">
                            <span class="fs-14 fw-semibold">
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
                        <td colspan="4" class="text-center text-muted py-4">
                            Tidak ada data settlement untuk periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>


</div>
