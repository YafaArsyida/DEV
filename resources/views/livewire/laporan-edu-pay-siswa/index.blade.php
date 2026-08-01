{{-- Stop trying to control. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-history-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Riwayat Transaksi EduPay Siswa
                        </h5>
                        <small class="text-muted">
                            Lihat riwayat transaksi EduPay siswa berdasarkan filter yang dipilih.
                        </small>
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">

                    {{-- @if ($selectedKelas) --}}
                    <button
                        wire:click="cetakLaporan"
                        type="button"
                        class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1">

                        <i class="ri-printer-line"></i>
                        <span>Cetak</span>
                    </button>
                    {{-- @endif --}}

                    <button
                        type="button"
                        class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal"
                        data-bs-target="#ExportLaporanExcel">

                        <i class="ri-file-excel-2-line"></i>
                        <span>Excel</span>
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#filterEduPay"
                        aria-controls="filterEduPay">

                        <i class="ri-filter-3-line"></i>
                        <span>Filter</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">

            <!-- Dropdown Kelas -->
            <div class="col-xxl-2 col-sm-6">
                <label for="selectKelas" class="form-label">Kelas</label>
                <select
                    id="selectKelas"
                    wire:model="selectedKelas"
                    class="form-select"
                    style="cursor: pointer"
                    data-bs-toggle="tooltip"
                    data-bs-trigger="hover"
                    data-bs-placement="top"
                    title="Pilih Kelas">

                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)
                        <option value="{{ $item->ms_kelas_id }}">
                            {{ $item->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="col-xxl-4 col-sm-6">
                <label for="searchData" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input
                        type="text"
                        id="searchData"
                        class="form-control search"
                        wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">

                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>

            <!-- Filter Periode -->
            <div class="col-xxl-6">
                <label class="form-label fw-semibold">Periode</label>

                <div class="d-flex align-items-center gap-2">
                    <input type="date" class="form-control" wire:model="startDate">

                    <span class="text-muted">–</span>

                    <input type="date" class="form-control" wire:model="endDate">

                    <button
                        type="button"
                        class="btn btn-soft-secondary"
                        wire:click="resetTanggal"
                        title="Reset Tanggal">

                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>

        </div>

        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <div class="table-responsive">
                <table id="tabelEduPay" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase text start">Tanggal</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase">Kelas</th>
                            <th class="text-uppercase" scope="col">Transaksi</th>
                            <th class="text-uppercase text-center">Petugas</th>
                            <th class="text-uppercase text-center">Pemasukan</th>
                            <th class="text-uppercase text-center">Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $key => $item)
                            <tr class="text-center">
                                <td class="text-start">{{ $laporan->firstItem() + $key }}.</td>
                                <td class="text-uppercase text-start">
                                    {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y') }}
                                </td>
                                <td class="text-start">
                                    {{ ucfirst($item->ms_siswa->nama_siswa) }}
                                </td>
                                <td class="text-start">
                                    {{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? ''}}
                                </td>
                                <td class="text-start fs-12 fw-medium">
                                    {!! 'Rp' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i>' !!}
                                </td>
                                <td>{{ $item->ms_pengguna->nama ?? '-' }}</td>
                                <td>
                                    <span class="fs-12 fw-medium text-success">
                                        {{ in_array($item->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana']) 
                                            ? 'RP' . number_format($item->nominal, 0, ',', '.') 
                                            : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fs-12 fw-medium text-danger">
                                        {{ in_array($item->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin']) 
                                            ? 'RP' . number_format($item->nominal, 0, ',', '.') 
                                            : '-' }}
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
                    {{-- <tfoot>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Pemasukan</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-success">
                                    RP{{ number_format($totalPemasukan, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Pengeluaran</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-danger">
                                    RP{{ number_format($totalPengeluaran, 0, ',', '.') }}
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
                    </tfoot> --}}
                </table>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $laporan->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $laporan->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $laporan->total() }}
                            </span>
                            data
                        </div>
                        <div>
                            {{ $laporan->links() }}
                        </div>
                    </div>
                </div>
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
                            Apakah Anda yakin ingin mengekspor laporan Edupay Siswa? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
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
                var table = document.getElementById("tabelEduPay"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-EduPay-Siswa.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>