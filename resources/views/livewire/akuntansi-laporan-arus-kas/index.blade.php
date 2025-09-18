<div class="row">
    <div class="col-xxl-8">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Laporan Arus Kas</h5>
                    @if ($selectedJenjang && $selectedTahunAjar)
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2 flex-wrap">
                            <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                                <i class="ri-printer-line align-bottom"></i>
                                <span>Cetak Laporan</span>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end mb-3">
                    <!-- Dropdown Kas/Bank -->
                    <div class="col-xxl-2 col-md-6">
                        <label for="selectRekening" class="form-label">Kas/Bank</label>
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
                        <label for="searchInput" class="form-label">Pencarian</label>
                        <div class="position-relative">
                            <input type="text" id="searchInput" 
                                class="form-control ps-4" 
                                wire:model.debounce.300ms="search" 
                                placeholder="Cari nama, deskripsi, atau lainnya...">
                            <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                        </div>
                    </div>

                    <!-- Filter Periode -->
                    <div class="col-xxl-5">
                        <label class="form-label">Periode</label>
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <input type="date" id="startDate" class="form-control" wire:model="startDate" placeholder="Mulai">
                            </div>
                            <div class="col-md-5">
                                <input type="date" id="endDate" class="form-control" wire:model="endDate" placeholder="Sampai">
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" 
                                        wire:click="resetTanggal" data-bs-toggle="tooltip" 
                                        data-bs-placement="top" title="Reset Tanggal">
                                    <i class="ri-refresh-line fs-16"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DATA --}}
                <div class="live-preview">
                    <div class="table-responsive">
                    {{-- <div class="table-responsive" style="max-height: 1000px;" data-simplebar> --}}
                        @php
                            $saldo = 0;
                        @endphp
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase">No</th>
                                    <th class="text-uppercase text-start" style="width: 120px;">Tanggal</th>
                                    <th class="text-uppercase text-start" style="width: 100px;">Akun</th>
                                    <th class="text-uppercase text-start">Petugas</th>
                                    <th class="text-uppercase text-start" style="min-width: 500px;">Deskripsi Transaksi</th>
                                    <th class="text-uppercase text-center">Pemasukan</th>
                                    <th class="text-uppercase text-center">Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nomorUrut = 1;
                                @endphp

                                @foreach($transaksiJurnal as $trx)
                                <tr>
                                    <td class="text-start">{{ $nomorUrut++ }}.</td>
                                    <td class="text-start">
                                        {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($trx->tanggal_transaksi, 'd F Y') }}
                                    </td>
                                    <td class="text-start">{{ $trx->akuntansi_rekening->nama_rekening ?? '-' }}</td>
                                    <td class="text-start">{{ $trx->ms_pengguna->nama ?? '-' }}</td>
                                    <td class="text-start">{{ $trx->deskripsi }}</td>
                                    <td class="text-center">
                                        <span class="fs-14 text-success">
                                            {{ $trx->posisi == 'debit' ? 'RP' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-14 text-danger">
                                            {{ $trx->posisi == 'kredit' ?'RP' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                {{-- Baris Total --}}
                                <tr class="table-light">
                                    <td colspan="5" class="text-end fw-bold">TOTAL</td>
                                    <td class="text-center">
                                        <span class="fs-14 text-success">
                                            RP{{ number_format($totalDebit, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-14 text-danger">
                                            RP{{ number_format($totalKredit, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>

                                <tr class="fw-bold">
                                    <td colspan="5" class="bg-dark text-white text-end">SALDO AWAL</td>
                                    <td colspan="2" class="bg-dark text-white text-center">
                                        <span class="fs-14">
                                            RP{{ number_format($saldoAwal, 0, ',', '.') }}
                                        </span>            
                                    </td>
                                </tr>

                                {{-- Saldo Akhir --}}
                                <tr class="fw-bold">
                                    <td colspan="5" class="text-end">SALDO AKHIR</td>
                                    <td colspan="2" class="text-center">
                                        <span class="fs-14">
                                            RP{{ number_format($saldoAkhir, 0, ',', '.') }}
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
                                Apakah Anda yakin ingin mengekspor laporan Rekapitulasi? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
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
    <div class="col-xxl-4">
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
    </div>

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
                XLSX.writeFile(workbook, "Laporan-Jurnal-Umum.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>