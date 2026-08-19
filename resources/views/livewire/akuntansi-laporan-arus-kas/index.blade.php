<div class="row">
    <div class="col-xxl-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header">
                <div class="d-flex align-items-center flex-wrap gap-3">

                    {{-- JUDUL --}}
                    <div class="flex-grow-1">

                        <div class="d-flex align-items-center gap-3">

                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                                    <i class="ri-exchange-funds-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Laporan Arus Kas - {{ $namaRekening }}
                                </h5>

                                <small class="text-muted">
                                    Ringkasan arus kas masuk dan keluar.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL --}}
                    @if ($selectedJenjang)
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#ExportLaporan"
                                class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1">

                                <i class="ri-file-excel-2-line"></i>
                                <span>Export</span>

                            </button>

                            <button type="button"
                                wire:click="cetakLaporan"
                                class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1">

                                <i class="ri-printer-line"></i>
                                <span>Cetak</span>

                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end mb-3">
                    <!-- Dropdown Kas/Bank -->
                    <div class="col-xxl-2 col-md-6">
                        <label for="selectRekening" class="form-label small text-muted text-uppercase fw-medium mb-2">Kas/Bank</label>
                        <select id="selectRekening" wire:model="selectedRekening" class="form-select"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" 
                                data-bs-placement="top" title="Pilih Jenis Transaksi">
                            <option value="">Semua</option>
                            <option value="11001">Kas Besar</option>
                            <option value="11002">Bank Sekolah</option>
                        </select>
                    </div>

                    <!-- Input Pencarian -->
                    <div class="col-xxl-5 col-md-5">
                        <label for="searchInput" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                        <div class="search-box">
                            <input type="text" id="search" class="form-control search" wire:model.debounce.300ms="search"
                                placeholder="Cari nama, deskripsi...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <!-- Filter Periode -->
                    <div class="col-xxl-5">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">Periode</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" id="startDate" class="form-control" wire:model="startDate" value="{{ $startDate }}">
                            <span class="text-muted">–</span>
                            <input type="date" id="endDate" class="form-control" wire:model="endDate" value="{{ $endDate }}">
                            <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- DATA --}}
                <div class="live-preview">
                    <div class="table-responsive">
                        <table id="DataArusKas" class="table table-hover table-nowrap align-middle">

                            {{-- HEADER --}}
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase text-center" style="width: 60px;">No</th>
                                    <th class="text-uppercase text-start">Tanggal</th>
                                    <th class="text-uppercase text-start">Akun</th>
                                    <th class="text-uppercase text-start">Petugas</th>
                                    <th class="text-uppercase text-start">Deskripsi Transaksi</th>
                                    <th class="text-uppercase text-center">Kas Masuk</th>
                                    <th class="text-uppercase text-center">Kas Keluar</th>
                                </tr>
                            </thead>

                            {{-- BODY --}}
                            <tbody>
                                @forelse ($transaksiJurnal as $key => $trx)
                                    <tr>

                                        {{-- NO --}}
                                        <td class="text-center">
                                            {{ $transaksiJurnal->firstItem() + $key }}.
                                        </td>

                                        {{-- TANGGAL --}}
                                        <td class="text-start">
                                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                                $trx->akuntansi_jurnal->tanggal_transaksi ?? null,
                                                'd F Y'
                                            ) }}
                                        </td>

                                        {{-- AKUN --}}
                                        <td class="text-start">
                                            {{ $trx->akuntansi_rekening->kode_rekening ?? '-' }}
                                            -
                                            {{ $trx->akuntansi_rekening->nama_rekening ?? '-' }}
                                        </td>

                                        {{-- PETUGAS --}}
                                        <td class="text-start">
                                            {{ $trx->akuntansi_jurnal->ms_pengguna->nama ?? '-' }}
                                        </td>

                                        {{-- DESKRIPSI --}}
                                        <td class="text-start">
                                            {{ $trx->akuntansi_jurnal->deskripsi ?? '-' }}
                                        </td>

                                        {{-- KAS MASUK --}}
                                        <td class="text-center">
                                            @if ($trx->posisi === 'debit')
                                                <span class="fs-12 text-success">
                                                    Rp{{ number_format($trx->nominal, 0, ',', '.') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- KAS KELUAR --}}
                                        <td class="text-center">
                                            @if ($trx->posisi === 'kredit')
                                                <span class="fs-12 text-danger">
                                                    Rp{{ number_format($trx->nominal, 0, ',', '.') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="ri-inbox-line fs-4 d-block mb-2"></i>
                                            Tidak ada transaksi pada periode yang dipilih.
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>

                            {{-- FOOTER --}}
                            <tfoot>

                                {{-- TOTAL --}}
                                <tr class="table-light fw-bold">

                                    <td colspan="5" class="text-end">
                                        TOTAL
                                    </td>

                                    <td class="text-center">
                                        <span class="fs-12 text-success">
                                            Rp{{ number_format($totalKasMasuk, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="fs-12 text-danger">
                                            Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}
                                        </span>
                                    </td>

                                </tr>

                            </tfoot>

                        </table>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted fs-13">
                                    Menampilkan
                                    <span class="fw-semibold">
                                        {{ $transaksiJurnal->firstItem() ?? 0 }}
                                    </span>
                                    -
                                    <span class="fw-semibold">
                                        {{ $transaksiJurnal->lastItem() ?? 0 }}
                                    </span>
                                    dari
                                    <span class="fw-semibold">
                                        {{ $transaksiJurnal->total() }}
                                    </span>
                                    transaksi
                                </div>
                                <div>
                                    {{ $transaksiJurnal->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="col-xl-5 col-lg-6 col-md-8">
                            <div class="border rounded-4 overflow-hidden">

                                {{-- SALDO AWAL --}}
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                    <span class="fw-medium">
                                        Saldo Awal {{ $namaRekening }}
                                    </span>
                                    <span class="fw-semibold fs-12">
                                        Rp{{ number_format($saldoAwal, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- KAS MASUK --}}
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                    <span class="fw-medium">
                                        Total Kas Masuk
                                    </span>
                                    <span class="fw-semibold text-success fs-12">
                                        + Rp{{ number_format($totalKasMasuk, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- KAS KELUAR --}}
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                    <span class="fw-medium">
                                        Total Kas Keluar
                                    </span>
                                    <span class="fw-semibold text-danger fs-12">
                                        - Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}
                                    </span>
                                </div>

                                {{-- SALDO AKHIR --}}
                                <div class="d-flex justify-content-between align-items-center px-3 py-3 bg-dark text-white">
                                    <span class="fw-bold">
                                        Saldo Akhir {{ $namaRekening }}
                                    </span>
                                    <span class="fw-bold fs-12">
                                        Rp{{ number_format($saldoAkhir, 0, ',', '.') }}
                                    </span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1"
            aria-labelledby="exportArusKasLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-4 pb-5 pt-2 text-center">
                        {{-- ICON --}}
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/fjvfsqea.json"
                                        trigger="loop"
                                        colors="primary:#405189,secondary:#0ab39c"
                                        style="width:70px;height:70px">
                                    </lord-icon>
                                </div>
                            </div>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-2">
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                                Export Excel
                            </span>

                            <h3 class="fw-bold mb-2" id="exportArusKasLabel">
                                Export Laporan Arus Kas?
                            </h3>

                            <p class="text-muted mb-0 lh-lg px-lg-4">
                                Apakah Anda yakin ingin mengekspor
                                <strong class="text-dark">
                                    Laporan Arus Kas
                                </strong>
                                untuk rekening
                                <strong class="text-dark">
                                    {{ $namaRekening ?? '-' }}
                                </strong>
                                ke Excel?
                            </p>
                        </div>

                        {{-- INFORMATION REKENING --}}
                        <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                            <div class="d-flex align-items-start gap-3">
                                {{-- ICON --}}
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-exchange-funds-line fs-18"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- INFORMATION --}}
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">
                                        {{ $namaRekening ?? 'Rekening belum dipilih' }}
                                    </h6>

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary">
                                            Arus Kas
                                        </span>

                                        @if(!empty($namaRekening))
                                            <span class="text-muted fs-13">
                                                Ringkasan transaksi kas masuk dan kas keluar
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-muted mb-0 fs-13">
                                        Data yang diekspor akan mengikuti data
                                        Laporan Arus Kas yang sedang ditampilkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>
                            Batal
                        </button>

                        <button type="button" class="btn btn-success rounded-pill px-4"
                            id="konfirmasiExportLaporan" data-nama-rekening="{{ $namaRekening ?? '' }}" data-bs-dismiss="modal">
                            <i class="ri-file-excel-2-line me-1"></i>
                            Ya, Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportLaporan')
            .addEventListener('click', function () {

                alertify.success("Menyiapkan Dokumen Excel");

                const namaRekening = this.dataset.namaRekening || 'Semua-Rekening';

                setTimeout(function () {

                    // Ambil tabel khusus Laporan Arus Kas
                    const table = document.getElementById('DataArusKas');

                    if (!table) {
                        alertify.error("Tabel Laporan Arus Kas tidak ditemukan.");
                        return;
                    }

                    // Konversi tabel ke Excel
                    const workbook = XLSX.utils.table_to_book(table, {
                        sheet: 'Arus Kas'
                    });

                    // Bersihkan nama rekening agar aman digunakan sebagai nama file
                    const namaFile = namaRekening
                        .replace(/[\\/:*?"<>|]/g, '')
                        .replace(/\s+/g, '-')
                        .trim();

                    const tanggal = '{{ date('Y-m-d') }}';

                    const namaFileExcel =
                        `Laporan-Arus-Kas-${namaFile}-${tanggal}.xlsx`;

                    XLSX.writeFile(workbook, namaFileExcel);

                }, 500);
            });
    </script>
</div>