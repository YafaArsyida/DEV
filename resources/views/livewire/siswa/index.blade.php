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
                            Data Siswa
                        </h5>
                        {{-- <small>
                            Kelola laporan kegiatan generus 
                        </small> --}}
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="d-flex align-items-center flex-wrap gap-2">
                {{-- PRIMARY ACTION --}}
                <button data-bs-toggle="modal" data-bs-target="#ModalAddSiswa"
                    wire:click.prevent="$emit('showCreateSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"
                    class="btn btn-primary rounded-pill px-4">
                    <i class="ri-play-list-add-line me-1"></i>
                    Siswa Baru
                </button>

                {{-- IMPORT SISWA --}}
                <button data-bs-toggle="modal" data-bs-target="#ModalImportSiswa"
                    wire:click.prevent="$emit('showImportSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"
                    class="btn btn-secondary rounded-pill px-4">
                    <i class="ri-contacts-line me-1"></i>
                    Import Siswa
                </button>

                {{-- EXPORT --}}
                <button data-bs-toggle="modal" data-bs-target="#ModalIndexSiswa"
                    class="btn btn-soft-success rounded-pill px-4">
                    <i class="ri-file-excel-2-line me-1"></i>
                    Export
                </button>

                @if ($selectedKelas)
                    {{-- IMPORT TELEPON --}}
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportTelepon"
                        wire:click.prevent="$emit('showImportTelepon', {{ $selectedKelas }}, {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"
                        class="btn btn-soft-success rounded-pill px-4">
                        <i class="ri-whatsapp-line me-1"></i>
                        Import Telepon
                    </button>

                    {{-- IMPORT EDUCARD --}}
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportEduCard"
                        wire:click.prevent="$emit('showImportEduCard', {{ $selectedKelas }}, {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"
                        class="btn btn-soft-warning rounded-pill px-4">
                        <i class="ri-bank-card-line me-1"></i>
                        Import EduCard
                    </button>
                @endif

                {{-- DELETE --}}
                @if ($siswaSelected)
                    <button href="#ModalBulkDeleteSiswa" data-bs-toggle="modal"
                        wire:click.prevent="$emit('confirmBulkDelete', {{ json_encode($siswaSelected) }})"
                        class="btn btn-soft-danger rounded-pill px-4 ms-auto d-inline-flex align-items-center">
                        <i class="ri-delete-bin-2-line me-1"></i>
                        Hapus {{ count($siswaSelected) }}
                    </button>
                @endif
            </div>
            @endif
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
                <table id="DataIndexSiswa" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 50px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkAll" wire:model="selectAll">
                                </div>
                            </th>
                            <th class="text-uppercase" width="50px">no</th>
                            <th class="text-uppercase" style="width: 50px;">Hapus</th>
                            <th class="text-uppercase">siswa</th>
                            <th class="text-uppercase">kelas</th>
                            <th class="text-uppercase">whatsapp</th>
                            <th class="text-uppercase">EduCard</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse ($siswas as $item) --}}
                        @forelse ($siswas as $key => $item)
                        <tr>
                            <td scope="row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:key="{{ $item->ms_penempatan_siswa_id }}"
                                        wire:model.live="siswaSelected" value="{{ $item->ms_penempatan_siswa_id }}">
                                </div>
                            </td>
                            <td>{{ $siswas->firstItem() + $key }}.</td>
                            <td class="text-center">
                                <a href="#ModalDeleteSiswa" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('confirmDeleteSiswa', {{ $item->ms_penempatan_siswa_id }})"
                                    data-bs-trigger="hover" data-bs-placement="top" title="Hapus Siswa">
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->ms_siswa->nama_siswa }}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                            </td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            <td>
                                <span class="fw-semibold text-success">    
                                    {{ $item->ms_siswa->telepon }}
                                </span>
                            </td>
                            <td>
                                @if($item->ms_siswa->ms_educard)
                                <span class="fw-semibold text-warning">    
                                    {{ $item->ms_siswa->ms_educard->kode_kartu }}
                                </span>
                                @else
                                    <em>Belum memiliki kartu</em>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Detail/Transfer --}}
                                    <a href="#ModalDetailSiswa" data-bs-toggle="modal" class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                        title="Detail Siswa" wire:click.prevent="$emit('showDetailSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-eye-line me-1"></i> Detail
                                    </a>
                                    {{-- edit --}}
                                    <a href="#ModalEditSiswa" data-bs-toggle="modal" class="btn btn-primary btn-sm rounded-pill px-3" title="Edit Siswa" 
                                        wire:click="$emit('loadDataSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                        <i class="ri-mark-pen-line me-1"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <!-- Jika Tidak Ada Data Kelas -->
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
                </table>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $siswas->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $siswas->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $siswas->total() }}
                            </span>
                            data siswa
                        </div>
                        <div>
                            {{ $siswas->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
    {{-- MODAL --}}
    <div class="modal fade" id="ModalIndexSiswa" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true">
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
                            Export Administrasi Siswa?
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
                        id="ExportIndexSiswa" data-bs-dismiss="modal">
                        <i class="ri-download-2-line me-1"></i>
                        Ya, Export
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('ExportIndexSiswa').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");

            setTimeout(function () {
                var table = document.getElementById("DataIndexSiswa");

                var data = [];
                // Kolom yang ingin diexport (NO=0, Siswa=1, Kelas=2, Tagihan=3, Estimasi=4, Dibayarkan=5, Kekurangan=6, Lunas=7)
                var exportCols = [1,3,4,5,6];

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

                XLSX.writeFile(wb, "Laporan-Administrasi-Siswa.xlsx");

            }, 1000);
        });

    </script>
</div>
