{{-- Stop trying to control. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">
                    @if ($jenisRekapitulasi === 'tagihan')
                    Rekapitulasi Tagihan Siswa
                    @elseif ($jenisRekapitulasi === 'pembayaran')
                    Rekapitulasi Pembayaran Tagihan Siswa
                    @elseif ($jenisRekapitulasi === 'kekurangan')
                    Rekapitulasi Kekurangan Tagihan Siswa
                    @else
                    Rekapitulasi Keuangan Siswa
                    @endif
                </h5>                
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-4">
            <!-- Jenis Rekapitulasi (paling kiri) -->
            <div class="col-xxl-2 col-md-3">
                <label for="rekapSelect" class="form-label">Jenis Rekapitulasi</label>
                <select id="rekapSelect" wire:model="jenisRekapitulasi" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenis Rekapitulasi">
                    <option value="tagihan">Rekapitulasi Tagihan</option>
                    <option value="pembayaran">Rekapitulasi Pembayaran</option>
                    <option value="kekurangan">Rekapitulasi Kekurangan</option>
                </select>
            </div>

            <!-- Pencarian (tengah) -->
            <div class="col-xxl-8 col-md-6">
                <label for="searchRekap" class="form-label">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchRekap" class="form-control ps-4" wire:model.debounce.300ms="search" placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>

            <!-- Pilih Kelas (paling kanan) -->
            <div class="col-xxl-2 col-md-3">
                <label for="kelasSelect" class="form-label">Kelas</label>
                <select id="kelasSelect" wire:model="selectedKelas" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)    
                        <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            @if (!$selectedJenjang || !$selectedTahunAjar)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-nowrap align-middle" style="width:100%">
                    <tr class="table-active">
                        <th class="text-uppercase">No</th>
                        <th style="white-space: nowrap;" class="text-uppercase">Siswa</th>
                        <th style="white-space: nowrap;" class="text-uppercase">Kelas</th>
                        @foreach($jenisTagihan as $jenis)
                            <th style="white-space: nowrap;" class="">{{ $jenis->nama_jenis_tagihan_siswa }}</th>
                        @endforeach
                        <th class="text-uppercase">Total</th>
                    </tr>
                    <tbody>
                        @forelse ($siswas as $key => $item)
                            <tr>
                                {{-- <td>{{ $siswas->firstItem() + $key }}.</td> --}}
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $item->ms_siswa->nama_siswa }}</td>
                                <td>{{ $item->ms_kelas->nama_kelas }}</td>
                                @foreach ($jenisTagihan as $jenis)
                                    <td>
                                        @php
                                            $tagihanItem = $item->ms_tagihan_siswa->where('ms_jenis_tagihan_siswa_id', $jenis->ms_jenis_tagihan_siswa_id)->first();
                                            $value = null;

                                            if ($tagihanItem) {
                                                if ($jenisRekapitulasi === 'tagihan') {
                                                    $value = $tagihanItem->jumlah_tagihan_siswa;
                                                } elseif ($jenisRekapitulasi === 'pembayaran') {
                                                    $value = $tagihanItem->jumlah_sudah_dibayar();
                                                } elseif ($jenisRekapitulasi === 'kekurangan') {
                                                    $value = $tagihanItem->jumlah_kekurangan();
                                                }
                                            }
                                        @endphp

                                        {{ $value !== null ? 'RP' . number_format($value, 0, ',', '.') : '-' }}
                                    </td>
                                @endforeach
                                <td>
                                    @php
                                        $total = 0;

                                        if ($jenisRekapitulasi === 'tagihan') {
                                            $total = $item->total_tagihan_siswa();
                                        } elseif ($jenisRekapitulasi === 'pembayaran') {
                                            $total = $item->total_dibayarkan();
                                        } elseif ($jenisRekapitulasi === 'kekurangan') {
                                            $total = $item->total_kekurangan();
                                        }
                                    @endphp

                                    RP{{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 3 + count($jenisTagihan) }}"> <!-- Tambahkan jumlah kolom dinamis -->
                                    <div class="noresult text-center py-3">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#08a88a"
                                            style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-uppercase">TOTAL</td>
                            @foreach ($jenisTagihan as $jenis)
                                <td>
                                    RP{{ number_format($this->total[$jenis->ms_jenis_tagihan_siswa_id] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                            <td>
                                RP{{ number_format($this->grandTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>                    
                </table>
                {{-- {{ $siswas->links() }} --}}
            </div>
            @endif
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Konfirmasi Export</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">
                            Apakah Anda yakin ingin mengekspor laporan Rekapitulasi? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya, Export!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.querySelector("table");
                
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Rekapitulasi.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>