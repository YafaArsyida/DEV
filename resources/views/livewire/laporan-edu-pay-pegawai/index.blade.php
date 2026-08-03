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
                            Riwayat Transaksi EduPay Pegawai
                        </h5>
                        <small class="text-muted">
                            Lihat riwayat transaksi EduPay Pegawai berdasarkan filter yang dipilih.
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
                <label for="selectJabatan" class="form-label">Jabatan</label>
                <select id="selectJabatan" wire:model="selectedJabatan" 
                        class="form-select" style="cursor: pointer"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" 
                        data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Jabatan</option>
                    @foreach ($select_jabatan as $item)    
                        <option value="{{ $item->ms_jabatan_id }}">{{ $item->nama_jabatan }}</option>
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
                            <th class="text-uppercase">Pegawai</th>
                            <th class="text-uppercase">Jabatan</th>
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
                                    {{ ucfirst($item->ms_pegawai->nama_pegawai) }}
                                </td>
                                <td class="text-start">
                                    {{ $item->ms_pegawai->ms_jabatan->nama_jabatan ?? ''}}
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
    <div class="modal fade zoomIn"
        id="ExportLaporanExcel"
        tabindex="-1"
        aria-labelledby="exportEduPayPegawaiLabel"
        aria-hidden="true"
        wire:ignore.self>

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- CLOSE BUTTON --}}
                <div class="modal-header border-0 pb-0">

                    <button
                        type="button"
                        class="btn btn-light btn-icon rounded-circle ms-auto"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                        <i class="ri-close-line fs-18"></i>

                    </button>

                </div>

                {{-- BODY --}}
                <div class="modal-body px-4 pb-5 pt-2 text-center">

                    {{-- ICON --}}
                    <div class="mb-4">

                        <div class="avatar-xl mx-auto">

                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">

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

                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                            Konfirmasi Export
                        </span>

                        <h3 class="fw-bold mb-2" id="exportEduPayPegawaiLabel">
                            Export Laporan EduPay Pegawai?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data laporan EduPay Pegawai yang diekspor akan mengikuti
                            data pada tabel yang sedang ditampilkan,
                            sehingga hasil export sesuai dengan data yang Anda lihat
                            saat ini.
                        </p>

                    </div>

                    {{-- INFORMATION --}}
                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">

                        <div class="d-flex align-items-start gap-3">

                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-primary fs-20"></i>
                            </div>

                            <div>

                                <h6 class="fw-semibold mb-1">
                                    Informasi
                                </h6>

                                <p class="text-muted mb-0 fs-13">
                                    Pastikan data laporan EduPay Pegawai yang
                                    ditampilkan sudah sesuai sebelum melakukan
                                    export laporan.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Batal

                    </button>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        id="konfirmasiExportLaporanEduPayPegawai"
                        data-bs-dismiss="modal">

                        <i class="ri-download-2-line me-1"></i>
                        Ya, Export

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.getElementById('konfirmasiExportLaporanEduPayPegawai')
            .addEventListener('click', function () {

                alertify.success("Menyiapkan Dokumen");

                setTimeout(function () {

                    // Ambil tabel EduPay Pegawai
                    var table = document.getElementById("tabelEduPay");

                    // Konversi tabel menjadi workbook Excel
                    var workbook = XLSX.utils.table_to_book(table, {
                        sheet: "Sheet1"
                    });

                    // Nama file export
                    let fileName = `Laporan-EduPay-Pegawai-{{ date('Y-m-d') }}.xlsx`;

                    // Download file Excel
                    XLSX.writeFile(workbook, fileName);

                }, 1000);

            });
    </script>
</div>