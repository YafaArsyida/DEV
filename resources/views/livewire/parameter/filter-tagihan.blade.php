<div wire:ignore.self class="offcanvas offcanvas-end bg-light" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="filterTagihan" aria-labelledby="filterTabunganLabel">
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
                        Filter laporan piutang siswa
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
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body">

                {{-- Tanggal Jatuh Tempo --}}
                {{-- <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label text-uppercase fw-semibold mb-0">
                            Tanggal Jatuh Tempo
                        </label>
                        <i class="mdi mdi-information-outline fs-16 text-primary"
                            style="cursor: pointer;"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Pilih tanggal jatuh tempo untuk menampilkan piutang sampai tempo akhir.">
                        </i>
                    </div>

                    <input type="date"
                        class="form-control"
                        id="endDate"
                        wire:model="endDate">
                </div> --}}

                {{-- Kategori Tagihan --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label text-uppercase fw-semibold mb-0">
                            Kategori Tagihan
                        </label>

                        <i class="mdi mdi-information-outline fs-16 text-primary"
                            style="cursor: pointer;"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Pilih kategori tagihan terlebih dahulu. Jenis tagihan akan tampil otomatis sesuai kategori yang dipilih.">
                        </i>
                    </div>

                    <select id="PilihKategoriTagihan"
                        class="form-select"
                        style="cursor:pointer"
                        wire:model="selectedKategoriTagihan"
                        multiple="multiple">

                        @foreach ($select_kategori_tagihan as $item)
                            <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">
                                {{ $item->nama_kategori_tagihan_siswa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Tagihan --}}
                @if ($showJenisTagihan)
                    <div class="mb-0">
                        <label class="form-label text-uppercase fw-semibold mb-2">
                            Jenis Tagihan
                        </label>

                        <select id="PilihJenisTagihan"
                            class="form-select"
                            style="cursor:pointer"
                            wire:model="selectedJenisTagihan"
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

            </div>
        </div>
    </div>

    <div class="offcanvas-footer border-top bg-light-subtle p-3">
        <div class="d-flex gap-2">
            <button id="ClearFilter"
                class="btn btn-light rounded-pill w-100"
                data-bs-dismiss="offcanvas">
                <i class="ri-refresh-line me-1"></i>
                Clear Filter
            </button>

            <button id="ApplyFilter"
                class="btn btn-primary rounded-pill w-100">
                <i class="ri-filter-3-line me-1"></i>
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
                {{ count($selectedJenisTagihan) > 0 ? implode(', ', $selectedJenisTagihan) : 'Tidak ada jenis tagihan yang dipilih' }}
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
        // $('#PilihKelas').select2();
        $('#PilihKategoriTagihan').select2(); // Terapkan Select2 pada elemen ini
        $('#PilihJenisTagihan').select2(); // Terapkan Select2 pada elemen ini
    }

    // Clear filter hanya didaftarkan sekali
    document.getElementById("ClearFilter").addEventListener("click", function () {
        // Reset semua select dan input ke nilai default
        // $('#PilihKelas').val(null).trigger('change');
        $('#PilihKategoriTagihan').val(null).trigger('change');
        $('#PilihJenisTagihan').val(null).trigger('change');

        // document.getElementById('endDate').value = "";

        // Emit event ke Livewire untuk clear filter
        Livewire.emit("clearFilters");
        alertify.success("Memperbarui...");
    });

    // Fungsi untuk mengirim data filter hanya didaftarkan sekali
    document.getElementById("ApplyFilter").addEventListener("click", function () {
        // const endDate = document.getElementById("endDate").value;

        const filters = {
            // endDate: endDate,
            // selectedKelas: $("#PilihKelas").val(),
            selectedKategoriTagihan: $("#PilihKategoriTagihan").val(),
            selectedJenisTagihan: $("#PilihJenisTagihan").val(),
        };

        // Emit filters ke Livewire
        Livewire.emit("applyFilters", filters);

        // Tampilkan notifikasi sukses menggunakan alertify
        alertify.success("Memperbarui...");
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