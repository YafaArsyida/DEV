{{-- The Master doesn't talk, he acts. --}}
<div class="card">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center mb-2">
            <div class="col-sm-4">
                <p class="text-muted mb-2 text-uppercase fw-semibold">
                    Data Transaksi Tabungan 
                    {{-- @if ($siswa)
                        : {{ $siswa->nama }}
                    @else
                        <span class="text-danger">Silakan pilih siswa untuk melihat data tabungan.</span>
                    @endif --}}
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
                        <th class="text-uppercase" scope="col">kredit</th>
                        <th class="text-uppercase" scope="col">debit</th>
                        <th class="text-uppercase" scope="col" class="">saldo</th>
                        <th class="text-start text-uppercase">aksi</th>
                    </tr>
                </thead>
                <tbody id="products-list">
                    @forelse ($transaksiTabungan as $item)
                        <tr>
                            <td style="width: 50px">{{ $loop->iteration }}.</td>
                            <td>
                                <a href="#ModalDeleteTabungan" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                                wire:click.prevent="$emit('confirmDeleteTabungan', {{ $item->ms_transaksi_tabungan_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi Tabungan">
                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                </a>
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

                            <td>{{ $item->ms_pengguna->nama }}</td>
                            <td>
                                <span class="fs-14 text-success">
                                    {{ $item->jenis_transaksi === 'setoran' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="fs-14 text-danger">
                                    {{ $item->jenis_transaksi === 'penarikan' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="fs-14 text-info">
                                    RP{{ number_format($item->saldo, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-start">
                                <ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Transaksi">
                                        <a href="#loadTransaksiTabungan" data-bs-toggle="modal" wire:click.prevent="$emit('loadTransaksiTabungan', {{ $item->ms_transaksi_tabungan_id }})" 
                                            class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                            <i class="ri-quill-pen-line align-bottom"></i>
                                            <span>Edit Transaksi</span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan Transaksi">
                                        <a  wire:click.prevent="kirimWhatsapp({{ $item->ms_transaksi_tabungan_id }})" class="btn btn-sm btn-soft-success d-inline-flex align-items-center gap-1">
                                            <i class="ri-whatsapp-line align-bottom"></i>
                                            <span>Kirim WhatsApp</span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cetak Bukti Transaksi">
                                        <a wire:click="cetakTransaksi({{ $item->ms_transaksi_tabungan_id }})" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
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
                                Tidak ada transaksi tabungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table><!--end table-->
        </div>
    </div>
</div>