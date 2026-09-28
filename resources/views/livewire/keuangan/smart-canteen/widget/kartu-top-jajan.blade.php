<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bar-chart-grouped-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Top Jajan SmartCanteen
                        </h5>

                        <small class="text-muted">
                            Lihat daftar pembeli dengan total transaksi jajan tertinggi.
                        </small>
                    </div>

                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">

                    {{-- EXCEL --}}
                    <button
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#ExportLaporanTopExcel"
                        class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1"
                    >
                        <i class="ri-file-excel-2-line"></i>
                        <span>Export</span>
                    </button>

                </div>
            </div>

        </div>

    </div>

    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Input Pencarian -->
            <div class="col-xxl-4 col-sm-6">
                <label for="searchInput" class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Pencarian
                </label>

                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control search"
                        wire:model.debounce.300ms="search" placeholder="...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-xxl-4 col-sm-6">
                <label for="selectJenis" class="form-label">Pembeli</label>
                <select id="selectJenis" wire:model="selectedJenis" class="form-select" style="cursor: pointer">
                    <option value="">Semua</option>
                    <option value="siswa">Siswa</option>
                    <option value="pegawai">Pegawai</option>
                </select>
            </div>

            <!-- Filter Periode -->
            <div class="col-xxl-4 col-sm-6">
                <label for="selectPeriode" class="form-label fw-semibold">Periode</label>
                <select id="selectPeriode" wire:model="selectedPeriode" class="form-select" style="cursor: pointer">
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="3_bulan">3 Bulan Terakhir</option>
                    <option value="6_bulan">6 Bulan Terakhir</option>
                </select>
            </div>
        </div>

        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <div class="table-responsive">
                <table id="tabelSmartCanteenTop" class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th class="text-uppercase">Nama</th>
                            <th class="text-uppercase">Pelanggan</th>
                            <th class="text-uppercase">EduCard</th>
                            <th class="text-uppercase text-center">Total Jajan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswas as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($item->user_type === 'siswa' && $item->ms_siswa)
                                    {{ $item->ms_siswa->nama_siswa }}
                                @elseif ($item->user_type === 'pegawai' && $item->ms_pegawai)
                                    {{ $item->ms_pegawai->nama_pegawai }}
                                @elseif ($item->user_type === 'umum')
                                    Umum
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($item->user_type === 'siswa' && $item->ms_siswa)
                                Siswa
                                @elseif ($item->user_type === 'pegawai' && $item->ms_pegawai)
                                {{ $item->ms_pegawai->ms_jabatan->nama_jabatan }}
                                @elseif ($item->user_type === 'umum')
                                    Umum
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <span class="fs-14 text-warning">
                                    @if ($item->user_type === 'siswa' && $item->ms_siswa)
                                    {{ $item->ms_siswa->ms_educard->kode_kartu ?? '-' }}
                                    @elseif ($item->user_type === 'pegawai')
                                    {{ $item->ms_pegawai->ms_educard->kode_kartu ?? '-' }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-14 text-success">
                                    RP{{ number_format($item->total_jajan, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $siswas->links() }} --}}
            </div>
        </div>
        {{-- end data --}}
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ExportLaporanTopExcel" tabindex="-1" aria-labelledby="exportRecordLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Konfirmasi Export</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">
                            Apakah Anda yakin ingin mengekspor laporan Top SmartCanteen? Data yang diekspor akan sesuai
                            dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary" id="konfirmasiExportLaporanTop" data-bs-dismiss="modal">Ya,
                                Export!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportLaporanTop').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("tabelSmartCanteenTop"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Top-SmartCanteen.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>