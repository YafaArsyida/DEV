<div wire:ignore.self class="offcanvas offcanvas-end bg-light" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="filterTabungan" aria-labelledby="filterTabunganLabel">
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
                        Filter laporan tabungan
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
                        <div class="mb-4">
                            <p class="text-uppercase fw-semibold mb-2">Petugas</p>
                            <select id="PilihPetugas" style="cursor: pointer" wire:model="selectedPetugas" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Petugas" multiple="multiple">
                                @foreach ($select_petugas as $item)    
                                <option value="{{ $item->ms_pengguna_id }}">{{ $item->nama }} - <i>{{ $item->peran }}</i> </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <p class="text-uppercase fw-semibold mb-2">Jenis Transaksi</p>
                            <select id="PilihJenisTransaksi" style="cursor: pointer" wire:model="selectedJenisTransaksi" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenis Transaksi" multiple="multiple" >
                                <option value="setoran">Setoran</option>
                                <option value="penarikan">Penarikan</option>
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
            <p><strong>Selected Petugas:</strong> 
                {{ count($selectedPetugas) > 0 ? implode(', ', $selectedPetugas) : 'Tidak ada petugas yang dipilih' }}
            </p>
        </div>
    </div> --}}
</div>

<script>
    // Select2 handler
    function initSelect2() {
        $('#PilihPetugas').select2(); // Terapkan Select2 pada elemen ini
        $('#PilihJenisTransaksi').select2(); // Terapkan Select2 pada elemen ini
    }

    // Clear filter hanya didaftarkan sekali
    document.getElementById("ClearFilter").addEventListener("click", function () {
        $('#PilihPetugas').val(null).trigger('change');

        // document.getElementById('startDate').value = "";
        // document.getElementById('endDate').value = "";

        // Emit event ke Livewire untuk clear filter
        Livewire.emit("clearFilters");
        alertify.success("Memperbarui...");
    });

    // Fungsi untuk mengirim data filter hanya didaftarkan sekali
    document.getElementById("ApplyFilter").addEventListener("click", function () {
        // const startDate = document.getElementById("startDate").value;
        // const endDate = document.getElementById("endDate").value;

        const filters = {
            // startDate: startDate,
            // endDate: endDate,
            selectedPetugas: $("#PilihPetugas").val(),
            selectedJenisTransaksi: $("#PilihJenisTransaksi").val(),
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