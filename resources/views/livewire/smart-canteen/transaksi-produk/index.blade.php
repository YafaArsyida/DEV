 <div class="" id="produkList">
    <div class="card-body p-4 pb-0">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <!-- Kiri: Nama & SmartCard -->
            <div>
                <h4 class="mb-1 fw-bold text-dark">
                    {{ $nama ?: 'Belum ada pengguna' }}
                </h4>
                <div class="text-muted">
                    SmartCard : 
                    <span class="fw-medium text-primary">
                        {{ $educard ?: '-' }}
                    </span>
                </div>
            </div>

            <!-- Kanan: Saldo + Tombol Scan -->
            <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
                <div class="text-end me-2">
                    <h6 class="mb-1 text-primary">Saldo EduPay</h6>
                    <h4 class="fw-bold text-success mb-0">
                        RP{{ number_format($saldo_edupay ?? 0, 0, ',', '.') }}
                    </h4>
                </div>

                <!-- Tombol Scan -->
                <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Scan Kartu RFID">
                    <a href="#ModalScanRFID" data-bs-toggle="modal" 
                    class="btn btn-light btn-icon shadow-sm"
                    wire:click.prevent="$emit('openScanModal')">
                        <i class="ri-qr-scan-2-line align-bottom text-primary fs-20"></i>
                    </a>
                </div>
            </div>


        </div>
    </div>
    <!-- Search & Filter -->
    <div class="card-body border-end-0 border-start-0">
        <form>
            <div class="row g-3">
                <div class="col-xxl-12 col-sm-12">
                    <div class="search-box">
                        <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="Cari nama produk...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
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
        </ul>

        <div class="tab-content mt-3">

            <!-- Semua Produk -->
            <div class="tab-pane fade {{ $activeTab === 'semua' ? 'show active' : '' }}" id="tabAll" role="tabpanel">
                <div class="row g-3">
                    @include('livewire.smart-canteen.transaksi-produk.kartu-produk', ['listProduk' => $allProduk])
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

                    @include('livewire.smart-canteen.transaksi-produk.kartu-produk', ['listProduk' => $produkKategori])
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>