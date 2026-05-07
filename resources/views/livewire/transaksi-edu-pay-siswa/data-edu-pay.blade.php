{{-- Success is as dangerous as failure. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center flex-wrap gap-3">
            {{-- Judul --}}
            <h5 class="card-title mb-0 flex-grow-1">Riwayat Transaksi EduPay</h5>
    
            {{-- Tombol Export & Cetak --}}
            <div class="d-flex gap-2 flex-wrap">
                <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-soft-success">
                    <i class="ri-file-excel-2-line pb-0"></i> Export
                </button>
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
        {{-- <div class="row g-3 align-items-end mb-3">
            <!-- Jenis -->
            <div class="col-xxl-3 col-sm-6">
                <label class="form-label">Jenis Transaksi</label>
                <select wire:model="selectedJenis" class="form-select">
                    <option value="">Semua</option>
                    <option value="topup tunai">Top-Up Tunai</option>
                    <option value="topup online">Top-Up Online</option>
                    <option value="pengembalian dana">Pengembalian Dana</option>
                    <option value="penarikan">Penarikan</option>
                    <option value="pembayaran">Pembayaran</option>
                    <option value="kantin">Kantin</option>
                </select>
            </div>
        
            <!-- Search -->
            <div class="col-xxl-9 col-sm-6">
                <label class="form-label">Pencarian</label>
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        
        </div> --}}
        <div class="table-responsive">
            <table id="data" class="table table-borderless table-hover text-center table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="table-active">
                        <th style="width: 50px;" class="text-uppercase">NO</th>
                        <th class="text-uppercase" scope="col" style="width: 50px;">hapus</th>
                        <th class="text-start text-uppercase" scope="col" style="width: 150px;">tanggal</th>
                        <th class="text-start text-uppercase" scope="col">transaksi</th>
                        <th class="text-uppercase" scope="col">petugas</th>
                        <th class="text-uppercase" scope="col">pemasukan</th>
                        <th class="text-uppercase" scope="col">pengeluaran</th>
                        <th class="text-uppercase" scope="col" class="">saldo</th>
                        <th class="text-start text-uppercase">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary fw-semibold">
                        <td colspan="5">
                            <i class="ri-wallet-3-line me-1"></i>
                            Saldo Awal Periode
                        </td>
                        <td class="fs-14 text-success">Rp{{ number_format($totalMasukSebelum, 0, ',', '.') }}</td>
                        <td class="fs-14 text-danger">Rp{{ number_format($totalKeluarSebelum, 0, ',', '.') }}</td>
                        <td class="fs-14 text-info">Rp{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    @forelse ($transaksiEduPay as $item)
                    <tr>
                        <td style="width: 50px">{{ $loop->iteration }}.</td>
                        <td>
                                @if ($item->jenis_transaksi === 'pembayaran' || $item->jenis_transaksi === 'kantin')
                                <span class="text-muted" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" 
                                    title="Hapus Transaksi lewat Histori {{ ucfirst($item->jenis_transaksi) }}">
                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                </span>
                            @elseif ($item->jenis_transaksi === 'topup online')
                                <span class="text-muted" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Transaksi Topup Online tidak dapat dihapus">
                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                </span>
                            @elseif ($item->jenis_transaksi === 'pengembalian dana')
                                <span class="text-muted" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pengembalian dana tidak dapat dihapus, hubungi CS">
                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                </span>
                            @else
                                <a href="#ModalDeleteEduPay" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                                wire:click.prevent="$emit('confirmDeleteEduPay', {{ $item->ms_transaksi_edupay_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi EduPay">
                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                </a>
                            @endif

                        </td>
                        <td class="text-uppercase text-start">
                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                        </td>
                        <td class="text-start">
                            <span class="fs-14">
                                {!! 'RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i>' !!}
                            </span>
                            <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                        </td>
                        <td>
                            <span class="fs-14">
                                {{ $item->ms_pengguna->nama }}
                            </span>
                        </td>
                        <td>
                            <span class="fs-14 text-success">
                                {{ in_array($item->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana']) ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="fs-14 text-danger">
                                {{ in_array($item->jenis_transaksi, ['penarikan', 'pembayaran','kantin']) ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="fs-14 text-info">
                                Rp{{ number_format($item->saldo, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="text-start">
                            <ul class="list-inline hstack gap-2 mb-0">
                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Transaksi">
                                    @if ($item->jenis_transaksi === 'penarikan' || $item->jenis_transaksi === 'topup tunai')
                                        <a href="#loadTransaksiEduPay" 
                                            data-bs-toggle="modal" 
                                            wire:click.prevent="$emit('loadTransaksiEduPay', {{ $item->ms_transaksi_edupay_id }})" 
                                            class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                            <i class="ri-quill-pen-line align-bottom"></i>
                                            <span>Edit Transaksi</span>
                                        </a>
                                    @else
                                        <span class="text-muted" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-trigger="hover" 
                                            data-bs-placement="top" 
                                            title="Transaksi {{ ucfirst($item->jenis_transaksi) }} tidak dapat diedit">
                                            <i class="ri-quill-pen-line align-bottom"></i>
                                            <span>Edit Transaksi</span>
                                        </span>
                                    @endif
                                </li>

                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan Transaksi">
                                    <a  wire:click.prevent="kirimWhatsapp({{ $item->ms_transaksi_edupay_id }})" class="btn btn-sm btn-soft-success d-inline-flex align-items-center gap-1">
                                        <i class="ri-whatsapp-line align-bottom"></i>
                                        <span>Kirim WhatsApp</span>
                                    </a>
                                </li>
                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Bukti Transaksi">
                                    <a wire:click="cetakTransaksi({{ $item->ms_transaksi_edupay_id }})" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
                                        <i class="ri-printer-line align-bottom"></i>
                                        <span>Cetak</span>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Tidak ada transaksi EduPay.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table><!--end table-->
        </div>
    </div>
    <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Konfirmasi Export</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">
                            Apakah Anda yakin ingin mengekspor laporan Transaksi EduPay? Data yang diekspor akan
                            sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya,
                                Export!</button>
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
                var table = document.getElementById("data");
                
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Data Transaksi EduPay.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>