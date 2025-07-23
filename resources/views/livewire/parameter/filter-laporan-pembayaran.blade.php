{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div class="card-header border-0">
    <div class="row g-4 align-items-center">
        <div class="col-xxl-12 col-sm-12">
            <div wire:ignore.self class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="filterPembayaran" aria-labelledby="filterTabunganLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title" id="filterTabunganLabel">Filter</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Tanggal Transaksi</p>
                        <div class="row g-2 align-items-center">
                            <div class="col-lg">
                                <input type="date" class="form-control" id="startDate" placeholder="0">
                            </div>
                            <div class="col-lg-auto">-</div>
                            <div class="col-lg">
                                <input type="date" class="form-control" id="endDate" placeholder="0">
                            </div>
                        </div>
                    </div>
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
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Petugas</p>
                        <select id="PilihPetugas" style="cursor: pointer" wire:model="selectedPetugas" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas" multiple="multiple">
                            @foreach ($select_petugas as $item)    
                            <option value="{{ $item->ms_pengguna_id }}">{{ $item->nama }} - <i>{{ $item->peran }}</i> </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Kategori Tagihan</p>
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

                    <div class="mb-4">
                        <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Metode Pembayaran</p>
                        <select id="PilihMetodePembayaran" style="cursor: pointer" wire:model="selectedMetode" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Metode Pembayaran" multiple="multiple" >
                            <option value="Teller Tunai">Tunai</option>
                            <option value="BNI">BNI</option>
                            <option value="BRI">BRI</option>
                            <option value="BCA">BCA</option>
                            <option value="EduPay">EduPay</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
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
        </div>
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

            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";

            // Emit event ke Livewire untuk clear filter
            Livewire.emit("clearFilters");
            alertify.success("Memperbarui...");
        });

        // Fungsi untuk mengirim data filter hanya didaftarkan sekali
        document.getElementById("ApplyFilter").addEventListener("click", function () {
            const startDate = document.getElementById("startDate").value;
            const endDate = document.getElementById("endDate").value;

            const filters = {
                startDate: startDate,
                endDate: endDate,
                selectedKelas: $("#PilihKelas").val(),
                selectedPetugas: $("#PilihPetugas").val(),
                selectedKategoriTagihanSiswa: $("#PilihKategoriTagihan").val(),
                selectedJenisTagihanSiswa: $("#PilihJenisTagihan").val(),
                selectedMetode: $("#PilihMetodePembayaran").val(),
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
