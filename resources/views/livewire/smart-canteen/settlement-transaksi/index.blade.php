<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center justify-content-between">

            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Settlement Transaksi Kantin</h5>
                <p class="text-muted mb-0 fs-12">
                    Pilih kantin, periode transaksi, lalu proses settlement untuk transaksi yang belum diselesaikan.
                </p>
            </div>

            <!-- Tombol Riwayat Settlement -->
            <button type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasSettlement"
                aria-controls="offcanvasSettlement"
                class="btn btn-info d-inline-flex align-items-center gap-1">
                <i class="ri-history-line align-bottom"></i>
                <span>Riwayat Settlement</span>
            </button>

        </div>
    </div>

    <div class="card-body">
        
        <!-- Filter -->
        <div class="row g-3 align-items-end mb-3">
             <!-- Kolom Pencarian -->
            <div class="col-xxl-9 col-sm-6">
                <label class="form-label fw-semibold">Pencarian</label>
                <div class="position-relative">
                    <input type="text" class="form-control ps-4" wire:model.debounce.300ms="search" placeholder="Cari nama, deskripsi...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>

            <!-- Kantin -->
            <div class="col-xxl-3 col-sm-6">
                <label for="selectKantin" class="form-label">Pilih Kantin</label>
                <select id="selectKantin" wire:model="selectedKantin" class="form-select" style="cursor:pointer">
                    <option value="">Pilih Kantin</option>
                    @foreach($kantinUsers as $kantin)
                        <option value="{{ $kantin->ms_pengguna_id }}">
                            {{ $kantin->nama }} - {{ $kantin->peran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal -->
            {{-- <div class="col-xxl-4 col-sm-6">
                <label class="form-label fw-semibold">Periode Transaksi</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" id="startDate" class="form-control" wire:model.lazy="startDate">
                    <span class="text-muted">–</span>
                    <input type="date" id="endDate" class="form-control" wire:model.lazy="endDate">
                    <button type="button" class="btn text-info btn-icon" title="Reset"  wire:click="resetPeriode">
                        <i class="ri-refresh-line fs-16"></i>
                    </button>
                </div>
            </div> --}}
        </div>
        <!-- Data -->
        <div class="table-responsive">
            <table class="table table-hover nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Transaksi</th>
                        <th>Petugas Kantin</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($dataTransaksi as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}.</td>

                            <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y H:i:s') }}</td>

                            {{-- Pembeli --}}
                            <td>
                                @if($item->user_type === 'siswa')
                                    {{ $item->ms_siswa->nama_siswa }}
                                    <p class="text-muted fs-12 mb-0">
                                        {{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '' }}
                                    </p>
                                @else
                                    {{ $item->ms_pegawai->nama_pegawai }}
                                    <p class="text-muted fs-12 mb-0">
                                        {{ $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '' }}
                                    </p>
                                @endif
                            </td>

                            {{-- Transaksi + Metode --}}
                            <td>
                                {{ $item->deskripsi ?? '-' }} -
                                <i>{{ strtolower($item->metode_pembayaran) }}</i>
                            </td>

                            {{-- Petugas --}}
                            <td>{{ $item->ms_pengguna->nama ?? '-' }}</td>

                            {{-- Nominal --}}
                            <td class="text-end">
                                <span class="fw-medium fs-14 text-success">
                                    RP{{ number_format($item->total_transaksi, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada transaksi untuk disettle.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $dataTransaksi->links() }}
            </div>
            @if(!$selectedKantin)
                <div class="alert alert-warning mt-3">
                    Silakan pilih kantin terlebih dahulu untuk memproses settlement.
                </div>
            @endif
        </div>

        <hr>
        <!-- Panel Settlement -->
        <div class="row g-3 align-items-end">

            <!-- Total Settlement -->
            <div class="col-md-4 col-12">
                <p class="fw-semibold mb-1">Total Settlement</p>
                <h3 class="text-primary mb-0">
                    {{ 'Rp ' . number_format($totalSettlement, 0, ',', '.') }}
                </h3>
            </div>

            <!-- Metode Pembayaran -->
            <div class="col-md-4 col-12">
                <label class="form-label">Metode Pembayaran</label>
                <select class="form-select" wire:model="metodePembayaran">
                    <option value="">-- Pilih --</option>
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>

            <!-- Tombol Proses -->
            <div class="col-md-4 col-12">
                <button 
                    class="btn btn-success btn-lg w-100" 
                    data-bs-toggle="modal" 
                    data-bs-target="#ModalSettlement"
                    {{ $selectedKantin && $totalSettlement > 0 && $metodePembayaran ? '' : 'disabled' }}
                >
                    Proses Settlement
                </button>
            </div>

        </div>
    </div>
    <div class="modal fade" id="ModalSettlement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body p-5 text-center">
                    
                    <!-- Lord Icon -->
                    <lord-icon
                        src="https://cdn.lordicon.com/tqywkdcz.json"
                        trigger="loop"
                        colors="primary:#405189,secondary:#0ab39c"
                        style="width:90px;height:90px">
                    </lord-icon>

                    <div class="mt-4">
                        <h4 class="fs-semibold">
                            Proses Settlement Kantin?
                        </h4>

                        <p class="text-muted fs-14 mb-4">
                            Anda akan memproses settlement untuk 
                            <strong>{{ optional($kantinUsers->firstWhere('ms_pengguna_id', $selectedKantin))->nama ?? '-' }}</strong>?<br>
                            dengan metode <strong>{{ strtoupper($metodePembayaran) }}</strong>.
                        </p>

                        <h3 class="text-primary mb-4">
                            Rp{{ number_format($totalSettlement, 0, ',', '.') }}
                        </h3>

                        <div class="hstack gap-2 justify-content-center">
                            <button class="btn btn-light fw-medium shadow-none"
                                data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>

                            <button class="btn btn-success"
                                wire:click="prosesSettlement">
                                <i class="ri-check-line me-1 align-middle"></i>
                                Ya, Proses!
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>