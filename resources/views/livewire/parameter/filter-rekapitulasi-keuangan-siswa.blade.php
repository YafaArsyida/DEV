{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div class="card-header border-0">
    <div class="row g-4 align-items-center">
        <div class="col-xxl-12 col-sm-12">
            <div wire:ignore.self class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="filterRekapitulasi" aria-labelledby="filterTabunganLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title" id="filterTabunganLabel">Filter</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Kelas</p>
                        <select id="PilihKelas" style="cursor: pointer" wire:model="selectedKelas" class="form-select" multiple="multiple">
                            @foreach ($select_kelas as $item)
                                <option value="{{ $item->ms_kelas_id }}">
                                    {{ $item->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <p class="text-muted text-uppercase fs-12 fw-medium mb-0 me-1">Kategori Tagihan</p>
                            <i class="mdi mdi-information-outline fs-14 text-primary" 
                            style="cursor: pointer;" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="Pilih Kategori tagihan terlebih dahulu. Jenis tagihan akan tampil otomatis menyesuaikan dengan kategori yang Anda pilih.">
                            </i>
                        </div>
                        <select id="PilihKategoriTagihan" style="cursor: pointer" wire:model="selectedKategoriTagihanSiswa" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kategori"  multiple="multiple">
                            {{-- <option value="">Semua Kategori</option> --}}
                            @foreach ($select_kategori_tagihan as $item)    
                            <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">{{ $item->nama_kategori_tagihan_siswa }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($showJenisTagihan)
                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Jenis Tagihan</p>
                        <select id="PilihJenisTagihan" style="cursor: pointer" wire:model="selectedJenisTagihanSiswa" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Tagihan" multiple="multiple">
                            @foreach ($select_jenis_tagihan as $item)    
                            <option value="{{ $item->ms_jenis_tagihan_siswa_id }}">
                                {{ $item->nama_jenis_tagihan_siswa }} - <i>{{ $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</i>
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
                    <button id="ClearFilter" class="btn btn-light w-100" data-bs-dismiss="offcanvas">Clear Filter</button>
                    <button id="ApplyFilter" class="btn btn-primary w-100" data-bs-dismiss="offcanvas">Filters</button>
                </div>
                            
                {{-- <div class="card mt-3">
                    <div class="card-header">
                        <h5>Debugging Filters</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Selected Kelas:</strong> 
                            {{ count($selectedKelas) > 0 ? implode(', ', $selectedKelas) : 'Tidak ada kelas yang dipilih' }}
                        </p>
                        <p><strong>Selected Kategori:</strong> 
                            {{ count($selectedKategoriTagihanSiswa) > 0 ? implode(', ', $selectedKategoriTagihanSiswa) : 'Tidak kategori tagihan yang dipilih' }}
                        </p>
                        <p><strong>Selected Jenis Tagihan:</strong> 
                            {{ count($selectedJenisTagihanSiswa) > 0 ? implode(', ', $selectedJenisTagihanSiswa) : 'Tidak ada jenis tagihan yang dipilih' }}
                        </p>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
    
    <script>
        // Select2 handler
        function initSelect2() {
            $('#PilihKelas').select2(); // Terapkan Select2 pada elemen ini
            $('#PilihKategoriTagihan').select2(); // Terapkan Select2 pada elemen ini
            $('#PilihJenisTagihan').select2(); // Terapkan Select2 pada elemen ini
        }

        // Clear filter hanya didaftarkan sekali
        document.getElementById("ClearFilter").addEventListener("click", function () {
            // Reset semua select dan input ke nilai default
            $('#PilihKelas').val(null).trigger('change');
            $('#PilihKategoriTagihan').val(null).trigger('change');
            $('#PilihJenisTagihan').val(null).trigger('change');

            // Emit event ke Livewire untuk clear filter
            Livewire.emit("clearFilters");
            alertify.success("Memperbarui...");
        });

        // Fungsi untuk mengirim data filter hanya didaftarkan sekali
        document.getElementById("ApplyFilter").addEventListener("click", function () {
            const filters = {
                selectedKelas: $("#PilihKelas").val(),
                selectedKategoriTagihanSiswa: $("#PilihKategoriTagihan").val(),
                selectedJenisTagihanSiswa: $("#PilihJenisTagihan").val(),
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
