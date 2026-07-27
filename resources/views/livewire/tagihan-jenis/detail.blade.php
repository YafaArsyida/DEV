<div>
    <div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="offcanvasDetailTagihan" aria-labelledby="offcanvasDetailTagihanLabel" style="min-height:100vh;">
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
                            Detail Jenis Tagihan
                        </h5>
                        <small class="text-muted">
                            {{ $nama_tagihan }}
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
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                                {{-- TITLE --}}
                                <div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                                <i class="ri-team-line"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <h5 class="fw-bold mb-1">
                                                Detail Jenis Tagihan
                                            </h5>
                                            <small class="text-muted">
                                                Tampilkan jenis tagihan dari daftar berdasarkan kategori dan pencarian.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION --}}
                                <div class="d-flex gap-2 flex-wrap">

                                    <button
                                        wire:click="DetailPdf"
                                        type="button"
                                        class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                        title="Cetak Laporan PDF">
                                        <i class="ri-printer-line"></i>
                                        <span>PDF</span>
                                    </button>

                                    <button data-bs-toggle="modal" data-bs-target="#ModalDetailTagihan" class="btn rounded-pill px-4 btn-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                            
                                <!-- Filter Kelas -->
                                <div class="col-xl-2 col-md-4 col-sm-6">
                                    <label for="filterKelas" class="form-label">Kelas</label>
                                    <select id="filterKelas" wire:model="selectedKelas" class="form-select" style="cursor:pointer"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                            
                                        <option value="">Semua Kelas</option>
                            
                                        @foreach ($select_kelas as $kelas)
                                        <option value="{{ $kelas->ms_kelas_id }}">
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                        @endforeach
                            
                                    </select>
                                </div>
                            
                                <!-- Pencarian -->
                                <div class="col-xl-10 col-md-8 col-sm-6">
                                    <label for="searchData" class="form-label">Pencarian</label>
                                    <div class="search-box">
                                        <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                                            placeholder="Cari nama siswa, kelas, atau lainnya...">
                            
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="live-preview">
                                <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                                @if (!$selectedJenjang || !$selectedTahunAjar)
                                <div class="text-center py-4">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                                </div>
                                @else
                                <div class="table-responsive">
                                    <table id="DataDetailTagihan" class="table table-hover nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr class="text-uppercase" style="white-space: nowrap;">
                                                <th class="text-center" style="width: 50px;">NO</th>
                                                <th class=>Siswa</th>
                                                <th class=>Kelas</th>
                                                <th class=>Jenis Tagihan</th>
                                                <th class=>Kategori</th>
                                                <th class=>Cicilan</th>
                                                <th class="text-center">Estimasi</th>
                                                <th class="text-center">Dibayarkan</th>
                                                <th class="text-center">Kekurangan</th>
                                                <th class="text-center">Jatuh Tempo</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihans as $item)
                                            <tr style="white-space: nowrap;">
                                                <td class="text-center">{{ $loop->iteration }}. </td>
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                        {{ $item->ms_penempatan_siswa->ms_siswa->nama_siswa }}
                                                    </span>
                                                </td>
                                                
                                                <td>
                                                    {{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas }}
                                                </td>
                                                
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                        {{ $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}
                                                    </span>
                                                </td>
                                                
                                                <td>
                                                    {{ $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}
                                                </td>
                                                
                                                <td>
                                                    {{ $item->ms_jenis_tagihan_siswa->cicilan_status }}
                                                </td>
                                                
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-primary">
                                                        RP{{ number_format($item->jumlah_tagihan_siswa, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-success">
                                                        RP{{ number_format($item->total_bayar ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-danger">
                                                        RP{{ number_format(($item->jumlah_tagihan_siswa - ($item->total_bayar ?? 0)), 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{\App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo, 'd F Y') }}
                                                </td>
                                                <td class="text-center
                                                    {{ $item->status === 'Belum Dibayar' ? 'text-warning' : '' }}
                                                    {{ $item->status === 'Masih Dicicil' ? 'text-primary' : '' }}
                                                    {{ $item->status === 'Lunas' ? 'text-success' : '' }}">
                                                    <i class="ri-{{ $item->status === 'Belum Dibayar' ? 'time-line' : ($item->status === 'Masih Dicicil' ? 'money-dollar-circle-line' : 'checkbox-circle-line') }} fs-17 align-middle"></i>
                                                    {{ $item->status }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="11">
                                                    <div class="noresult text-center py-3">
                                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                            colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                                        </lord-icon>
                                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil
                                                            yang sesuai.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold text-center">
                                                <td colspan="6" class="text-end">TOTAL</td>
                                                <td class="fs-12 fw-medium text-primary">
                                                    RP{{ number_format($totalEstimasi, 0, ',', '.') }}
                                                </td>
                                                <td class="fs-12 fw-medium text-success">
                                                    RP{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                                </td>
                                                <td class="fs-12 fw-medium text-danger">
                                                    RP{{ number_format($totalKekurangan, 0, ',', '.') }}
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="offcanvas">
                            <i class="ri-close-line me-1"></i>
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade zoomIn" id="ModalDetailTagihan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- CLOSE BUTTON --}}
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body px-4 pb-5 pt-2 text-center">
                    {{-- ICON --}}
                    <div class="mb-4">
                        <div class="avatar-xl mx-auto">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json"
                                    trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                                    style="width:70px;height:70px">
                                </lord-icon>
                            </div>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-2">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                            Konfirmasi Export
                        </span>

                        <h3 class="fw-bold mb-2" id="exportRecordLabel">
                            Export Detail Tagihan {{ $nama_tagihan ?? 'Tagihan' }}?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Data yang diekspor akan mengikuti filter dan tabel yang
                            sedang ditampilkan, sehingga hasil export sesuai dengan
                            data yang Anda lihat saat ini.
                        </p>

                    </div>

                    {{-- INFORMATION --}}
                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-primary fs-20"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Informasi
                                </h6>
                                <p class="text-muted mb-0 fs-13">
                                    Pastikan filter dan pencarian sudah sesuai sebelum melakukan export.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Batal
                    </button>

                    <button type="button" class="btn btn-primary rounded-pill px-4"
                        id="konfirmasiExporDetail" 
                        data-tagihan="{{ $nama_tagihan ?? 'Tagihan' }}"
                        data-bs-dismiss="modal">
                        <i class="ri-download-2-line me-1"></i>
                        Ya, Export
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExporDetail').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            const namaTagihan = this.dataset.tagihan;

            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("DataDetailTagihan"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(
                    workbook,
                    `Administrasi-Tagihan-${namaTagihan}-{{ date('Y-m-d') }}.xlsx`
                );
            }, 1000); // 1000 ms = 1 detik
        });

    </script>
</div>
