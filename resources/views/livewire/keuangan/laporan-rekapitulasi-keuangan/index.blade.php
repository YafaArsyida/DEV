{{-- Stop trying to control. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-chart-line"></i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
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
                        <small>
                            @if ($jenisRekapitulasi === 'tagihan')
                                Ringkasan tagihan siswa berdasarkan periode dan kelas.
                            @elseif ($jenisRekapitulasi === 'pembayaran')
                                Ringkasan pembayaran tagihan siswa.
                            @elseif ($jenisRekapitulasi === 'kekurangan')
                                Pantau sisa tagihan yang belum dibayarkan siswa.
                            @else
                                Lihat ringkasan kondisi keuangan siswa.
                            @endif
                        </small>
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            <div class="d-flex gap-2 flex-wrap">
                <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-success rounded-pill px-4"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="offcanvas" data-bs-target="#filterRekapitulasi" aria-controls="filterTabungan"><i class="ri-filter-3-line align-bottom me-1"></i> Fliters</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-4">
            <!-- Jenis Rekapitulasi (paling kiri) -->
            <div class="col-xxl-2 col-md-3">
                <label for="rekapSelect" class="form-label small text-muted text-uppercase fw-medium mb-2">Jenis Rekapitulasi</label>
                <select id="rekapSelect" wire:model="jenisRekapitulasi" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenis Rekapitulasi">
                    <option value="tagihan">Estimasi</option>
                    <option value="pembayaran">Dibayarkan</option>
                    <option value="kekurangan">Kekurangan</option>
                </select>
            </div>

            <!-- Pencarian (tengah) -->
            <div class="col-xxl-10 col-sm-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
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
                <table id="tabelRekap" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase">Kelas</th>
                            @foreach($jenisTagihan as $jenis)
                                <th class="">{{ $jenis->nama_jenis_tagihan_siswa }}</th>
                            @endforeach
                            <th class="text-uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $key => $item)
                            <tr>
                                <td>{{ $siswas->firstItem() + $key }}.</td>
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
                                                    $value = $tagihanItem->jumlah_sudah_dibayar ?? 0;
                                                } elseif ($jenisRekapitulasi === 'kekurangan') {
                                                    $value = $tagihanItem->jumlah_tagihan_siswa - ($tagihanItem->jumlah_sudah_dibayar ?? 0);
                                                }
                                            }
                                        @endphp

                                        {{ $value !== null ? 'Rp' . number_format($value, 0, ',', '.') : '-' }}
                                    </td>
                                @endforeach
                                <td>
                                    @php
                                        if ($jenisRekapitulasi === 'tagihan') {
                                            $total = $item->total_tagihan_siswa();
                                        } elseif ($jenisRekapitulasi === 'pembayaran') {
                                            $total = $item->total_dibayarkan();
                                        } else {
                                            $total = $item->total_kekurangan();
                                        }
                                    @endphp

                                    Rp{{ number_format($total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + count($jenisTagihan) }}"> <!-- Tambahkan jumlah kolom dinamis -->
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

                        {{-- Subtotal halaman --}}
                        <tr class="table-light">
                            <td colspan="3">
                                <strong>SUBTOTAL HALAMAN</strong>
                            </td>

                            @foreach ($jenisTagihan as $jenis)
                                <td>
                                    Rp{{ number_format($pageTotal[$jenis->ms_jenis_tagihan_siswa_id] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            <td>
                                <strong>
                                    Rp{{ number_format($pageGrandTotal, 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>

                        {{-- Grand total --}}
                        <tr class="table-secondary fw-bold">
                            <td colspan="3">
                                GRAND TOTAL
                                <br>
                                <small>Seluruh data sesuai filter</small>
                            </td>

                            @foreach ($jenisTagihan as $jenis)
                                <td>
                                    Rp{{ number_format($overallTotal[$jenis->ms_jenis_tagihan_siswa_id] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            <td>
                                Rp{{ number_format($overallGrandTotal, 0, ',', '.') }}
                            </td>
                        </tr>

                    </tfoot>            
                </table>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $siswas->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $siswas->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $siswas->total() }}
                            </span>
                            data
                        </div>
                        <div>
                            {{ $siswas->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn"
        id="ExportLaporan"
        tabindex="-1"
        aria-labelledby="exportRecordLabel"
        aria-hidden="true"
        wire:ignore.self>

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="modal-header border-0 pb-0">

                    <button
                        type="button"
                        class="btn btn-light btn-icon rounded-circle ms-auto"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line fs-18"></i>

                    </button>

                </div>

                {{-- BODY --}}
                <div class="modal-body px-4 pb-5 pt-2 text-center">

                    <div class="mb-4">

                        <div class="avatar-xl mx-auto">

                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">

                                <lord-icon
                                    src="https://cdn.lordicon.com/fjvfsqea.json"
                                    trigger="loop"
                                    colors="primary:#405189,secondary:#0ab39c"
                                    style="width:70px;height:70px">
                                </lord-icon>

                            </div>

                        </div>

                    </div>

                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                        Konfirmasi Export
                    </span>

                    <h3 class="fw-bold mb-2">
                        Export Laporan Rekapitulasi?
                    </h3>

                    <p class="text-muted mb-0 lh-lg px-lg-4">
                        Data yang diekspor akan mengikuti tabel yang sedang
                        ditampilkan sehingga hasil export sesuai dengan filter
                        dan jenis rekapitulasi yang dipilih.
                    </p>

                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">

                        <div class="d-flex gap-3">

                            <i class="ri-information-line text-primary fs-20"></i>

                            <div>

                                <h6 class="fw-semibold mb-1">
                                    Informasi
                                </h6>

                                <p class="text-muted mb-0 fs-13">
                                    Pastikan data yang tampil sudah sesuai sebelum
                                    melakukan export Excel.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                    <button
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Batal

                    </button>

                    <button
                        class="btn btn-primary rounded-pill px-4"
                        id="konfirmasiExportLaporan"
                        data-bs-dismiss="modal">

                        <i class="ri-download-2-line me-1"></i>
                        Ya, Export

                    </button>

                </div>

            </div>

        </div>

    </div>
    <script>
        document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {

            alertify.success("Menyiapkan Dokumen");

            setTimeout(function () {

                const table = document.getElementById("tabelRekap");

                const workbook = XLSX.utils.table_to_book(table, {
                    sheet: "Rekapitulasi"
                });

                // Ambil nilai Livewire
                let jenis = @this.get('jenisRekapitulasi');

                let namaJenis = {
                    tagihan: "Estimasi",
                    pembayaran: "Dibayarkan",
                    kekurangan: "Kekurangan"
                };

                let fileName =
                    `Laporan-Rekapitulasi-${namaJenis[jenis] || "Rekapitulasi"}-{{ date('Y-m-d') }}.xlsx`;

                XLSX.writeFile(workbook, fileName);

            }, 1000);

        });
        </script>
</div>