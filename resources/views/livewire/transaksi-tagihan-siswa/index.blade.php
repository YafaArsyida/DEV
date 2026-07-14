
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="flex-grow-1">
                <h4 class="mb-1">
                    {{ $nama_siswa ?? 'Siswa belum dipilih' }}
                </h4>

                <div class="hstack gap-3 flex-wrap small">
                    <div>
                        <a href="#" class="text-primary text-decoration-none">
                            {{ $ms_penempatan_siswa_id }} - TemanSekolah
                        </a>
                    </div>

                    <div class="vr"></div>

                    <div class="text-muted">
                        EduCard :
                        <span class="text-warning fw-semibold">
                            {{ $educard ?? 'Belum ada' }}
                        </span>
                    </div>

                    <div class="vr"></div>

                    <div class="text-muted">
                        Kelas :
                        <span class="text-body fw-semibold">
                            {{ $nama_kelas ?? 'Belum ada' }}
                        </span>
                    </div>

                    <div class="vr"></div>

                    <div class="text-muted">
                        Telepon :
                        <span class="text-body fw-semibold">
                            {{ $telepon ?? 'Tidak tersedia' }}
                        </span>
                    </div>
                </div>

                <p class="text-muted mt-2 mb-0">
                    {{ $deskripsi ?? 'Tidak ada catatan' }}
                </p>
            </div>

            @if ($ms_penempatan_siswa_id)
                <div class="flex-shrink-0">
                    <button
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalEditSiswa"
                        wire:click.prevent="$emit('loadDataSiswa', {{ $ms_penempatan_siswa_id }})">

                        <i class="ri-pencil-fill me-1"></i>
                        Edit Siswa
                    </button>
                </div>
            @endif

        </div>

        <!-- Statistik -->
        <div class="row g-3 mt-2">
            <div class="col-lg-3 col-sm-6">
                <div class="p-2 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-danger fs-24">
                                <i class="ri-stack-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 cursor-pointer"
                            role="button"
                            wire:click="$emit('showDetailTagihan', {
                                ms_penempatan_siswa_id: {{ $ms_penempatan_siswa_id }},
                                jenjang: {{ $ms_jenjang_id }},
                                tahunAjar: {{ $ms_tahun_ajar_id }}
                            })"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasDetailTagihan">

                            <div class="text-muted mb-1">
                                Kekurangan :
                                <i class="ri-information-line text-danger fs-24 float-end align-bottom"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="Detail Tagihan"></i>
                            </div>

                            <h5 class="mb-0">
                                RP{{ number_format($totalKekurangan, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="p-2 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                <i class="ri-file-paper-2-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 cursor-pointer"
                            role="button"
                            wire:click="$emit('showHistoriTagihan', {
                                ms_penempatan_siswa_id: {{ $ms_penempatan_siswa_id }},
                                jenjang: {{ $ms_jenjang_id }},
                                tahunAjar: {{ $ms_tahun_ajar_id }}
                            })"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasHistori">

                            <div class="text-muted mb-1">
                                Dibayarkan :
                                <i class="ri-history-line text-success fs-24 float-end align-bottom"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="Histori Pembayaran"></i>
                            </div>

                            <h5 class="mb-0">
                                RP{{ number_format($totalDibayarkan, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-3 col-sm-6">
                <div class="p-2 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24">
                                <i class="ri-wallet-3-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 cursor-pointer"
                            role="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasTabungan">

                            <div class="text-muted mb-1">
                                Tabungan :
                                <i class="ri-money-dollar-circle-line text-primary fs-24 float-end align-bottom"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="Transaksi Tabungan"></i>
                            </div>

                            <h5 class="mb-0">
                                RP{{ number_format($saldoTabunganSiswa, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-3 col-sm-6">
                <div class="p-2 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-warning fs-24">
                                <i class="ri-bank-card-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 cursor-pointer"
                            role="button"
                            wire:click="$emitTo('transaksi-edu-pay-siswa.index', 'showEduPay', {
                                ms_penempatan_siswa_id: {{ $ms_penempatan_siswa_id }},
                            })"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasEduPay">

                            <div class="text-muted mb-1">
                                EduPay Uang Digital :
                                <i class="ri-money-dollar-circle-line text-warning fs-24 float-end align-bottom"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="Transaksi EduPay"></i>
                            </div>

                            <h5 class="mb-0">
                                RP{{ number_format($saldoEduPaySiswa, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>

    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-calendar-event-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-0">
                        Tagihan Siswa
                    </h5>

                    <small class="text-muted">
                        Kelola seluruh tagihan siswa
                    </small>
                </div>

            </div>

            @if ($ms_penempatan_siswa_id)
            <button
                class="btn rounded-pill px-4 btn-primary"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasAddTagihan"
                wire:click="$emit('tambahTagihanSiswa', {{ $ms_penempatan_siswa_id }}, {{ $ms_jenjang_id }}, {{ $ms_tahun_ajar_id }})">

                <i class="ri-play-list-add-line me-1"></i>
                Tagihan Baru

            </button>
            @endif

        </div>

        <div class="table-responsive">
            <table class="table table-hover nowrap align-bottom" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase" style="width: 50px;">Hapus</th>
                        <th class="text-uppercase">Tagihan</th>
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
                        <th class="text-center">
                            @if ($item['total_bayar'] === 0)
                                <a href="#ModalAksiDelete"
                                    data-bs-toggle="modal"
                                    class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit('loadTagihanDelete', {{ $item['ms_tagihan_siswa_id'] }})"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="Hapus Tagihan">

                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            @else
                                <span class="text-muted d-inline-block"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="Tagihan tidak dapat dihapus karena sudah memiliki pembayaran">

                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </span>
                            @endif
                        </th>
                        <td class="text-start">{{ $item['nama_jenis'] }}</td>
                        <td class="text-start">{{ $item['nama_kategori'] }}</td>
                        <td class="text-center">
                            <span class="fw-medium fs-12 text-info">
                                RP{{ number_format($item['jumlah_tagihan_siswa'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fw-medium fs-12 text-success">
                                RP{{ number_format($item['total_bayar'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fw-medium fs-12 text-danger">
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

                                    {{-- Keranjang (aksi utama) --}}
                                    <li class="list-inline-item">
                                        <button type="button"
                                            class="btn btn-primary btn-sm rounded-pill px-3"
                                            title="Masuk Keranjang"
                                            wire:click="tambahKeranjang({{ $item['ms_tagihan_siswa_id'] }})">

                                            <i class="ri-shopping-cart-line me-1"></i>
                                            Keranjang
                                        </button>
                                    </li>

                                    {{-- Cicilan --}}
                                    @if ($item['cicilan_status'] === 'Aktif')

                                        <li class="list-inline-item">
                                            <button type="button"
                                                class="btn btn-soft-secondary btn-sm rounded-pill px-3"
                                                title="Pembayaran Cicilan"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalAksiBayar"
                                                wire:click="$emit('loadCicilan', {{ $item['ms_tagihan_siswa_id'] }})">

                                                <i class="ri-money-dollar-circle-line me-1"></i>
                                                Cicil
                                            </button>
                                        </li>

                                    @else

                                        <li class="list-inline-item">
                                            <button type="button"
                                                class="btn btn-light btn-sm rounded-pill px-3"
                                                disabled>

                                                <i class="ri-money-dollar-circle-line me-1"></i>
                                                Non Cicil
                                            </button>
                                        </li>

                                    @endif

                                    {{-- Edit --}}
                                    <li class="list-inline-item">
                                        <button type="button"
                                            class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                            title="Edit Tagihan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalAksiEdit"
                                            wire:click="$emit('loadTagihanEdit', {{ $item['ms_tagihan_siswa_id'] }})">

                                            <i class="ri-mark-pen-line me-1"></i>
                                            Edit
                                        </button>
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
                                    <h5 class="mt-2">Silakan Pilih Siswa</h5>
                                    <p class="text-muted mb-0">Untuk melakukan transaksi, harap pilih Siswa terlebih dahulu.</p>
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
                            <span class="fw-medium fs-12 text-info">
                                RP{{ number_format($totalEstimasi, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fw-medium fs-12 text-success">
                                RP{{ number_format($totalDibayarkan, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="fw-medium fs-12 text-danger">
                                RP{{ number_format($totalKekurangan, 0, ',', '.') }}
                            </span>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table><!--end table-->
        </div>
        {{-- <div class="hstack gap-2 justify-content-end d-print-none mt-4">
            <a wire:click.prevent="kirimWhatsappTagihan({{ $ms_penempatan_siswa_id }})" class="btn btn-success d-inline-flex align-items-center gap-1"><i class="ri-whatsapp-line align-bottom"></i> Kirim Tagihan</a>
            <a wire:click="cetakSurat({{ $ms_penempatan_siswa_id }})" class="btn btn-danger d-inline-flex align-items-center gap-1"><i class="ri-printer-line align-bottom"></i> Cetak Surat</a>
        </div> --}}
    </div>
</div>

