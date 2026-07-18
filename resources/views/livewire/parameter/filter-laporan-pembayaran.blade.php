<div wire:ignore.self class="offcanvas offcanvas-end bg-light" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="filterPembayaran" aria-labelledby="filterTabunganLabel">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-chart-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Filter
                    </h5>
                    <small class="text-muted">
                        Filter laporan pembayaran
                    </small>
                </div>
            </div>
            <!-- Kanan -->
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body">

                        {{-- KELAS --}}
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-semibold mb-2">
                                Kelas
                            </label>
                            <select id="PilihKelas"
                                    class="form-select"
                                    style="cursor:pointer"
                                    wire:model="selectedKelas"
                                    multiple="multiple">
                                @foreach ($select_kelas as $item)
                                    <option value="{{ $item->ms_kelas_id }}">
                                        {{ $item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PETUGAS --}}
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-semibold mb-2">
                                Petugas
                            </label>
                            <select id="PilihPetugas"
                                    class="form-select"
                                    style="cursor:pointer"
                                    wire:model="selectedPetugas"
                                    multiple="multiple">
                                @foreach ($select_petugas as $item)
                                    <option value="{{ $item->ms_pengguna_id }}">
                                        {{ $item->nama }} - {{ $item->peran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KATEGORI TAGIHAN --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label text-uppercase fw-semibold mb-0">
                                    Kategori Tagihan
                                </label>

                                <i class="mdi mdi-information-outline fs-16 text-primary"
                                style="cursor:pointer"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Pilih kategori tagihan terlebih dahulu. Jenis tagihan akan tampil otomatis sesuai kategori yang dipilih.">
                                </i>
                            </div>

                            <select id="PilihKategoriTagihan"
                                    class="form-select"
                                    style="cursor:pointer"
                                    wire:model="selectedKategoriTagihanSiswa"
                                    multiple="multiple">
                                @foreach ($select_kategori_tagihan as $item)
                                    <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">
                                        {{ $item->nama_kategori_tagihan_siswa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- JENIS TAGIHAN --}}
                        @if ($showJenisTagihan)
                            <div class="mb-4">
                                <label class="form-label text-uppercase fw-semibold mb-2">
                                    Jenis Tagihan
                                </label>

                                <select id="PilihJenisTagihan"
                                        class="form-select"
                                        style="cursor:pointer"
                                        wire:model="selectedJenisTagihanSiswa"
                                        multiple="multiple">
                                    @foreach ($select_jenis_tagihan as $item)
                                        <option value="{{ $item->ms_jenis_tagihan_siswa_id }}">
                                            {{ $item->nama_jenis_tagihan_siswa }}
                                            -
                                            {{ $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- METODE PEMBAYARAN --}}
                        <div class="mb-0">
                            <label class="form-label text-uppercase fw-semibold mb-2">
                                Metode Pembayaran
                            </label>

                            <select id="PilihMetodePembayaran"
                                    class="form-select"
                                    style="cursor:pointer"
                                    wire:model="selectedMetode"
                                    multiple="multiple">
                                <option value="Teller Tunai">Teller Tunai</option>
                                <option value="Transfer ke Rekening Sekolah">
                                    Transfer ke Rekening Sekolah
                                </option>
                                <option value="EduPay">EduPay</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas-footer border-top p-3 bg-light-subtle">
        <div class="hstack gap-2">
            <button id="ClearFilter"
                    class="btn btn-light rounded-pill w-100"
                    data-bs-dismiss="offcanvas">
                <i class="ri-refresh-line align-bottom me-1"></i>
                Clear Filter
            </button>

            <button id="ApplyFilter"
                    class="btn btn-primary rounded-pill w-100">
                <i class="ri-filter-3-line align-bottom me-1"></i>
                Terapkan Filter
            </button>
        </div>
    </div>
                
    {{-- <div class="card mt-3">
        <div class="card-header">
            <h5>Debugging Filters</h5>
        </div>
        <div class="card-body">
            <p><strong>Start Date:</strong> {{ $startDate ?? 'Tidak ada' }}</p>
            <p><strong>End Date:</strong> {{ $endDate ?? 'Tidak ada' }}</p>
            <p><strong>Selected Kelas:</strong> 
                {{ count($selectedKelas) > 0 ? implode(', ', $selectedKelas) : 'Tidak ada kelas yang dipilih' }}
            </p>
            <p><strong>Selected Petugas:</strong> 
                {{ count($selectedPetugas) > 0 ? implode(', ', $selectedPetugas) : 'Tidak ada petugas yang dipilih' }}
            </p>
            <p><strong>Selected Jenis Tagihan:</strong> 
                {{ count($selectedJenisTagihanSiswa) > 0 ? implode(', ', $selectedJenisTagihanSiswa) : 'Tidak ada jenis tagihan yang dipilih' }}
            </p>
            <p><strong>Selected Metode Pembayaran:</strong> 
                {{ count($selectedMetode) > 0 ? implode(', ', $selectedMetode) : 'Tidak ada metode pembayaran yang dipilih' }}
            </p>
        </div>
    </div> --}}

</div>
<script>
    // Select2 handler
    function initSelect2() {
        $('#PilihKelas').select2(); // Terapkan Select2 pada elemen ini
        $('#PilihPetugas').select2(); // Terapkan Select2 pada elemen ini
        $('#PilihKategoriTagihan').select2(); // Terapkan Select2 pada elemen ini
        $('#PilihJenisTagihan').select2(); // Terapkan Select2 pada elemen ini
        $('#PilihMetodePembayaran').select2(); // Terapkan Select2 pada elemen ini
    }

    // Clear filter hanya didaftarkan sekali
    document.getElementById("ClearFilter").addEventListener("click", function () {
        // Reset semua select dan input ke nilai default
        $('#PilihKelas').val(null).trigger('change');
        $('#PilihPetugas').val(null).trigger('change');
        $('#PilihKategoriTagihan').val(null).trigger('change');
        $('#PilihJenisTagihan').val(null).trigger('change');
        $('#PilihMetodePembayaran').val(null).trigger('change');

        // Emit event ke Livewire untuk clear filter
        Livewire.emit("clearFilters");
    });

    // Fungsi untuk mengirim data filter hanya didaftarkan sekali
    document.getElementById("ApplyFilter").addEventListener("click", function () {
        const filters = {
            selectedKelas: $("#PilihKelas").val(),
            selectedPetugas: $("#PilihPetugas").val(),
            selectedKategoriTagihanSiswa: $("#PilihKategoriTagihan").val(),
            selectedJenisTagihanSiswa: $("#PilihJenisTagihan").val(),
            selectedMetode: $("#PilihMetodePembayaran").val(),
        };

        // Emit filters ke Livewire
        Livewire.emit("applyFilters", filters);
    });

    // Inisialisasi Select2 dan hook Livewire
    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi awal Select2
        initSelect2();

        // Re-inisialisasi Select2 setiap kali Livewire memperbarui DOM tanpa mendaftarkan listener ulang
        Livewire.hook('message.processed', (message, component) => {
            initSelect2(); // Pastikan Select2 tetap bekerja setelah Livewire update
        });
    });

</script>