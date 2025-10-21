{{-- Stop trying to control. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Laporan Transaksi Tabungan Pegawai</h5>
                {{-- <p class="mb-0">Transaksi akan ditampilkan dari semua petugas untuk memastikan penghitungan yang akurat dan terkini.</p> --}}
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    {{-- @if ($selectedJabatan)
                        <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                            <i class="ri-printer-line align-bottom"></i>
                            <span>Cetak Laporan</span>
                        </button>
                    @endif --}}
                    <button data-bs-toggle="modal" data-bs-target="#ExportLaporanExcel" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                    {{-- <button data-bs-toggle="modal" data-bs-target="#ExportTabunganSiswa" wire:click.prevent="showExportTabunganSiswa" class="btn btn-soft-success"><i class="ri-file-excel-2-line"></i> Export</button> --}}
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterTabungan" aria-controls="filterTabungan"><i class="ri-filter-3-line align-bottom me-1"></i> Fliters</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Dropdown Kelas -->
            <div class="col-xxl-3 col-sm-6">
                <label for="selectJabatan" class="form-label">Jabatan</label>
                <select id="selectJabatan" wire:model="selectedJabatan" 
                        class="form-select" style="cursor: pointer"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" 
                        data-bs-placement="top" title="Pilih Jabatan">
                    <option value="">Semua Jabatan</option>
                    @foreach ($select_jabatan as $item)    
                    <option value="{{ $item->ms_jabatan_id }}">{{ $item->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Input Pencarian -->
            <div class="col-xxl-9 col-sm-6">
                <label for="searchInput" class="form-label">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control ps-4" 
                        wire:model.debounce.300ms="search" 
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <div class="table-responsive">
                <table id="tabelTabungan" class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase text start" scope="col" style="width: 200px;">Tanggal</th>
                            <th class="text-uppercase">Pegawai</th>
                            <th class="text-uppercase" scope="col">Transaksi</th>
                            <th class="text-uppercase text-center">Petugas</th>
                            <th class="text-uppercase text-center">Kredit</th>
                            <th class="text-uppercase text-center">Debit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                            <tr class="text-center">
                                <td class="text-start">{{ $loop->iteration }}.</td>
                                <td class="text-uppercase text-start">
                                    {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                                </td>
                                <td class="text-start" style="white-space: nowrap;">
                                    {{ ucfirst($item->ms_pegawai->nama_pegawai ?? '-') }}
                                    <p class="fs-12 mb-0 text-muted">
                                        {{ $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '' }}
                                    </p>
                                </td>
                                <td class="text-start">
                                    <span class="fs-14">
                                        {!! 'RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i>' !!}
                                    </span>
                                    <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                                </td>
                                <td style="white-space: nowrap;">{{ $item->ms_pengguna->nama ?? '-' }}</td>
                                <td>
                                    <span class="fs-14 text-success">
                                        {{ $item->jenis_transaksi === 'setoran' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fs-14 text-danger">
                                        {{ $item->jenis_transaksi === 'penarikan' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
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
                    <tfoot>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Kredit</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-success">
                                    RP{{ number_format($totalKredit, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Debit</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-danger">
                                    RP{{ number_format($totalDebit, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Saldo</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-info">
                                    RP{{ number_format($totalSaldo, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ExportLaporanExcel" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
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
                            Apakah Anda yakin ingin mengekspor laporan Tabungan Pegawai? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
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
                var table = document.getElementById("tabelTabungan"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Tabungan-Pegawai.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>