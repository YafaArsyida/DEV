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
                        @if ($item['total_bayar'] === 0)
                            <a href="#ModalAksiDelete" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                            wire:click.prevent="$emit('loadTagihanDelete', {{ $item['ms_tagihan_siswa_id'] }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Tagihan">
                                <i class="ri-delete-bin-5-line"></i>
                            </a>
                        @else
                            <span class="text-muted" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Terdapat Pembayaran">
                                <i class="ri-delete-bin-5-line"></i>
                            </span>
                        @endif
                    </th>
                    <td class="text-start">{{ $item['nama_jenis'] }}</td>
                    <td class="text-start">{{ $item['nama_kategori'] }}</td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-info">
                            RP{{ number_format($item['jumlah_tagihan_siswa'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-success">
                            RP{{ number_format($item['total_bayar'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-medium fs-14 text-danger">
                            RP{{ number_format($item['kekurangan'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        @if ($item['status'] === 'Lunas')
                            <span class="text-success d-inline-flex align-items-center gap-1">
                                <i class="ri-checkbox-circle-line"></i> Lunas
                            </span>
                        @elseif ($item['in_keranjang'])
                            <span class="text-info d-inline-flex align-items-center gap-1">
                                <i class="ri-check-double-line"></i> Menunggu Bayar
                            </span>
                        @else
                            <ul class="list-inline mb-0 d-flex flex-wrap gap-2">
                                {{-- Keranjang --}}
                                <li class="list-inline-item" title="Masuk Keranjang">
                                    <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                        wire:click="tambahKeranjang({{ $item['ms_tagihan_siswa_id'] }})">
                                        <i class="ri-shopping-cart-line"></i> Keranjang
                                    </button>
                                </li>
                            
                                {{-- Cicilan --}}
                                @if ($item['cicilan_status'] === 'Aktif')
                                <li class="list-inline-item" title="Cicil Tagihan">
                                    <button type="button" class="btn btn-sm btn-soft-secondary d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#ModalAksiBayar"
                                        wire:click="$emit('loadCicilan', {{ $item['ms_tagihan_siswa_id'] }})">
                                        <i class="ri-money-dollar-circle-line"></i> Cicil
                                    </button>
                                </li>
                                @else
                                <li class="list-inline-item">
                                    <span class="text-muted d-inline-flex align-items-center gap-1" title="Cicilan tidak aktif">
                                        <i class="ri-money-dollar-circle-line"></i> Non Cicil
                                    </span>
                                </li>
                                @endif
                                {{-- Edit --}}
                                <li class="list-inline-item" title="Edit Tagihan">
                                    <a href="#ModalAksiEdit" data-bs-toggle="modal" class="text-primary d-inline-block" title="Edit Siswa"
                                        wire:click="$emit('loadTagihanEdit', {{ $item['ms_tagihan_siswa_id'] }})">
                                        <i class="ri-quill-pen-line fs-17 align-middle"></i> Edit
                                    </a>
                                    {{-- <button type="button" class="btn btn-sm btn-soft-warning d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#ModalAksiEdit"
                                        wire:click="$emit('loadTagihanEdit', {{ $item['ms_tagihan_siswa_id'] }})">
                                        <i class="ri-quill-pen-line"></i> Edit
                                    </button> --}}
                                </li>
                            
                            </ul>
                        @endif
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