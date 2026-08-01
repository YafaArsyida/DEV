{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div wire:ignore.self
     class="offcanvas offcanvas-end bg-light"
     data-bs-scroll="true"
     data-bs-backdrop="false"
     tabindex="-1"
     id="filterEduPay"
     aria-labelledby="filterEduPayLabel">

    {{-- HEADER --}}
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">

            {{-- Kiri --}}
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-wallet-3-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Filter
                    </h5>
                    <small class="text-muted">
                        Filter laporan EduPay
                    </small>
                </div>
            </div>

            {{-- Kanan --}}
            <button
                type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">

                <i class="ri-close-line fs-18"></i>
            </button>

        </div>
    </div>

    {{-- BODY --}}
    <div class="offcanvas-body">

        <div class="row g-3 mb-3">
            <div class="col-12">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body">

                        <div class="mb-4">
                            <p class="text-uppercase fw-semibold mb-2">
                                Petugas
                            </p>

                            <select
                                id="PilihPetugas"
                                wire:model="selectedPetugas"
                                class="form-select"
                                style="cursor:pointer"
                                multiple="multiple"
                                data-bs-toggle="tooltip"
                                title="Pilih Petugas">

                                @foreach ($select_petugas as $item)
                                    <option value="{{ $item->ms_pengguna_id }}">
                                        {{ $item->nama }} - {{ $item->peran }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-4">
                            <p class="text-uppercase fw-semibold mb-2">
                                Jenis Transaksi
                            </p>

                            <select
                                id="PilihJenisTransaksi"
                                wire:model="selectedJenisTransaksi"
                                class="form-select"
                                style="cursor:pointer"
                                multiple="multiple"
                                data-bs-toggle="tooltip"
                                title="Pilih Jenis Transaksi">

                                <option value="topup tunai">Top Up Tunai</option>
                                <option value="topup online">Top Up Online</option>
                                <option value="penarikan">Penarikan Tunai</option>
                                <option value="pembayaran">Pembayaran Tagihan</option>
                                <option value="pengembalian dana">Pengembalian Dana</option>
                                <option value="kantin">Kantin</option>

                            </select>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="offcanvas-footer border-top p-3 bg-light-subtle">
        <div class="hstack gap-2">

            <button
                id="ClearFilter"
                class="btn btn-light rounded-pill w-100"
                data-bs-dismiss="offcanvas">

                <i class="ri-refresh-line me-1"></i>
                Clear Filter
            </button>

            <button
                id="ApplyFilter"
                class="btn btn-primary rounded-pill w-100"
                data-bs-dismiss="offcanvas">

                <i class="ri-filter-3-line me-1"></i>
                Terapkan Filter
            </button>

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