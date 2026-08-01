{{-- The Master doesn't talk, he acts. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-3">
            {{-- Judul --}}
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Riwayat Transaksi Tabungan Siswa
                        </h5>
                        <small class="text-muted">
                            Riwayat transaksi tabungan siswa berdasarkan periode yang dipilih.
                        </small>
                    </div>
                </div>
            </div>
            {{-- Tombol Export & Cetak --}}
            <div class="d-flex gap-2 flex-wrap">
                {{-- <button data-bs-toggle="modal" data-bs-target="#ExportLaporanTabungan" class="btn rounded-pill px-4 btn-soft-success">
                    <i class="ri-file-excel-2-line pb-0"></i> Export
                </button> --}}
                {{-- <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                    <i class="ri-printer-line align-bottom"></i>
                    <span>Cetak Laporan</span>
                </button> --}}
            </div>
    
            <div class="d-flex align-items-center gap-2">
                <input type="date" class="form-control" wire:model="startDate">
                <span class="text-muted">–</span>
                <input type="date" class="form-control" wire:model="endDate">
                <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTabungan" class="table table-hover table-nowrap align-middle">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th class="text-center" scope="col" style="width: 50px;">hapus</th>
                        <th class="text-start" scope="col" style="width: 150px;">tanggal</th>
                        <th class="text-start" scope="col">transaksi</th>
                        <th scope="col" class="text-center">petugas</th>
                        <th scope="col" class="text-center">kredit</th>
                        <th scope="col" class="text-center">debit</th>
                        <th scope="col" class="text-center">saldo</th>
                        <th class="text-start">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary fw-medium text-center">
                        <td colspan="5">
                            <i class="ri-wallet-3-line me-1"></i>
                            Saldo Sebelum Periode
                        </td>
                    
                        <td class="fs-12 fw-medium text-success">
                            Rp{{ number_format($totalSetoranSebelum, 0, ',', '.') }}
                        </td>
                    
                        <td class="fs-12 fw-medium text-danger">
                            Rp{{ number_format($totalPenarikanSebelum, 0, ',', '.') }}
                        </td>
                    
                        <td class="fs-12 fw-medium text-primary">
                            Rp{{ number_format($saldoAwal, 0, ',', '.') }}
                        </td>
                    
                        <td></td>
                    </tr>
                    @forelse ($transaksiTabungan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}.</td>
                            <td class="text-center">
                                <a href="#ModalDeleteTabungan" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('confirmDeleteTabungan', {{ $item->ms_transaksi_tabungan_id }})"
                                    data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi Tabungan">
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            </td>
                            <td class="text-uppercase">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                            </td>
                            <td>
                                <span class="fs-12 fw-medium">
                                    {!! 'Rp' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i>' !!}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                            </td>

                            <td class="text-center">{{ $item->ms_pengguna->nama }}</td>
                            <td class="text-center">
                                <span class="fs-12 fw-medium text-success">
                                    {{ $item->jenis_transaksi === 'setoran' ? 'Rp' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fs-12 fw-medium text-danger">
                                    {{ $item->jenis_transaksi === 'penarikan' ? 'Rp' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="fs-12 fw-medium text-primary">
                                    Rp{{ number_format($item->saldo, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-start">
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Transaksi">
                                        <a href="#loadTransaksiTabungan" data-bs-toggle="modal" wire:click.prevent="$emit('loadTransaksiTabungan', {{ $item->ms_transaksi_tabungan_id }})" 
                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                            <i class="ri-mark-pen-line me-1"></i>
                                            <span>Edit</span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Bukti Transaksi">
                                        <a wire:click="cetakTransaksi({{ $item->ms_transaksi_tabungan_id }})" class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="ri-printer-line me-1"></i>
                                            <span>Cetak</span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan Transaksi">
                                        <a  wire:click.prevent="kirimWhatsapp({{ $item->ms_transaksi_tabungan_id }})" class="btn btn-soft-success btn-sm rounded-pill px-3">
                                            <i class="ri-whatsapp-line me-1"></i>
                                            <span>Kirim WhatsApp</span>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Tidak ada transaksi tabungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table><!--end table-->
        </div>
    
        <div class="modal fade zoomIn" id="ExportLaporanTabungan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true"
            wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop"
                            colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                        <div class="mt-4 text-center">
                            <h4 class="fs-semibold">Konfirmasi Export</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">
                                Apakah Anda yakin ingin mengekspor laporan Transaksi Tabungan? Data yang diekspor akan
                                sesuai dengan tabel yang ditampilkan. Kolom aksi tidak akan disertakan.
                            </p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                    data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Batal
                                </button>
                                <button class="btn btn-primary rounded-pill px-4" id="konfirmasiExportLaporanTabungan" data-bs-dismiss="modal">Ya,
                                    Export!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('konfirmasiExportLaporanTabungan').addEventListener('click', function () {
                alertify.success("Menyiapkan Dokumen");
                setTimeout(function () {
                    var table = document.getElementById("dataTabungan");

                    // Clone table to avoid modifying DOM
                    var clone = table.cloneNode(true);

                    // Remove last column (aksi) from header
                    var thead = clone.querySelector('thead');
                    if (thead) {
                        var ths = thead.querySelectorAll('th');
                        if (ths.length > 0) ths[ths.length - 1].parentNode.removeChild(ths[ths.length - 1]);
                    }

                    // Remove last column from each body row
                    var tbodies = clone.querySelectorAll('tbody');
                    tbodies.forEach(function(tbody){
                        var rows = tbody.querySelectorAll('tr');
                        rows.forEach(function(row){
                            var cells = row.querySelectorAll('td, th');
                            if (cells.length > 0) {
                                var last = cells[cells.length - 1];
                                if (last) last.parentNode.removeChild(last);
                            }
                        });
                    });

                    var workbook = XLSX.utils.table_to_book(clone, { sheet: "Sheet1" });
                    XLSX.writeFile(workbook, "Data Transaksi Tabungan.xlsx");
                }, 1000);
            });
        </script>
    </div>
</div>