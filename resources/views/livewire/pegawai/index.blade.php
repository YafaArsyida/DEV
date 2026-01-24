<div class="card" id="generusList">
    <div class="card-header border-0">
        <div class="row align-items-center gy-3">
            <div class="col-sm">
                <h5 class="card-title mb-0">Data Pegawai</h5>
            </div>

            <div class="col-sm-auto">
                <div class="d-flex gap-1 flex-wrap">
                    @if ($selectedJenjang)
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportKontakPegawai" wire:click.prevent="$emit('showImportKontakPegawai', {{ $selectedJenjang }})" class="btn btn-success"><i class="ri-whatsapp-line me-1 align-bottom"></i> Import Kontak</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportEduCardPegawai" wire:click.prevent="$emit('showImportEduCardPegawai', {{ $selectedJenjang }})" class="btn btn-warning"><i class="ri-bank-card-line me-1 align-bottom"></i> Import EduCard</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportPegawai" wire:click.prevent="$emit('showImportPegawai', {{ $selectedJenjang }})" class="btn btn-secondary"><i class="ri-contacts-line me-1 align-bottom"></i> Import Pegawai</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalPegawaiCreate" wire:click.prevent="$emit('PegawaiCreate', {{ $selectedJenjang }})" class="btn btn-primary"><i class="ri-play-list-add-line align-bottom me-1"></i>Pegawai Baru</button>
                    @endif
                    <button data-bs-toggle="modal" data-bs-target="#ModalPegawaiExport" class="btn btn-soft-success"><i class="ri-file-excel-2-line align-bottom me-1"></i> Export</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card-body border border-dashed border-start-0 border-end-0">
        <div class="row g-3">

            {{-- Search --}}
            <div class="col-xxl-10 col-sm-8">
                <div class="search-box">
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                        placeholder="Cari nama pegawai ...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-xxl-2 col-sm-4">
                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasJabatan"
                    aria-controls="offcanvasJabatan" class="btn btn-primary w-100">
                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Master Data Jabatan
                </button>
            </div>

        </div>
    </div>

    <div class="card-body pt-0">
        <ul class="nav nav-tabs nav-tabs-custom nav-success" role="tablist">
        
            {{-- TAB SEMUA --}}
            <li class="nav-item">
                <a class="nav-link py-3 {{ $activeTab === 'semua' ? 'active' : '' }}" wire:click="setActiveTab('semua')"
                    data-bs-toggle="tab" href="#tabSemua" role="tab">
                    <i class="ri-team-fill me-1 align-bottom"></i>
                    Semua Pegawai
                </a>
            </li>
        
            {{-- TAB DINAMIS JABATAN --}}
            @foreach($jabatan as $item)
            <li class="nav-item">
                <a class="nav-link py-3 {{ $activeTab === 'jabatan-'.$item->ms_jabatan_id ? 'active' : '' }}"
                    wire:click="setActiveTab('jabatan-{{ $item->ms_jabatan_id }}')" data-bs-toggle="tab"
                    href="#tabJabatan{{ $item->ms_jabatan_id }}" role="tab">
                    <i class="ri-medal-fill me-1 align-bottom"></i>
                    {{ $item->nama_jabatan }}
                </a>
            </li>
            @endforeach
            <li class="nav-item">
                <button data-bs-toggle="modal" data-bs-target="#ModalJabatanCreate" wire:click="$emit('JabatanCreate')"
                    class="btn btn-sm shadow-none nav-link py-3">
                    <i class="ri-add-line me-1 align-bottom"></i> Tambah Jabatan
                </button>
            </li>
        </ul>
        <div class="tab-content mt-3">
        
            {{-- TAB SEMUA --}}
            <div class="tab-pane fade {{ $activeTab === 'semua' ? 'show active' : '' }}" id="tabSemua" role="tabpanel">
        
                @php $listPegawai = $allPegawai; @endphp
                @include('livewire.pegawai.data', compact('listPegawai'))
            </div>
        
            {{-- TAB PER JABATAN --}}
            @foreach($jabatan as $grp)
            <div class="tab-pane fade {{ $activeTab === 'jabatan-'.$grp->ms_jabatan_id ? 'show active' : '' }}"
                id="tabJabatan{{ $grp->ms_jabatan_id }}" role="tabpanel">
        
                @php
                $listPegawai = $allPegawai->where('ms_jabatan_id', $grp->ms_jabatan_id);
                @endphp
        
                @include('livewire.pegawai.data', compact('listPegawai'))
            </div>
            @endforeach
        
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ModalPegawaiExport" tabindex="-1" aria-labelledby="exportRecordLabel"
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
                            Apakah Anda yakin ingin mengekspor laporan Data Pegawai? Data yang diekspor akan
                            sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya,
                                Export!</button>
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
                    var table = document.getElementById("PegawaiData");
                    
                    // Konversi tabel ke format Excel
                    var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                    
                    // Simpan file Excel
                    XLSX.writeFile(workbook, "Data Pegawai.xlsx");
                }, 1000); // 1000 ms = 1 detik
            });
    </script>
</div>