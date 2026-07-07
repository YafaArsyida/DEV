<div>
    <div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasDetailTagihan" aria-labelledby="offcanvasDetailTagihanLabel" style="min-height:100vh;">
        <div class="offcanvas-header border-bottom bg-white px-4 py-3 shadow-sm">
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
                            Detail Tagihan Siswa
                        </h5>
                        <small class="text-muted">
                            {{ $namaSiswaCurrent ?? 'Siswa' }}
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
            <div class="row g-4">
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
                                                Detail Tagihan Siswa
                                            </h5>
                                            <small class="text-muted">
                                                Tampilkan siswa dari daftar berdasarkan kategori dan pencarian.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION --}}
                                <div class="d-flex gap-2 flex-wrap">

                                    <button
                                        wire:click="cetakPdfTagihan"
                                        type="button"
                                        class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                        title="Cetak Laporan PDF">
                                        <i class="ri-printer-line"></i>
                                        <span>PDF</span>
                                    </button>

                                    <button
                                        id="exportExcelTagihanDetail"
                                        type="button"
                                        class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                        title="Export Excel">
                                        <i class="ri-file-excel-2-line"></i>
                                        <span>Excel</span>
                                    </button>

                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <!-- Filter Kategori -->
                                <div class="col-lg-2">
                                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                                    Kategori
                                    </label>
                                    <select id="filterKategori" wire:model="selectedKategori" class="form-select" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($select_kategori as $kategori)
                                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">
                                            {{ $kategori->nama_kategori_tagihan_siswa }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Pencarian -->
                                <div class="col-lg-10">
                                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                                    Cari Tagihan
                                    </label>
                                    <div class="search-box">
                                        <input type="text"
                                            id="searchTagihan"
                                            class="form-control"
                                            wire:model.debounce.300ms="search"
                                            placeholder="Cari nama, kategori, atau deskripsi...">
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
                                    <table id="laporanTagihanDetail" class="table table-hover nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr class="text-uppercase" style="white-space: nowrap;">
                                                <th style="width: 50px;">NO</th>
                                                <th>Siswa</th>
                                                <th>Kelas</th>
                                                <th>Jenis Tagihan</th>
                                                <th>Kategori</th>
                                                <th>Cicilan</th>
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
                                                <td style="width: 50px">{{ $loop->iteration }}.</td>
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                    {{ $item->ms_penempatan_siswa->ms_siswa->nama_siswa }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas }}</td>
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                    {{ $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</td>
                                                <td>{{ $item->ms_jenis_tagihan_siswa->cicilan_status }}</td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-info">
                                                    RP{{ number_format($item->jumlah_tagihan_siswa, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-success">
                                                    RP{{ number_format($item->total_bayar ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-danger">
                                                    RP{{ number_format($item->jumlah_tagihan_siswa - ($item->total_bayar ?? 0), 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{
                                                        \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo,
                                                        'd F Y') }}
                                                </td>
                                                <td class="text-center
                                                    {{ $item->status === 'Belum Dibayar' ? 'text-warning' : '' }}
                                                    {{ $item->status === 'Masih Dicicil' ? 'text-info' : '' }}
                                                    {{ $item->status === 'Lunas' ? 'text-success' : '' }}">
                                                    <i class="ri-{{ $item->status === 'Belum Dibayar' ? 'time-line' : ($item->status === 'Masih Dicicil' ? 'money-dollar-circle-line' : 'check-double-line') }} fs-17 align-middle"></i>
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
                                                            yang sesuai.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold text-center">
                                                <td colspan="6" class="text-end">TOTAL</td>
                                                <td class="fs-12 fw-medium text-info">
                                                    Rp{{ number_format($totalEstimasi, 0, ',', '.') }}
                                                </td>
                                                <td class="fs-12 fw-medium text-success">
                                                    Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                                </td>
                                                <td class="fs-12 fw-medium text-danger">
                                                    Rp{{ number_format($totalKekurangan, 0, ',', '.') }}
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
    <script>
        document.getElementById('exportExcelTagihanDetail').addEventListener('click', function () {
            //   console.log("🔵 Tombol export diklik");
            alertify.success("Menyiapkan Dokumen");
        
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("laporanTagihanDetail");
                
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Detail-Tagihan.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>
