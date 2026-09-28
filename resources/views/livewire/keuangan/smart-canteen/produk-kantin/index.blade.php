<div id="produkList" class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-shopping-bag-3-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Administrasi Produk SmartCanteen
                        </h5>

                        <small class="text-muted">
                            Kelola produk, harga, dan kategori untuk kantin
                            <span class="fw-semibold text-dark">
                                {{ $namaKantin }}
                            </span>.
                        </small>
                    </div>

                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex gap-2 flex-wrap">

                {{-- TAMBAH PRODUK --}}
                <button type="button" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#ModalTambahProduk"
                    wire:click="$emit('showCreateProduk', {{ $selectedKantin ?? 'null' }})">

                    <i class="ri-add-line"></i>
                    <span>Tambah Produk</span>
                </button>

                {{-- IMPORT --}}
                {{-- <button type="button"
                    class="btn btn-soft-info rounded-pill px-4 d-inline-flex align-items-center gap-1">
                    <i class="ri-file-upload-line"></i>
                    <span>Import</span>
                </button> --}}

                {{-- DELETE MULTIPLE --}}
                {{-- <button type="button" class="btn btn-soft-danger btn-icon rounded-circle"
                    id="remove-actions" onclick="deleteMultiple()" title="Hapus Produk Terpilih">

                    <i class="ri-delete-bin-2-line fs-18"></i>
                </button> --}}

            </div>

        </div>
    </div>
    <!-- Search & Filter -->
    <div class="card-body">
        <div class="row g-3">
            <div class="col-xxl-10 col-sm-8">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="Cari nama produk...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-xxl-2 col-sm-4">
                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasKategori" aria-controls="offcanvasKategori" class="btn btn-primary w-100">
                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Master Kategori Produk
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs kategori -->
    <div class="card-body pt-0">
        <ul class="nav nav-tabs nav-tabs-custom nav-success" role="tablist">
            <!-- Tab Semua Produk -->
            <li class="nav-item">
                <a class="nav-link py-3 {{ $activeTab === 'semua' ? 'active' : '' }}" 
                wire:click="setActiveTab('semua')" 
                data-bs-toggle="tab" href="#tabAll" role="tab">
                    <i class="ri-store-2-fill me-1 align-bottom"></i> Semua Produk
                </a>
            </li>

            <!-- Tab berdasarkan kategori -->
            @foreach($kategori as $item)
            <li class="nav-item">
                <a class="nav-link py-3 {{ $activeTab === 'kategori-'.$item->ms_kategori_produk_kantin_id ? 'active' : '' }}" 
                wire:click="setActiveTab('kategori-{{ $item->ms_kategori_produk_kantin_id }}')" 
                data-bs-toggle="tab" href="#tabKategori{{ $item->ms_kategori_produk_kantin_id }}" role="tab">
                    <i class="{{ $item->icon ?? 'mdi mdi-tag' }} me-1 align-bottom"></i>
                    {{ $item->nama_kategori_produk_kantin }}
                </a>
            </li>
            @endforeach
            <!-- Tombol Offcanvas di Kanan -->
            <li class="nav-item">
                <button data-bs-toggle="modal" 
                    data-bs-target="#ModalTambahKategori" wire:click="$emit('showCreateKategori', {{ $selectedKantin ?? 'null' }})" class="btn btn-sm shadow-none nav-link py-3">
                    <i class="ri-add-line me-1 align-bottom"></i> Tambah Kategori
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3">

            <!-- Semua Produk -->
            <div class="tab-pane fade {{ $activeTab === 'semua' ? 'show active' : '' }}" id="tabAll" role="tabpanel">
                <div class="row g-3">
                    @include('livewire.smart-canteen.produk-kantin.kartu-produk', ['listProduk' => $allProduk])
                </div>
            </div>

            <!-- Produk per Kategori -->
            @foreach($kategori as $kat)
            <div class="tab-pane fade {{ $activeTab === 'kategori-'.$kat->ms_kategori_produk_kantin_id ? 'show active' : '' }}" 
                id="tabKategori{{ $kat->ms_kategori_produk_kantin_id }}" role="tabpanel">
                <div class="row g-3">
                    @php
                        $produkKategori = $allProduk->where('ms_kategori_produk_kantin_id', $kat->ms_kategori_produk_kantin_id);
                    @endphp

                    @include('livewire.smart-canteen.produk-kantin.kartu-produk', ['listProduk' => $produkKategori])
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>