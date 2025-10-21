{{-- Be like water. --}}
<div>
    <div class="card">
        <div class="card-header border-0 pb-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Saldo Tabungan</h5>
                <div class="flex-shrink-0">
                    <div class="d-flex gap-2 flex-wrap">
                        {{-- @if ($selectedKelas)
                        @endif --}}
                        {{-- <button data-bs-toggle="modal" data-bs-target="#WithdrawEduPay" wire:click.prevent="$emit('withdrawEduPay', {selectedKelas: '{{ $selectedKelas }}', selectedJenjang: '{{ $selectedJenjang }}', selectedTahunAjar: '{{ $selectedTahunAjar }}'})" class="btn btn-danger"><i class="ri-file-excel-2-line pb-0"></i> Kosongkan Edupay</button> --}}
                        <button data-bs-toggle="modal" data-bs-target="#ExportLaporanSaldo" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end mb-3">
                <!-- Dropdown Kelas -->
                <div class="col-xxl-4 col-sm-6">
                    <label for="selectJabatan" class="form-label">Jabatan</label>
                    <select id="selectJabatan" 
                            wire:model="selectedJabatan" 
                            class="form-select" style="cursor: pointer"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" 
                            data-bs-placement="top" title="Pilih Jabatan">
                        <option value="">Semua Jabatan</option>
                        @foreach ($select_jabatan as $item)    
                        <option value="{{ $item->ms_jabatan_id }}">{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Input Pencarian -->
                <div class="col-xxl-8 col-sm-6">
                    <label for="searchInput" class="form-label">Pencarian</label>
                    <div class="position-relative">
                        <input type="text" id="searchInput" 
                            class="form-control ps-4" 
                            wire:model.debounce.300ms="search" 
                            placeholder="Cari nama, deskripsi, atau lainnya...">
                        <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                    </div>
                </div>
            </div>

            <!--end row-->
            {{-- DATA --}}
            <div class="live-preview">
                <div class="table-responsive">
                    <table id="saldoTabungan" class="table table-hover nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase" style="width: 50px;">NO</th>
                                <th class="text-uppercase">Pegawai</th>
                                <th class="text-uppercase">EduCard</th>
                                <th class="text-uppercase text-center">Saldo Tabungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawai as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}.</td> 
                                    <td class="text-start" style="white-space: nowrap;">
                                        {{ ucfirst($item->nama_pegawai) }}
                                        <p class="fs-12 mb-0 text-muted">{{ $item->ms_jabatan->nama_jabatan ?? '' }}</p>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($item->ms_educard)
                                            <span class="fs-14 text-warning">
                                                {{ $item->ms_educard->kode_kartu }}
                                            </span>
                                        @else
                                            <em>Belum memiliki kartu</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-14 text-info">
                                            RP{{ number_format($item->saldo_tabungan_pegawai() ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
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
                            <tr class="">
                                <td></td>
                                <td></td>
                                <td class="text-uppercase text-start">TOTAL</td>
                                <td class="text-center">
                                    <span class="fs-14 text-info">
                                        RP{{ number_format($totalSaldo, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- {{ $siswas->links() }} --}}
                </div>
            </div>
            {{-- end data --}}
        </div>
        {{-- MODAL --}}
        <div class="modal fade zoomIn" id="ExportLaporanSaldo" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
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
                                Apakah Anda yakin ingin mengekspor laporan Saldo Edupay? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                            </p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Batal
                                </button>
                                <button class="btn btn-primary" id="konfirmasiExportSaldo" data-bs-dismiss="modal">Ya, Export!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportSaldo').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("saldoTabungan"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-Saldo-EduPay-Pegawai.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>

