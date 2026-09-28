<div wire:ignore.self
     class="offcanvas offcanvas-end bg-light"
     data-bs-scroll="true"
     data-bs-backdrop="false"
     tabindex="-1"
     id="filterRekapitulasi"
     aria-labelledby="filterRekapitulasiLabel">

    {{-- HEADER --}}
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">

            {{-- Kiri --}}
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-chart-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1" id="filterRekapitulasiLabel">
                        Filter
                    </h5>
                    <small class="text-muted">
                        Filter data rekapitulasi tagihan
                    </small>
                </div>
            </div>

            {{-- Kanan --}}
            <button
                type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas"
                aria-label="Close">

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

                        {{-- KELAS --}}
                        <div class="mb-4">
                            <p class="text-uppercase fw-semibold mb-2">
                                Kelas
                            </p>

                            <select
                                id="PilihKelas"
                                wire:model="selectedKelas"
                                class="form-select"
                                style="cursor:pointer"
                                multiple="multiple"
                                title="Pilih Kelas">

                                @foreach ($select_kelas as $item)
                                    <option value="{{ $item->ms_kelas_id }}">
                                        {{ $item->nama_kelas }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- KATEGORI TAGIHAN --}}
                        <div class="mb-4">

                            <div class="d-flex align-items-center mb-2">
                                <p class="text-uppercase fw-semibold mb-0 me-2">
                                    Kategori Tagihan
                                </p>

                                <i
                                    class="mdi mdi-information-outline fs-14 text-primary"
                                    style="cursor:pointer"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Pilih Kategori Tagihan terlebih dahulu. Jenis Tagihan akan tampil otomatis menyesuaikan kategori yang Anda pilih.">
                                </i>
                            </div>

                            <select
                                id="PilihKategoriTagihan"
                                wire:model="selectedKategoriTagihanSiswa"
                                class="form-select"
                                style="cursor:pointer"
                                multiple="multiple"
                                title="Pilih Kategori">

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
                                <p class="text-uppercase fw-semibold mb-2">
                                    Jenis Tagihan
                                </p>

                                <select
                                    id="PilihJenisTagihan"
                                    wire:model="selectedJenisTagihanSiswa"
                                    class="form-select"
                                    style="cursor:pointer"
                                    multiple="multiple"
                                    title="Pilih Tagihan">

                                    @foreach ($select_jenis_tagihan as $item)
                                        <option value="{{ $item->ms_jenis_tagihan_siswa_id }}">
                                            {{ $item->nama_jenis_tagihan_siswa }}
                                            - {{ $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                        @endif

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
        $('#PilihKelas').select2();
        $('#PilihKategoriTagihan').select2();
        $('#PilihJenisTagihan').select2();
    }

    // Clear filter
    document.getElementById("ClearFilter").addEventListener("click", function () {

        $('#PilihKelas').val(null).trigger('change');
        $('#PilihKategoriTagihan').val(null).trigger('change');
        $('#PilihJenisTagihan').val(null).trigger('change');

        Livewire.emit("clearFilters");

        alertify.success("Memperbarui...");
    });

    // Apply filter
    document.getElementById("ApplyFilter").addEventListener("click", function () {

        const filters = {
            selectedKelas: $("#PilihKelas").val(),
            selectedKategoriTagihanSiswa: $("#PilihKategoriTagihan").val(),
            selectedJenisTagihanSiswa: $("#PilihJenisTagihan").val(),
        };

        Livewire.emit("applyFilters", filters);

        alertify.success("Memperbarui...");
    });

    // Inisialisasi Select2
    document.addEventListener("DOMContentLoaded", function () {

        initSelect2();

        Livewire.hook('message.processed', (message, component) => {
            initSelect2();
        });

    });
</script>