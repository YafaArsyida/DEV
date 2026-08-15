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
                                    Laporan Arus Kas
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
                        <table class="table table-hover align-middle">

                            {{-- HEADER --}}
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase">No</th>
                                    <th class="text-uppercase text-start" style="width: 120px;">
                                        Tanggal
                                    </th>
                                    <th class="text-uppercase text-start" style="width: 120px;">
                                        Akun
                                    </th>
                                    <th class="text-uppercase text-start">
                                        Petugas
                                    </th>
                                    <th class="text-uppercase text-start" style="min-width: 500px;">
                                        Deskripsi Transaksi
                                    </th>
                                    <th class="text-uppercase text-center">
                                        Pemasukan
                                    </th>
                                    <th class="text-uppercase text-center">
                                        Pengeluaran
                                    </th>
                                </tr>
                            </thead>

                            {{-- BODY --}}
                            <tbody>

                                {{-- SALDO AWAL --}}
                                <tr class="table-light fw-semibold">
                                    <td colspan="5" class="text-end">
                                        Saldo Awal
                                    </td>
                                    <td colspan="2" class="text-center">
                                        Rp{{ number_format($saldoAwal, 0, ',', '.') }}
                                    </td>
                                </tr>

                                @forelse ($transaksiJurnal as $trx)

                                    <tr>
                                        {{-- NO --}}
                                        <td class="text-start">
                                            {{ $loop->iteration }}.
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

                                        {{-- PEMASUKAN --}}
                                        <td class="text-center">
                                            @if ($trx->posisi === 'debit')
                                                <span class="fs-14 text-success">
                                                    Rp{{ number_format($trx->nominal, 0, ',', '.') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- PENGELUARAN --}}
                                        <td class="text-center">
                                            @if ($trx->posisi === 'kredit')
                                                <span class="fs-14 text-danger">
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
                                            Tidak ada transaksi pada periode yang dipilih.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                            {{-- FOOTER --}}
                            <tfoot>

                                {{-- TOTAL --}}
                                <tr class="table-light">
                                    <td colspan="5" class="text-end fw-bold">
                                        TOTAL
                                    </td>

                                    <td class="text-center">
                                        <span class="fs-14 text-success">
                                            Rp{{ number_format($totalKasMasuk, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="fs-14 text-danger">
                                            Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>

                                {{-- SALDO AKHIR --}}
                                <tr class="fw-bold">
                                    <td colspan="5" class="text-end">
                                        SALDO AKHIR
                                    </td>

                                    <td colspan="2" class="text-center">
                                        <span class="fs-14">
                                            Rp{{ number_format($saldoAkhir, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>

                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                        <div class="mt-4 text-center">
                            <h4 class="fs-semibold">Konfirmasi Export</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">
                                Apakah Anda yakin ingin mengekspor Laporan Arus Kas? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                            </p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Batal
                                </button>
                                <button class="btn btn-primary" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya, Export!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-xxl-4">
        <!-- Info: Apa itu Laporan Arus Kas -->
        <div class="card shadow-none mb-3">
            <div class="card-body bg-info-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-wallet text-info fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Apa itu Laporan Arus Kas?</h6>
                        <p class="text-muted mb-0">
                            Laporan Arus Kas adalah laporan keuangan yang menunjukkan pergerakan uang masuk (pemasukan) dan uang keluar (pengeluaran) sekolah dalam periode tertentu.
                            Laporan ini membantu memantau posisi kas dan bank secara <em>real-time</em> sehingga memudahkan pengelolaan keuangan harian.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fungsi Laporan Arus Kas -->
        <div class="card shadow-none mb-3">
            <div class="card-body bg-success-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-list-check text-success fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Fungsi Laporan Arus Kas</h6>
                        <ul class="mb-0 text-muted">
                            <li>Memantau saldo kas dan bank secara akurat berdasarkan periode dan akun terpilih</li>
                            <li>Menganalisis sumber pemasukan dan pengeluaran sekolah</li>
                            <li>Membantu perencanaan keuangan jangka pendek maupun panjang</li>
                            <li>Memastikan ketersediaan dana untuk kebutuhan operasional</li>
                            <li>Mendukung transparansi dan akuntabilitas keuangan lembaga</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan Penting -->
        <div class="card shadow-none">
            <div class="card-body bg-warning-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-error-circle text-warning fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Catatan Penting</h6>
                        <p class="text-muted mb-0">
                            Fitur pencarian hanya memfilter transaksi berdasarkan <strong>deskripsi</strong>. 
                            Saat pencarian digunakan, perhitungan <em>Saldo Awal</em> dan <em>Saldo Akhir</em> akan mengikuti hasil filter, 
                            sehingga nilainya tidak mencerminkan saldo sebenarnya. 
                            Untuk melihat saldo final yang akurat, gunakan filter <strong>Akun</strong> dan <strong>Periode</strong> tanpa pencarian.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <script>
        document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.querySelector("table");
                
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Arus-Kas.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>