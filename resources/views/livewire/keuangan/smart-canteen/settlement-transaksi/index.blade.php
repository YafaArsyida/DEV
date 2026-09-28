<div class="row">
    <div class="col-xxl-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    {{-- HEADER --}}
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-exchange-funds-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Settlement Transaksi SmartCanteen
                                </h5>

                                <small class="text-muted">
                                    Proses settlement untuk transaksi SmartCanteen yang belum diselesaikan.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasSettlement"
                            aria-controls="offcanvasSettlement"
                            class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1">

                            <i class="ri-history-line"></i>
                            <span>Riwayat Settlement</span>
                        </button>
                    </div>

                </div>
            </div>

            <div class="card-body">

                <div class="row g-3 align-items-end">

                    {{-- TOTAL SETTLEMENT --}}
                    <div class="col-xxl-4 col-md-6 col-12">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Settlement Tersedia
                        </label>

                        <div class="border rounded-3 px-3 py-2 bg-light-subtle">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="text-primary fw-semibold mb-0">
                                        Rp{{ number_format($totalSettlement, 0, ',', '.') }}
                                    </h2>
                                </div>

                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-wallet-3-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- JENJANG --}}
                    <div class="col-xxl-2 col-md-6 col-12">

                        <label for="selectedJenjang"
                            class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Sumber Dana / Jenjang
                        </label>

                        <select id="selectedJenjang" class="form-select" wire:model="selectedJenjang">
                            <option value="">-- Semua --</option>
                            @foreach ($select_jenjang as $item)
                                <option value="{{ $item->ms_jenjang_id }}">
                                    {{ $item->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    {{-- METODE PEMBAYARAN --}}
                    <div class="col-xxl-4 col-md-6 col-12">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Metode Pembayaran
                        </label>

                        <select class="form-select" wire:model="metodePembayaran">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    {{-- TOMBOL PROSES --}}
                    <div class="col-xxl-2 col-12">
                        <button type="button"
                            class="btn btn-success rounded-pill px-4 w-100 d-inline-flex align-items-center justify-content-center gap-2"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalSettlement"
                            {{ $selectedJenjang && $selectedKantin && $totalSettlement > 0 && $metodePembayaran ? '' : 'disabled' }}>

                            <i class="ri-check-double-line"></i>
                            <span>Proses Settlement</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-xxl-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-list-check-2"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Riwayat Transaksi
                                </h5>

                                <small class="text-muted">
                                    Daftar transaksi SmartCanteen berdasarkan periode yang dipilih.
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">

                {{-- FILTER --}}
                <div class="row g-3 align-items-end mb-3">

                    <div class="col-xxl-2 col-lg-3 col-sm-6">
                        <label for="selectedJenjang"
                            class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Sumber Dana / Jenjang
                        </label>

                        <select id="selectedJenjang" class="form-select" wire:model="selectedJenjang">
                            <option value="">Semua Jenjang</option>
                            @foreach ($select_jenjang as $item)
                                <option value="{{ $item->ms_jenjang_id }}">
                                    {{ $item->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- PENCARIAN --}}
                    <div class="col-xxl-6 col-sm-6">
                        <label for="searchTransaksi" class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Pencarian
                        </label>

                        <div class="search-box">
                            <input type="text" id="searchTransaksi" class="form-control search" wire:model.debounce.300ms="search" placeholder="Cari nama pembeli, transaksi...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    {{-- PERIODE --}}
                    <div class="col-xxl-4 col-sm-6">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                            Periode Transaksi
                        </label>

                        <div class="d-flex align-items-center gap-2">
                            <input type="date" id="startDate" class="form-control" wire:model.lazy="startDate">
                            <span class="text-muted flex-shrink-0">
                                –
                            </span>
                            <input type="date" id="endDate" class="form-control" wire:model.lazy="endDate">
                            <button type="button" class="btn btn-soft-secondary flex-shrink-0" wire:click="resetPeriode" title="Reset Periode">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- DATA --}}
                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-nowrap align-middle">
                            <thead class="table-light">
                                <tr class="text-uppercase">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Sumber Dana</th>
                                    <th class="text-center">Settlement</th>
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
        
                                <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y') }}</td>
        
                                <td>
                                    {{ $item->ms_jenjang->nama_jenjang ?? '-' }}
                                </td>

                                <td class="text-center">
                                    @if($item->status_settlement === 'sudah')
                                        <span class="text-success fw-semibold">
                                            <i class="ri-check-line me-1"></i> Sudah
                                        </span>
                                    @elseif($item->status_settlement === 'belum')
                                        <span class="text-warning fw-semibold">
                                            <i class="ri-timer-line me-1"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="text-primary fw-semibold">
                                            <i class="ri-check-double-line me-1"></i> Langsung
                                        </span>
                                    @endif
                                </td>
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
                                    <span class="fw-medium fs-12">
                                        Rp{{ number_format($item->total_transaksi, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="noresult text-center py-3">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#08a88a"
                                            style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted fs-13">
                                Menampilkan
                                <span class="fw-semibold">
                                    {{ $dataTransaksi->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $dataTransaksi->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $dataTransaksi->total() }}
                                </span>
                                data
                            </div>
                            <div>
                                {{ $dataTransaksi->links() }}
                            </div>
                        </div>
                    </div>
                    @if(!$selectedKantin)
                    <div class="alert alert-warning mt-3">
                        Silakan pilih kantin terlebih dahulu untuk memproses settlement.
                    </div>
                    @endif
                </div>
        
        
            </div>
            <div wire:ignore.self class="modal fade zoomIn"
                id="ModalSettlement" tabindex="-1"
                aria-labelledby="settlementRecordLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        {{-- HEADER --}}
                        <div class="modal-header border-0 pb-0">
                            <button type="button"
                                class="btn btn-light btn-icon rounded-circle ms-auto"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                <i class="ri-close-line fs-18"></i>
                            </button>
                        </div>

                        {{-- BODY --}}
                        <div class="modal-body px-4 pb-4 pt-2 text-center">

                            {{-- ICON --}}
                            <div class="mb-4">
                                <div class="avatar-xl mx-auto">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">

                                        <lord-icon
                                            src="https://cdn.lordicon.com/tqywkdcz.json"
                                            trigger="loop"
                                            colors="primary:#0ab39c,secondary:#405189"
                                            style="width:70px;height:70px">
                                        </lord-icon>

                                    </div>
                                </div>
                            </div>

                            {{-- TITLE --}}
                            <div class="mb-3">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                                    Konfirmasi Settlement
                                </span>

                                <h3 class="fw-bold mb-2" id="settlementRecordLabel">
                                    Proses Settlement?
                                </h3>

                                <p class="text-muted mb-0 lh-lg px-lg-3">
                                    Pastikan informasi settlement sudah benar
                                    sebelum melanjutkan proses.
                                </p>
                            </div>

                            {{-- INFORMATION --}}
                            <div class="card border rounded-4 bg-light-subtle text-start mt-4 mb-3">
                                <div class="card-body">
                                    {{-- Kantin --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                    <i class="ri-store-2-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <small class="text-muted d-block">
                                                    Kantin
                                                </small>

                                                <span class="fw-semibold">
                                                    {{ $namaKantin ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Jenjang --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                    <i class="ri-graduation-cap-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <small class="text-muted d-block">
                                                    Sumber Dana
                                                </small>

                                                <span class="fw-semibold">
                                                    {{ $namaJenjang ?: '-' }}
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                    {{-- Metode Pembayaran --}}
                                    <div class="d-flex align-items-center justify-content-between">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                                    <i class="ri-bank-card-line"></i>
                                                </div>
                                            </div>

                                            <div>
                                                <small class="text-muted d-block">
                                                    Metode Pembayaran
                                                </small>

                                                <span class="fw-semibold">
                                                    {{ strtoupper($metodePembayaran ?? '-') }}
                                                </span>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- TOTAL --}}
                            <div class="bg-success-subtle rounded-4 p-3 mb-3">
                                <small class="text-muted d-block mb-1">
                                    Total Settlement
                                </small>

                                <h2 class="fw-bold text-success mb-0">
                                    Rp{{ number_format($totalSettlement ?? 0, 0, ',', '.') }}
                                </h2>

                            </div>

                            {{-- WARNING --}}
                            <div class="alert alert-warning border rounded-4 text-start mb-0">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <i class="ri-information-line text-warning fs-20"></i>
                                    </div>

                                    <div>
                                        <h6 class="fw-semibold mb-1">
                                            Perhatian
                                        </h6>

                                        <p class="text-muted mb-0 fs-13">
                                            Setelah settlement diproses, transaksi akan
                                            dicatat sebagai settlement dan tidak dapat
                                            diproses kembali.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                            <button type="button"
                                class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">

                                <i class="ri-close-line me-1"></i>
                                Batal
                            </button>

                            <button type="button"
                                class="btn btn-success rounded-pill px-4"
                                wire:click="prosesSettlement">
                                <i class="ri-check-double-line me-1"></i>
                                Ya, Proses Settlement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
