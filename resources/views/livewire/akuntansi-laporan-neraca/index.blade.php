<div class="row justify-content-center">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">
                            Laporan Neraca
                        </h5>
                        {{-- <p class="mb-0">Neraca adalah laporan keuangan yang menunjukkan kondisi keuangan sekolah pada titik waktu tertentu. Laporan ini menggambarkan hubungan antara aset yang dimiliki, kewajiban yang harus dibayar, dan ekuitas (modal) sekolah. Neraca selalu dalam keadaan seimbang</p>        --}}
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2 flex-wrap">
                            {{-- <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button> --}}
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
                    <div class="col-xxl-12 col-sm-12"> 
                        <div class="row g-2 align-items-center">
                            <!-- Label di sisi kiri -->
                            <div class="col-auto">
                                <label for="startDate" class="form-label text-muted text-uppercase fs-12 fw-medium mb-0">Periode </label>
                            </div>
                            <!-- Input tanggal di sisi kanan -->
                            <div class="col">
                                <div class="row g-2 align-items-center">
                                    <div class="col-lg">
                                        <input type="date" id="endDate" class="form-control" wire:model="endDate" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal" title="Reset Tanggal">
                                    <i class="ri-refresh-line fs-16"></i>
                                </button>                                
                            </div>
                        </div>
                    </div>
                </div>
                {{-- DATA --}}
                <div class="live-preview">
                    <div class="table-responsive">
                        <div class="text-center my-3">
                            <h4 class="mb-0">Laporan Neraca</h4>
                            <div>Yayasan Drul Khukama Unit {{ $namaJenjang }}</div>
                            @if ($endDate)
                                <div>
                                    <strong>
                                        Per {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y') }}
                                    </strong>
                                </div>
                                @else
                                <div>
                                    <strong>
                                        Semua Periode
                                    </strong>
                                </div>
                            @endif
                        </div>
                        <table class="table table-bordered table-hover table-nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Akun</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- ASET --}}
                                <tr><th colspan="2" class="bg-light">ASET</th></tr>
                                @php $totalAset = 0; @endphp
                                @foreach ($kelompok['aset'] as $akun)
                                    <tr>
                                        <td>{{ $akun['nama'] }}</td>
                                        <td class="text-end">RP{{ number_format($akun['saldo'], 0, ',', '.') }}</td>
                                    </tr>
                                    @php $totalAset += $akun['saldo']; @endphp
                                @endforeach
                                <tr>
                                    <th class="bg-dark text-white">TOTAL ASET</th>
                                    <th class="bg-dark text-white text-end">RP{{ number_format($totalAset, 0, ',', '.') }}</th>
                                </tr>
                        
                                {{-- KEWAJIBAN --}}
                                <tr><th colspan="2" class="bg-light">KEWAJIBAN</th></tr>
                                @php $totalKewajiban = 0; @endphp
                                @foreach ($kelompok['kewajiban'] as $akun)
                                    <tr>
                                        <td>{{ $akun['nama'] }}</td>
                                        <td class="text-end">RP{{ number_format($akun['saldo'], 0, ',', '.') }}</td>
                                    </tr>
                                    @php $totalKewajiban += $akun['saldo']; @endphp
                                @endforeach
                                <tr>
                                    <th>Total Kewajiban</th>
                                    <th class="text-end">RP{{ number_format($totalKewajiban, 0, ',', '.') }}</th>
                                </tr>
                        
                                {{-- EKUITAS --}}
                                <tr><th colspan="2" class="bg-light">EKUITAS</th></tr>
                                @if(isset($labaRugi))
                                    <tr>
                                        <td>
                                            {{ $tutupBuku == 'sudah' ? 'Akumulasi Surplus/Defisit' : 'Surplus/Defisit Tahun Berjalan' }}
                                        </td>
                                        <td class="text-end">RP{{ number_format($labaRugi, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @php $totalEkuitas = 0; @endphp
                                @foreach ($kelompok['ekuitas'] as $akun)
                                    <tr>
                                        <td>{{ $akun['nama'] }}</td>
                                        <td class="text-end">RP{{ number_format($akun['saldo'], 0, ',', '.') }}</td>
                                    </tr>
                                    @php $totalEkuitas += $akun['saldo']; @endphp
                                @endforeach
                                <tr>
                                    <th>Total Ekuitas</th>
                                    <th class="text-end">RP{{ number_format(($labaRugi ?? 0) + $totalEkuitas, 0, ',', '.') }}</th>
                                </tr>
                        
                                {{-- TOTAL PASSIVA --}}
                                <tr>
                                    <th class="bg-dark text-white">TOTAL KEWAJIBAN + EKUITAS</th>
                                    <th class="bg-dark text-white text-end">RP{{ number_format($totalKewajiban + $labaRugi ?? 0 + $totalEkuitas, 0, ',', '.') }}</th>
                                </tr>
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
                    XLSX.writeFile(workbook, "Laporan-Neraca.xlsx");
                }, 1000); // 1000 ms = 1 detik
            });
        </script>
    </div>
    <div class="col-xxl-4">
         <div class="card shadow-none mb-3">
            <div class="card-body bg-info-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-info-circle text-info fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Apa itu Laporan Neraca?</h6>
                        <p class="text-muted mb-0">
                            Laporan Neraca (Balance Sheet) adalah laporan keuangan yang menunjukkan kondisi keuangan sekolah pada suatu titik waktu tertentu (biasanya akhir bulan atau akhir tahun ajaran). Neraca selalu dalam keadaan seimbang mengikuti rumus: 
                            <strong>ASET = KEWAJIBAN + EKUITAS</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none">
            <div class="card-body bg-success-subtle rounded">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bx bx-list-check text-success fs-22"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-2">Fungsi Laporan Neraca</h6>
                        <ul class="mb-0 text-muted">
                            <li>Menampilkan posisi aset, kewajiban, dan ekuitas sekolah</li>
                            <li>Menggambarkan kesehatan keuangan secara menyeluruh</li>
                            <li>Membantu pihak yayasan atau manajemen mengambil keputusan keuangan</li>
                            <li>Menjadi dasar untuk menyusun anggaran di periode berikutnya</li>
                            <li>Digunakan untuk audit dan transparansi keuangan lembaga</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>