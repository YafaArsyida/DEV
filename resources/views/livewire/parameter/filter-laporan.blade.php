{{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
<div class="card">
    <div class="card-header border-0">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="fs-16">Filter</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
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
            <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Jenis Tagihan</p>
            <select id="PilihJenisTagihan" style="cursor: pointer" wire:model="selectedJenisTagihan" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Tagihan" multiple="multiple">
                @foreach ($select_jenis_tagihan as $item)    
                <option value="{{ $item->ms_jenis_tagihan_id }}">
                    {{ $item->nama_jenis_tagihan }} - <i>{{ $item->ms_kategori_tagihan->nama_kategori_tagihan }}</i>
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Metode Pembayaran</p>
            <select id="PilihMetodePembayaran" style="cursor: pointer" wire:model="selectedMetode" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Metode Pembayaran" multiple="multiple" >
                <option value="tunai">Tunai</option>
                <option value="bni">BNI</option>
                <option value="bri">BRI</option>
                <option value="bca">BCA</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
    </div>
    <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
        <button id="ClearFilter" class="btn btn-light w-100">Clear Filter</button>
        <button id="ApplyFilter" class="btn btn-primary w-100">Filters</button>
    </div>


    <div class="card mt-3">
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
    </div>

    <script>
        // Select2 handler
        function initSelect2() {
            $('#PilihKelas').select2(); // Terapkan Select2 pada elemen ini
            $('#PilihPetugas').select2(); // Terapkan Select2 pada elemen ini
            $('#PilihJenisTagihan').select2(); // Terapkan Select2 pada elemen ini
            $('#PilihMetodePembayaran').select2(); // Terapkan Select2 pada elemen ini
        }

        // Clear filter hanya didaftarkan sekali
        document.getElementById("ClearFilter").addEventListener("click", function () {
            // Reset semua select dan input ke nilai default
            $('#PilihKelas').val(null).trigger('change');
            $('#PilihPetugas').val(null).trigger('change');
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
                selectedJenisTagihan: $("#PilihJenisTagihan").val(),
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
<!-- end card -->
