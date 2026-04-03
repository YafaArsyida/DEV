<div class="card-body px-4 pt-0">
    @if (!$siswaSelected)
    <div class="text-center py-4">
        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
            colors="primary:#405189,secondary:#08a88a"
            style="width:75px;height:75px">
        </lord-icon>
        <h5 class="mt-2">Silakan Pilih Siswa</h5>
        <p class="text-muted mb-0">Untuk melihat data tagihan, harap pilih siswa terlebih dahulu.</p>
    </div>
    @else
    <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
        <!-- Kiri: Judul -->
        <div>
            <p class="text-muted text-uppercase fw-semibold mb-0">Tagihan</p>
        </div>
    
        <!-- Kanan: Tombol -->
        <div>
            <button 
                class="btn btn-primary d-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" 
                data-bs-target="#ModalAksiTambah" 
                wire:click="$emit('showModalTambah', {{ $ms_penempatan_siswa_id }}, {{ $ms_jenjang_id }}, {{ $ms_tahun_ajar_id }})" 
                data-bs-trigger="hover" 
                data-bs-placement="top" 
                title="Buat Tagihan Baru">
                <i class="ri-stack-line align-bottom"></i> Tagihan Baru
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover nowrap align-bottom" style="width:100%">
            <thead class="table-light">
                <tr>
                    <th class="text-uppercase" style="width: 50px;">Hapus</th>
                    <th class="text-uppercase">Jenis Tagihan</th>
                    <th class="text-uppercase">Kategori</th>
                    <th class="text-uppercase text-center">Estimasi</th>
                    <th class="text-uppercase text-center">Dibayarkan</th>
                    <th class="text-uppercase text-center">Kekurangan</th>
                    {{-- <th class="text-uppercase">Status</th> --}}
                    <th class="text-uppercase">aksi</th>
                </tr>
            </thead>
            <tbody id="products-list">
                @forelse ($tagihans as $item)
                <tr class="align-middle">
                    <th class="text-center" scope="row">
                        @if ($item->jumlah_sudah_dibayar() === 0)
                            <a href="#ModalAksiDelete" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                            wire:click.prevent="$emit('loadTagihanDelete', {{ $item->ms_tagihan_siswa_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Tagihan">
                                <i class="ri-delete-bin-5-line"></i>
                            </a>
                        @else
                            <span class="text-muted" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Terdapat Pembayaran">
                                <i class="ri-delete-bin-5-line"></i>
                            </span>
                        @endif
                    </th>
                    <td class="text-start">{{ $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}</td>
                    <td class="text-start">{{ $item->nama_kategori_tagihan_siswa() }}</td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-info">
                            RP{{ number_format($item->jumlah_tagihan_siswa, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-success">
                            RP{{ number_format($item->jumlah_sudah_dibayar(), 0, ',', '.') }}</td>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-danger">
                            RP{{ number_format($item->jumlah_tagihan_siswa - $item->jumlah_sudah_dibayar(), 0, ',', '.') }}
                        </span>
                    </td>
                    {{-- <td class="
                        {{ $item->status === 'Belum Dibayar' ? 'text-warning' : '' }}
                        {{ $item->status === 'Masih Dicicil' ? 'text-info' : '' }}
                        {{ $item->status === 'Lunas' ? 'text-success' : '' }}">
                        <i class="ri-{{ $item->status === 'Belum Dibayar' ? 'time-line' : ($item->status === 'Masih Dicicil' ? 'money-dollar-circle-line' : 'checkbox-circle-line') }} align-bottom"></i>
                        {{ $item->status }}
                    </td> --}}
                    <td class="text-end">
                        <ul class="list-inline hstack gap-2 mb-0">
                        @if ($item->status === 'Lunas')
                        <a class="text-success d-inline-block detail-item-btn">
                            <i class="ri-checkbox-circle-line align-bottom"></i> Lunas
                        </a>
                        @else
                            @if (!$this->isInKeranjang($item->ms_tagihan_siswa_id))
                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Masuk Keranjang">
                                    <a href="" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" 
                                    wire:click.prevent="aksiLunas({{ $item->ms_tagihan_siswa_id }})">
                                        <i class="ri-shopping-cart-line align-bottom"></i> Keranjang
                                    </a>
                                </li>
                                @if ($item->ms_jenis_tagihan_siswa->cicilan_status === 'Aktif')
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cicil Tagihan">
                                        <a href="#ModalAksiBayar" data-bs-toggle="modal" class="btn btn-sm btn-soft-secondary d-inline-flex align-items-center gap-1" 
                                        wire:click.prevent="showBayar({{ $item->ms_tagihan_siswa_id }})">
                                            <i class="ri-money-dollar-circle-line align-bottom"></i> Bayar Cicilan
                                        </a>
                                    </li>
                                @else
                                    <li class="list-inline-item detail">
                                        <span class="text-muted d-inline-block" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Cicilan tidak aktif">
                                            <i class="ri-money-dollar-circle-line align-bottom"></i> Cicilan Tidak Aktif
                                        </span>
                                    </li>
                                @endif
                                <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Tagihan">
                                    <a href="#ModalAksiEdit" data-bs-toggle="modal" class="text-warning d-inline-block detail-item-btn" 
                                    wire:click.prevent="$emit('loadTagihanEdit', {{ $item->ms_tagihan_siswa_id }})">
                                        <i class="ri-quill-pen-line align-bottom"></i> Edit
                                    </a>
                                </li>
                            @else
                            <a class="text-info d-inline-block detail-item-btn">
                                <i class="ri-check-double-line label-icon"></i> Menunggu Bayar
                            </a>
                            @endif
                        @endif
                        </ul>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="9">
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
                    <td></td>
                    <td></td>
                    <td class="text-start fw-medium">TOTAL</td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-info">
                            RP{{ number_format($totalEstimasi, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-success">
                            RP{{ number_format($totalDibayarkan, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-danger">
                            RP{{ number_format($totalKekurangan, 0, ',', '.') }}
                        </span>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table><!--end table-->
    </div>
    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
        <a href="" wire:click.prevent="kirimWhatsappTagihan({{ $ms_penempatan_siswa_id }})" class="btn btn-success d-inline-flex align-items-center gap-1"><i class="ri-whatsapp-line align-bottom"></i> Kirim Tagihan</a>
        <a wire:click="cetakSurat({{ $ms_penempatan_siswa_id }})" class="btn btn-danger d-inline-flex align-items-center gap-1"><i class="ri-printer-line align-bottom"></i> Cetak Surat</a>
    </div>
    @endif
</div>
<!--end card-body-->