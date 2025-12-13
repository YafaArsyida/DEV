{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div class="card-header border-0">
    <div class="row g-4 align-items-center">
        <div class="col-xxl-12 col-sm-12">
            <div wire:ignore.self class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="filterEduPay" aria-labelledby="filterEduPayLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title" id="filterEduPayLabel">Filter</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Petugas</p>
                        <select id="PilihPetugas" style="cursor: pointer" wire:model="selectedPetugas" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas" multiple="multiple">
                            @foreach ($select_petugas as $item)    
                            <option value="{{ $item->ms_pengguna_id }}">{{ $item->nama }} - <i>{{ $item->peran }}</i> </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Jenis Transaksi</p>
                        <select id="PilihJenisTransaksi" style="cursor: pointer" wire:model="selectedJenisTransaksi" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenis Transaksi" multiple="multiple" >
                            <option value="topup tunai">Topup Tunai</option>
                            <option value="topup online">Topup Online</option>
                            <option value="penarikan">Penarikan Tunai</option>
                            <option value="pembayaran">Pembayaran Tagihan</option>
                            <option value="pengembalian dana">Pengembalian Dana</option>
                            <option value="kantin">Kantin</option>
                        </select>
                    </div>
                </div>
                <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
                    <button id="ClearFilter" class="btn btn-light w-100" data-bs-dismiss="offcanvas">Clear Filter</button>
                    <button id="ApplyFilter" class="btn btn-primary w-100" data-bs-dismiss="offcanvas">Filters</button>
                </div>
            </div>
        </div>
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
            $('#PilihJenisTransaksi').val(null).trigger('change');

            // Emit event ke Livewire untuk clear filter
            Livewire.emit("clearFilters");
            alertify.success("Memperbarui...");
        });

        // Fungsi untuk mengirim data filter hanya didaftarkan sekali
        document.getElementById("ApplyFilter").addEventListener("click", function () {
            const filters = {
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
</div>
