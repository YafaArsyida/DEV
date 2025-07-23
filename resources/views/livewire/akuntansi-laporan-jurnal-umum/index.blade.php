<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">
                            Laporan Jurnal Keuangan
                        </h5>
                        {{-- <p class="mb-0">kjsbhdjjsjjs</p>        --}}
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2 flex-wrap">
                            <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
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
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-xxl-8 col-sm-6">
                        <div class="search-box">
                            <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-sm-6"> 
                        {{-- <select wire:model="selectedBulan" wire:change="$emit('bulanUpdated', $event.target.value)" class="form-select">
                            <option value="">Semua Periode</option>
                            @foreach ($select_bulan as $bulan)
                                <option value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>
                            @endforeach
                        </select> --}}
                        <div class="row g-2 align-items-center">
                            <!-- Label di sisi kiri -->
                            <div class="col-auto">
                                <label for="startDate" class="form-label text-muted text-uppercase fs-12 fw-medium mb-0">Periode </label>
                            </div>
                            <!-- Input tanggal di sisi kanan -->
                            <div class="col">
                                <div class="row g-2 align-items-center">
                                    <div class="col-lg">
                                        <input type="date" id="startDate" class="form-control" wire:model="startDate" placeholder="0">
                                    </div>
                                    <div class="col-lg">
                                        <input type="date" id="endDate" class="form-control" wire:model="endDate" placeholder="0">
                                    </div>
                                </div>
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
                                    <th class="text-uppercase text-start" style="width: 200px;">Tanggal</th>
                                    <th class="text-uppercase text-start">Petugas</th>
                                    <th class="text-uppercase text-start" style="min-width: 500px;">Deskripsi Transaksi</th>
                                    <th class="text-uppercase text-center">Akun Debit</th>
                                    <th class="text-uppercase text-center">Akun Kredit</th>
                                    <th class="text-uppercase text-center">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nomorUrut = 1; // Inisialisasi nomor urut
                                @endphp

                                @foreach ($transaksiJurnal as $deskripsi => $transaksiByNominal)
                                    @foreach ($transaksiByNominal as $nominal => $transaksi)
                                        @php
                                            $debit = $transaksi->where('posisi', 'debit')->first();
                                            $kredit = $transaksi->where('posisi', 'kredit')->first();
                                            $tanggal = $transaksi->first()->tanggal_transaksi ?? null;
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ $nomorUrut++ }}.</td>
                                            <td class="text-start">
                                                {{ $tanggal ? \App\Http\Controllers\HelperController::formatTanggalIndonesia($tanggal, 'd F Y H:i:s') : '-' }}
                                            </td>
                                            <td class="text-start">{{ $debit ? $debit->ms_pengguna->nama : ($kredit ? $kredit->ms_pengguna->nama : '-') }}</td>
                                            <td>{{ $deskripsi }}</td>
                                            <td style="white-space: nowrap;" class="text-center">{{ $debit ? $debit->akuntansi_rekening->nama_rekening : '-' }}</td>
                                            <td style="white-space: nowrap;" class="text-center">{{ $kredit ? $kredit->akuntansi_rekening->nama_rekening : '-' }}</td>
                                            <td class="text-center">
                                                <span class="fs-14 text-info">
                                                    RP{{ number_format($nominal, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
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