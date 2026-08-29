<div id="produkList" class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="row align-items-center g-3">
            {{-- INFORMASI PENGGUNA --}}
            <div class="col-xxl-7 col-md-6">
                @if($nama)
                    <div class="d-flex align-items-center gap-3">

                        {{-- ICON --}}
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                @if($user_type === 'siswa')
                                    <i class="ri-user-3-line"></i>
                                @elseif($user_type === 'pegawai')
                                    <i class="ri-user-settings-line"></i>
                                @else
                                    <i class="ri-user-line"></i>
                                @endif
                            </div>
                        </div>

                        {{-- USER --}}
                        <div class="flex-grow-1 min-w-0">

                            <div class="d-flex align-items-center flex-wrap gap-2">

                                <h5 class="fw-bold text-dark mb-0">
                                    {{ $nama }}
                                </h5>

                                @if($user_type === 'siswa')

                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                        <i class="ri-school-line me-1"></i>
                                        Siswa
                                    </span>

                                @elseif($user_type === 'pegawai')

                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                        <i class="ri-briefcase-line me-1"></i>
                                        {{ $nama_jabatan ?: 'Pegawai' }}
                                    </span>

                                @endif
                                
                                <small class="text-muted">
                                    <i class="ri-bank-card-line me-1"></i>
                                    {{ $educard ?: '-' }}
                                </small>

                            </div>

                            {{-- SMARTCARD --}}
                            <div class="mt-1">
                                <div class="d-flex align-items-center gap-2">

                                    <i class="ri-wallet-3-line text-success fs-18"></i>

                                    <h3 class="fw-bold text-success mb-0">
                                        Rp{{ number_format($saldo_edupay ?? 0, 0, ',', '.') }}
                                    </h3>

                                </div>
                            </div>

                        </div>

                    </div>
                @else
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                <i class="ri-scan-2-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Siap Melakukan Transaksi
                            </h5>

                            <small class="text-muted">
                                Scan SmartCard untuk memulai transaksi.
                            </small>
                        </div>

                    </div>

                @endif
            </div>
            {{-- SCANNER --}}
            <div class="col-xxl-5 col-md-6">
                <div class="border rounded-4 p-3">

                    <div class="d-flex gap-3">

                        {{-- Scan Smartcard --}}
                        <div class="input-group input-group-lg flex-grow-1">
                            <span class="input-group-text bg-primary text-white border-primary">
                                <i class="ri-sensor-fill fs-4"></i>
                            </span>

                            <input type="text" id="inputSmartcard"
                                wire:model.defer="smartcardInput"
                                wire:keydown.enter="prosesSmartcard"
                                class="form-control border-primary"
                                placeholder="Tempelkan kartu atau ketik kode..."
                                autocomplete="off" autofocus>

                            <button type="button" class="btn btn-primary"
                                wire:click="resetScan" title="Reset Scan" aria-label="Reset Scan">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>

                        {{-- Histori --}}
                        <button type="button" class="btn btn-outline-primary btn-lg px-3"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasHistori"
                            aria-controls="offcanvasHistori"
                            wire:click="$emit('openHistori')"
                            title="Histori Transaksi" aria-label="Histori Transaksi">
                            <i class="ri-file-list-3-line fs-4"></i>
                        </button>

                    </div>
                </div>
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