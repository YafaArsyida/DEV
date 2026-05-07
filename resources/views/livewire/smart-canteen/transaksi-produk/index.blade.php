 <div class="" id="produkList">
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-4">

            <div class="row g-4 align-items-center">
                <!-- ============================ -->
                <!-- KIRI — DATA PENGGUNA -->
                <!-- ============================ -->
                <div class="col-md-7">

                    @if($nama)

                        <h4 class="fw-bold text-dark mb-1">
                            {{ $nama }}
                            <span class="text-muted">—</span>

                            @if($user_type === 'siswa')
                                <span class="text-primary">{{ $nama_kelas ?: 'Belum ada kelas' }}</span>
                            @elseif($user_type === 'pegawai')
                                <span class="text-primary">{{ $nama_jabatan ?: 'Belum ada jabatan' }}</span>
                            @else
                                <span class="text-primary">Belum ada informasi</span>
                            @endif
                        </h4>

                        <div class="text-muted mb-2">
                            SmartCard :
                            <span class="fw-semibold text-primary">{{ $educard ?: '-' }}</span>
                        </div>

                        <div class="mt-1">
                            {{-- <div class="text-muted small">Saldo EduPay</div> --}}
                            <h3 class="fw-bold text-success mb-0">
                                RP{{ number_format($saldo_edupay ?? 0, 0, ',', '.') }}
                            </h3>
                        </div>

                    @else

                        <div class="text-muted">
                            <h5 class="mb-0">Scan SmartCard untuk transaksi</h5>
                        </div>

                    @endif

                </div>

                <!-- ============================ -->
                <!-- KANAN — INPUT SCAN -->
                <!-- ============================ -->
                <div class="col-md-5">

                    <label class="fw-semibold mb-1">Scan / Input SmartCard</label>

                    <div class="input-group input-group-lg shadow-sm">

                        <span class="input-group-text bg-primary text-white border-primary">
                            <i class="ri-sensor-fill fs-4"></i>
                        </span>

                        <input type="text"
                            id="inputSmartcard"
                            wire:model.defer="smartcardInput"
                            wire:keydown.enter="prosesSmartcard"
                            class="form-control border-primary"
                            placeholder="Tempelkan SmartCard atau ketik kode..."
                            autofocus>
                    </div>

                </div>

            </div>

        </div>
    </div>


    <!-- Search & Filter -->
    {{-- <div class="card-body border-end-0 border-start-0">
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
    </div> --}}

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
    <script>
        document.addEventListener('focus-smartcard-input', () => {
            const el = document.getElementById('inputSmartcard');
            if (el) el.focus();
        });
    </script>

</div>