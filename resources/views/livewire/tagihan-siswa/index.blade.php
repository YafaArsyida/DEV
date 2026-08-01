<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Tagihan Siswa
                        </h5>
                        {{-- <small>
                            Kelola laporan kegiatan generus 
                        </small> --}}
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            <div class="d-flex gap-2 flex-wrap">
                @if ($selectedJenjang && $selectedTahunAjar && $selectedKelas)
                <button wire:click="cetakLaporanTagihan" class="btn rounded-pill px-4 btn-danger d-inline-flex align-items-center gap-1">
                    <i class="ri-printer-line align-bottom"></i>
                    <span>Cetak Laporan</span>
                </button>
                @endif
                <button data-bs-toggle="offcanvas" id="create-btn" data-bs-target="#offcanvasAddTagihan" wire:click.prevent="$emit('showCreateTagihan', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})" class="btn rounded-pill px-4 btn-primary"><i class="ri-play-list-add-line"></i> Tagihan Baru</button>
                <button data-bs-toggle="modal" data-bs-target="#ModalIndex" class="btn rounded-pill px-4 btn-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Input Pencarian -->
            <div class="col-12 col-lg-6">
                <label for="searchData" class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <!-- Dropdown Kelas -->
            <div class="col-6 col-lg-3">
                <label for="filterKelas" class="form-label">Kelas</label>
                <select id="filterKelas" wire:model="selectedKelas" class="form-select" style="cursor: pointer;"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)
                    <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Dropdown Jumlah Per Halaman -->
            <div class="col-6 col-lg-3">
                <label for="perPage" class="form-label">Tampilkan</label>
                <select id="perPage" wire:model="perPage" class="form-select" style="cursor: pointer;">
                    <option value="10">10 Data</option>
                    <option value="20">20 Data</option>
                    <option value="30">30 Data</option>
                    <option value="40">40 Data</option>
                    <option value="50">50 Data</option>
                    <option value="75">75 Data</option>
                    <option value="100">100 Data</option>
                </select>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
            @if (!$selectedJenjang || !$selectedTahunAjar)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                </div>
            @else
            <div class="table-responsive">
                <table id="DataIndexTagihan" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr class="text-uppercase" style="white-space: nowrap;">
                            <th style="width: 50px;">NO</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Tagihan</th>
                            <th>Estimasi</th>
                            <th>Dibayarkan</th>
                            <th>Kekurangan</th>
                            <th>Lunas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $key => $item)
                        <tr>
                            <td>{{ $tagihans->firstItem() + $key }}.</td>
                            <td class="text-start">
                                <span class="fw-medium">
                                    {{ $item->ms_siswa->nama_siswa }}
                                </span>
                                {{-- <p class="text-muted mb-0">{{ $item->ms_siswa->deskripsi }}</p> --}}
                            </td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            <td>{{ $item->jumlah_item }} item</td>
                            
                            <td>
                                <span class="fw-medium fs-12 text-primary">
                                    RP{{ number_format($item->total_tagihan, 0, ',', '.') }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="fw-medium fs-12 text-success">
                                    RP{{ number_format($item->total_bayar, 0, ',', '.') }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="fw-medium fs-12 text-danger">
                                    RP{{ number_format($item->total_tagihan - $item->total_bayar, 0, ',', '.') }}
                                </span>
                            </td>
                            
                            <td>
                                @php
                                $estimasi = $item->total_tagihan;
                                $dibayarkan = $item->total_bayar;
                                @endphp
                            
                                <span class="fs-12 fw-medium">
                                    @if ($estimasi > 0)
                                    {{ number_format(($dibayarkan / $estimasi) * 100, 2) }}% <i class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                    @else
                                    -
                                    @endif
                                </span>
                            </td>   
                                            
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- detail tagihan --}}
                                    <button class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasDetailTagihan"
                                            title="Detail Tagihan"
                                            wire:click.prevent="$emit('showDetailTagihan', {
                                                ms_penempatan_siswa_id: {{ $item->ms_penempatan_siswa_id }},
                                                jenjang: {{ $item->ms_jenjang_id }},
                                                tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                nama_siswa: '{{ addslashes($item->ms_siswa->nama_siswa) }}'
                                            })">
                                        <i class="ri-eye-line align-bottom me-1"></i> Detail
                                    </button>
                                    
                                    {{-- kelola tagihan --}}
                                    <button class="btn btn-primary btn-sm rounded-pill px-3"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasManage"
                                            aria-controls="offcanvasManage"
                                            title="Kelola Tagihan"
                                            wire:click.prevent="$emit('manageTagihan', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-settings-3-line align-bottom me-1"></i> Kelola
                                    </button>

                                    {{-- riwayat transaksi --}}
                                    <a href="javascript:void(0);"
                                       class="btn btn-soft-success btn-sm rounded-pill px-3"
                                       data-bs-toggle="offcanvas"
                                       data-bs-target="#offcanvasHistori"
                                       aria-controls="offcanvasHistori"
                                       title="Riwayat Transaksi"
                                       wire:click.prevent="$emit('showHistoriTagihan', {
                                            ms_penempatan_siswa_id: {{ $item->ms_penempatan_siswa_id }},
                                            jenjang: {{ $item->ms_jenjang_id }},
                                            tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                            nama_siswa: '{{ addslashes($item->ms_siswa->nama_siswa) }}'
                                       })">
                                        <i class="ri-history-line align-bottom"></i> Riwayat
                                    </a>
                                </div>
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
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td>
                                {{ $jumlahTagihan }} item
                            </td>
                            <td>
                                <span class="fs-12 fw-medium text-primary">
                                    RP{{ number_format($totalTagihan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-12 fw-medium text-success">
                                    RP{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-12 fw-medium text-danger">
                                    RP{{ number_format($totalKekurangan, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-12 fw-medium">{{ number_format($totalPersen, 2) }}% <i class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-medium">
                                {{ $tagihans->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-medium">
                                {{ $tagihans->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-medium">
                                {{ $tagihans->total() }}
                            </span>
                            data tagihan
                        </div>
                        <div>
                            {{ $tagihans->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @endif
        </div>
        {{-- end data --}}
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ModalIndex" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- CLOSE BUTTON --}}
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body px-4 pb-5 pt-2 text-center">
                    {{-- ICON --}}
                    <div class="mb-4">
                        <div class="avatar-xl mx-auto">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json"
                                    trigger="loop" colors="primary:#405189,secondary:#0ab39c"
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

                        <h3 class="fw-bold mb-2" id="exportRecordLabel">
                            Export Administrasi Tagihan Siswa?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data yang diekspor akan mengikuti filter dan tabel yang
                            sedang ditampilkan, sehingga hasil export sesuai dengan
                            data yang Anda lihat saat ini.
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
                                    Pastikan filter jenjang, tahun ajar, kelas, maupun
                                    pencarian sudah sesuai sebelum melakukan export.
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

                    <button type="button" class="btn btn-primary rounded-pill px-4"
                        id="konfirmasiExportIndex" data-bs-dismiss="modal">
                        <i class="ri-download-2-line me-1"></i>
                        Ya, Export
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportIndex').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");

            setTimeout(function () {
                var table = document.getElementById("DataIndexTagihan");

                var data = [];
                // Kolom yang ingin diexport (NO=0, Siswa=1, Kelas=2, Tagihan=3, Estimasi=4, Dibayarkan=5, Kekurangan=6, Lunas=7)
                var exportCols = [0,1,2,3,4,5,6,7];

                // Ambil header
                var headers = [];
                for(var i=0; i<exportCols.length; i++){
                    headers.push(table.tHead.rows[0].cells[exportCols[i]].innerText.trim());
                }
                data.push(headers);

                // Ambil data tbody
                for(var i=0; i<table.tBodies[0].rows.length; i++){
                    var row = table.tBodies[0].rows[i];
                    var rowData = [];
                    for(var j=0; j<exportCols.length; j++){
                        rowData.push(row.cells[exportCols[j]].innerText.trim());
                    }
                    data.push(rowData);
                }

                // Ambil data tfoot (jika ada)
                if(table.tFoot){
                    for(var i=0; i<table.tFoot.rows.length; i++){
                        var row = table.tFoot.rows[i];
                        var rowData = [];
                        for(var j=0; j<exportCols.length; j++){
                            rowData.push(row.cells[exportCols[j]].innerText.trim());
                        }
                        data.push(rowData);
                    }
                }

                // Buat workbook
                var wb = XLSX.utils.book_new();
                var ws = XLSX.utils.aoa_to_sheet(data);
                XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

                XLSX.writeFile(wb, "Laporan-Administrasi-Tagihan-Siswa.xlsx");

            }, 1000);
        });

    </script>
</div>