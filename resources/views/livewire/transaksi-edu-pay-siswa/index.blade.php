{{-- Be like water. --}}
<div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasEduPay" aria-labelledby="offcanvasEduPayLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasEduPayLabel">EduPay - Uang Digital Teman Sekolah</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            {{-- DATA SISWA --}}
            <div class="col-xxl-4 pe-1">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h4>{{ $nama_siswa ?? 'Siswa belum dipilih' }}</h4>
                                <div class="hstack gap-3 flex-wrap">
                                    <div><a href="#" class="text-primary d-block">{{ $ms_penempatan_siswa_id }}-TemanSekolah</a></div>
                                    <div class="vr"></div>
                                    <div class="text-muted">EduCard : <span class="text-warning fw-medium">{{ $educard ?? 'Belum ada' }}</span></div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Kelas : <span class="text-body fw-medium">{{ $nama_kelas ?? 'Belum ada' }}</span></div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Telepon : <span class="text-body fw-medium">{{ $telepon ?? 'Tidak tersedia' }}</span></div>
                                    {{-- <div class="vr"></div> --}}
                                    {{-- <div class="text-muted">Virtual Akun : <span class="text-body fw-medium">9888838383838</span></div> --}}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mt-4 text-muted">
                            <p>{{ $alamat ?? 'Tidak ada alamat' }}</p>
                        </div> --}}
                        <div class="mt-4 row">
                            <div class="col-lg-4 col-sm-6">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-info fs-24">
                                                <i class="ri-inbox-archive-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Saldo :</p>
                                            <h5 class="mb-0">RP{{ number_format($saldo_edupay_siswa, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Masuk :</p>
                                            <h5 class="mb-0">RP{{ number_format($total_pemasukan_edupay_siswa, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-4 col-sm-6">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-danger fs-24">
                                                <i class="ri-file-copy-2-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Keluar :</p>
                                            <h5 class="mb-0">RP{{ number_format($total_pengeluaran_edupay_siswa, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <div class="mt-4">        
                            <!-- Base Example -->
                            <div class="accordion" id="default-accordion-example">
                                <div class="accordion-item shadow">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Top-Up
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                                        <div class="accordion-body">
                                            <h5 class="fs-15">Top Up EduPay</h5>
                                            <p class="text-muted">Top-Up adalah pencatatan <b>penambahan</b> nominal saldo EduPay melalui transaksi pengisian ulang.</p>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">Rp</span>
                                                <input type="number" class="form-control" placeholder="123" wire:model.defer="nominal_topup" aria-label="123" aria-describedby="addon-wrapping">
                                            </div>
                                            @error('nominal_topup') <span class="text-danger">{{ $message }}</span> @enderror

                                            <!-- Input deskripsi topup -->
                                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                <input type="text" 
                                                    class="form-control" 
                                                    wire:model.defer="deskripsi_topup" 
                                                    placeholder="deskripsi transaksi (bila perlu)" 
                                                    aria-label="deskripsi">
                                                @error('deskripsi_topup') <span class="text-danger">{{ $message }}</span> @enderror

                                                <!-- Tombol simpan -->
                                                <button wire:click="simpanTopUp" class="btn btn-sm btn-success">
                                                    <i class="ri-printer-line align-bottom me-1"></i> Top-Up
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item shadow">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Tarik Tunai
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                                        <div class="accordion-body">
                                            <h5 class="fs-15">Tarik Tunai EduPay</h5>
                                            <p class="text-muted">Tarik Tunai adalah pencatatan <b>pengeluaran</b> nominal saldo EduPay yang diberikan kepada siswa atau orang tua untuk kebutuhan tertentu.</p>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">RP</span>
                                                <input type="number" 
                                                    class="form-control" 
                                                    placeholder="123" 
                                                    wire:model.defer="nominal_penarikan" 
                                                    aria-label="123" 
                                                    aria-describedby="addon-wrapping">
                                            </div>
                                            @error('nominal_penarikan') <span class="text-danger">{{ $message }}</span> @enderror

                                            <!-- Input deskripsi pengeluaran -->
                                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                <input type="text" 
                                                    class="form-control" 
                                                    wire:model.defer="deskripsi_penarikan" 
                                                    placeholder="deskripsi transaksi (bila perlu)" 
                                                    aria-label="deskripsi">
                                                @error('deskripsi_penarikan') <span class="text-danger">{{ $message }}</span> @enderror

                                                <!-- Tombol simpan -->
                                                <button wire:click="simpanPengeluaran" class="btn btn-sm btn-danger">
                                                    <i class="ri-printer-line align-bottom me-1"></i> Tarik Tunai
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!--end col-->
            {{-- DATA EduPay --}}
            <div class="col-xxl-8 ps-0">
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
                                                    {{-- pengembalian dana dari transferan --}}
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
                            <a href="" 
                            wire:click.prevent="" 
                            class="btn btn-sm btn-success">
                                <i class="ri-printer-line align-bottom me-1"></i> Bayarkan
                            </a>
                            {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
