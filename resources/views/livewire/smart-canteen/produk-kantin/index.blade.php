 <div class="card" id="produkList">
    <div class="card-header border-0">
        <div class="row align-items-center gy-3">
            <div class="col-sm">
                <h5 class="card-title mb-0">Daftar Produk Kantin</h5>
            </div>
            <div class="col-sm-auto">
                <div class="d-flex gap-1 flex-wrap">
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#modalTambah">
                        <i class="ri-add-line align-bottom me-1"></i> Tambah Produk
                    </button>
                    <button type="button" class="btn btn-info">
                        <i class="ri-file-download-line align-bottom me-1"></i> Import
                    </button>
                    <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()">
                        <i class="ri-delete-bin-2-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form>
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
        </form>
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
                    data-bs-target="#ModalTambahKategori" wire:click="$emit('showCreateKategori', {{ $selectedJenjang ?? 'null' }})" class="btn btn-sm shadow-none nav-link py-3">
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