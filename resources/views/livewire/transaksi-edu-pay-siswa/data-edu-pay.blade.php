{{-- Success is as dangerous as failure. --}}
<div class="card">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center mb-2">
            <div class="col-sm-4">
                <p class="text-muted mb-2 text-uppercase fw-semibold">
                    Data Transaksi EduPay
                </p>
            </div>
        </div>
        <div class="table-responsive">
            @php
                $saldo = 0;
            @endphp
            <table class="table table-borderless table-hover text-center table-nowrap align-middle mb-0">
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
                    @forelse ($transaksiEduPay as $item)
                        <tr>
                            <td style="width: 50px">{{ $loop->iteration }}.</td>
                            <td>
                                @if ($item->jenis_transaksi === 'pembayaran')
                                    <span class="text-muted" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi lewat Histori Pembayaran">
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
                                    wire:click.prevent="$emit('confirmDeleteEduPay', {{ $item->ms_edupay_siswa_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi EduPay">
                                        <i class="ri-delete-bin-5-line align-bottom"></i>
                                    </a>
                                @endif

                            </td>
                            <td class="text-uppercase text-start">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                            </td>
                            <td class="text-start">
                                <span class="fw-medium">
                                    {!! 'RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i>' !!}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                            </td>
                            <td>
                                <span class="fw-medium">
                                    {{ $item->ms_pengguna->nama }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium text-success">
                                    {{ in_array($item->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana']) ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium text-danger">
                                    {{ in_array($item->jenis_transaksi, ['penarikan', 'pembayaran']) ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    // Perhitungan saldo
                                    if (in_array($item->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
                                        $saldo += $item->nominal; // Tambahkan saldo
                                    } elseif (in_array($item->jenis_transaksi, ['penarikan', 'pembayaran'])) {
                                        $saldo -= $item->nominal; // Kurangi saldo
                                    }
                                @endphp
                                <span class="fw-medium text-info">
                                    RP{{ number_format($saldo, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="text-start">
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Transaksi">
                                        <a href="#editTransaksiEduPay" data-bs-toggle="modal" wire:click.prevent="$emit('loadTransaksiEduPay', {{ $item->ms_edupay_siswa_id }})" 
                                            class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                            <i class="ri-quill-pen-line align-bottom"></i>
                                            <span>Edit Transaksi</span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan Transaksi">
                                        <a  wire:click.prevent="kirimWhatsapp({{ $item->ms_edupay_siswa_id }})" class="btn btn-sm btn-soft-success d-inline-flex align-items-center gap-1">
                                            <i class="ri-whatsapp-line align-bottom"></i>
                                            <span>Kirim WhatsApp</span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Bukti Transaksi">
                                        <a wire:click="cetakTransaksi({{ $item->ms_edupay_siswa_id }})" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
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


        <div class="hstack gap-2 justify-content-end d-print-none mt-4">   
            <a  
            wire:click.prevent="" 
            class="btn btn-sm btn-success">
                <i class="ri-printer-line align-bottom me-1"></i> Bayarkan
            </a>
            {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
        </div>

    </div>
</div>