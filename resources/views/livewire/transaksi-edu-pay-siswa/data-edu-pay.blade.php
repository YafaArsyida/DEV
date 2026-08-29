{{-- Success is as dangerous as failure. --}}
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
                            Riwayat Transaksi EduPay Siswa
                        </h5>
                        {{-- <small class="text-muted">
                            Riwayat transaksi tabungan siswa berdasarkan periode yang dipilih.
                        </small> --}}
                    </div>
                </div>
            </div>
            {{-- Tombol Export & Cetak --}}
            <div class="d-flex gap-2 flex-wrap">
                {{-- <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn rounded-pill px-4 btn-soft-success">
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
            <table id="data" class="table table-hover table-nowrap align-middle">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th style="width: 50px;" class="text-uppercase">NO</th>
                        <th class="text-center" scope="col" style="width: 50px;">hapus</th>
                        <th class="text-start" scope="col" style="width: 150px;">tanggal</th>
                        <th class="text-start" scope="col">transaksi</th>
                        <th class="text-center" scope="col">petugas</th>
                        <th class="text-center" scope="col">pemasukan</th>
                        <th class="text-center" scope="col">pengeluaran</th>
                        <th class="text-center" scope="col" class="">saldo</th>
                        <th class="text-start">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary fw-medium text-center">
                        <td colspan="5">
                            <i class="ri-wallet-3-line me-1"></i>
                            Saldo Sebelum Periode
                        </td>
                        <td class="fs-12 text-success">Rp{{ number_format($totalMasukSebelum, 0, ',', '.') }}</td>
                        <td class="fs-12 text-danger">Rp{{ number_format($totalKeluarSebelum, 0, ',', '.') }}</td>
                        <td class="fs-12">Rp{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    @forelse ($transaksiEduPay as $item)
                    <tr class="{{ $item->status_transaksi === 'dibatalkan' ? 'table-danger' : '' }}">
                        <td class="text-center" style="width: 50px">{{ $loop->iteration }}.</td>
                        <td class="text-center">
                            @if ($item->jenis_transaksi === 'topup tunai' &&
                                $item->status_transaksi !== 'dibatalkan' && 
                                \Carbon\Carbon::parse($item->tanggal)->isToday())
                                <a href="#ModalDeleteEduPay" data-bs-toggle="modal"
                                    class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('confirmDeleteEduPay', {{ $item->ms_transaksi_edupay_id }})"
                                    data-bs-trigger="hover" data-bs-placement="top"
                                    title="Koreksi Transaksi Top Up Tunai Hari Ini"
                                >
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            @else
                                <span class="text-muted d-inline-block remove-item-btn"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                    title="Hanya Top Up Tunai hari ini yang dapat dikoreksi"
                                >
                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </span>
                            @endif
                        </td>
                        <td class="text-uppercase text-start">
                            <div class="fw-medium">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                    $item->tanggal,
                                    'd F Y'
                                ) }}
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('H:i') }}
                            </small>
                        </td>
                        <td>
                            <div>
                                <span class="fs-12 fw-medium {{ $item->status_transaksi === 'dibatalkan' ? 'text-decoration-line-through text-muted' : '' }}">
                                    Rp{{ number_format($item->nominal, 0, ',', '.') }}
                                    -
                                    <i>{{ ucfirst($item->jenis_transaksi) }}</i>
                                </span>

                                @if ($item->status_transaksi === 'dibatalkan')
                                    <span class="badge bg-danger-subtle text-danger ms-1">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>

                            <p class="text-muted mb-0">
                                {{ $item->deskripsi ?? '' }}
                            </p>
                        </td>
                        <td class="text-center">{{ $item->ms_pengguna->nama }}</td>
                        <td class="text-center">
                            <span class="fs-12 fw-medium {{ $item->status_transaksi === 'dibatalkan' ? 'text-muted text-decoration-line-through' : 'text-success' }}">
                                {{ in_array($item->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])
                                    ? 'Rp' . number_format($item->nominal, 0, ',', '.')
                                    : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fs-12 fw-medium {{ $item->status_transaksi === 'dibatalkan' ? 'text-muted text-decoration-line-through' : 'text-danger' }}">
                                {{ in_array($item->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin'])
                                    ? 'Rp' . number_format($item->nominal, 0, ',', '.')
                                    : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fs-12 fw-medium">
                                Rp{{ number_format($item->saldo, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="text-start">
                            <ul class="list-inline hstack gap-2 mb-0">
                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Detail Transaksi">
                                    <a href="#detailTransaksiEduPay"
                                        data-bs-toggle="modal"
                                        wire:click.prevent="$emit('loadDetailTransaksiEduPay', {{ $item->ms_transaksi_edupay_id }})"
                                        class="btn btn-info btn-sm rounded-pill px-3">
                                        <i class="ri-eye-line me-1"></i>
                                        <span>Detail</span>
                                    </a>
                                </li>
                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Transaksi">
                                    @if (
                                        $item->status_transaksi !== 'dibatalkan' &&
                                        ($item->jenis_transaksi === 'penarikan' || $item->jenis_transaksi === 'topup tunai')
                                    )
                                        <a href="#loadTransaksiEduPay" 
                                            data-bs-toggle="modal" 
                                            wire:click.prevent="$emit('loadTransaksiEduPay', {{ $item->ms_transaksi_edupay_id }})" 
                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                            <i class="ri-mark-pen-line me-1"></i>
                                            <span>Edit</span>
                                        </a>
                                    @else
                                        <span class="btn btn-muted btn-sm rounded-pill px-3" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-trigger="hover" 
                                            data-bs-placement="top" 
                                            title="{{ $item->status_transaksi === 'dibatalkan'
                                                ? 'Transaksi sudah dibatalkan'
                                                : 'Transaksi ' . ucfirst($item->jenis_transaksi) . ' tidak dapat diedit' }}">
                                            <i class="ri-mark-pen-line me-1"></i>
                                            <span>Edit</span>
                                        </span>
                                    @endif
                                </li>

                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Bukti Transaksi">
                                    <a wire:click="cetakTransaksi({{ $item->ms_transaksi_edupay_id }})" class="btn btn-sm btn-danger rounded-pill px-3">
                                        <i class="ri-printer-line me-1"></i>
                                            <span>Cetak</span>
                                    </a>
                                </li>

                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan Transaksi">
                                    @if ($item->status_transaksi !== 'dibatalkan')
                                        <a wire:click.prevent="kirimWhatsapp({{ $item->ms_transaksi_edupay_id }})"
                                            class="btn btn-soft-success btn-sm rounded-pill px-3">
                                            <i class="ri-whatsapp-line me-1"></i>
                                            <span>Kirim WhatsApp</span>
                                        </a>
                                    @else
                                        <span
                                            class="btn btn-muted btn-sm rounded-pill px-3"
                                            data-bs-toggle="tooltip"
                                            title="Transaksi sudah dibatalkan">
                                            <i class="ri-whatsapp-line me-1"></i>
                                            <span>Kirim WhatsApp</span>
                                        </span>
                                    @endif
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
                            Apakah Anda yakin ingin mengekspor laporan Transaksi EduPay? Data yang diekspor akan
                            sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary rounded-pill px-4" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya,
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