{{-- Close your eyes. Count to one. That is how long forever feels. --}}
<div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasTabungan" aria-labelledby="offcanvasTabunganLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasTabunganLabel">Tabungan Siswa</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            {{-- DATA SISWA --}}
            <div class="col-xxl-4 pe-1">
                <div class="card sticky-side-div">
                    <div class="card-body p-4">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h4>{{ $nama_siswa ?? 'Siswa belum dipilih' }}</h4>
                                <div class="hstack gap-3 flex-wrap">
                                    <div><a href="#" class="text-primary d-block">29029</a></div>
                                    <div class="vr"></div>
                                    <div class="text-muted"> Tanggal Lahir : <span class="text-body fw-medium">{{ $tanggal_lahir ?? 'Belum disetting' }}</span></div>
                                    <div class="vr"></div>
                                    <div class="text-muted">Telepon : <span class="text-body fw-medium">{{ $telepon ?? 'Tidak tersedia' }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-muted">
                            <p>{{ $alamat ?? 'Tidak ada alamat' }}</p>
                        </div>
                        <div class="row">
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
                                            <h5 class="mb-0">RP{{ number_format($saldo_tabungan_siswa, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-4 col-sm-6">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-money-dollar-circle-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Total Kredit:</p>
                                            <h5 class="mb-0">RP{{ number_format($total_kredit_tabungan, 0, ',', '.') }}</h5>
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
                                            <p class="text-muted mb-1">Total Debit:</p>
                                            <h5 class="mb-0">RP{{ number_format($total_debit_tabungan, 0, ',', '.') }}</h5>
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
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Kredit
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                                        <div class="accordion-body">
                                            <h5 class="fs-15">Kredit</h5>
                                            <p class="text-muted">Kredit adalah Pencatatan <b>penambahan</b> nominal saldo tabungan</p>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">RP</span>
                                                <input type="number" class="form-control" placeholder="123" wire:model.defer="nominal_kredit" aria-label="123" aria-describedby="addon-wrapping">
                                            </div>
                                            @error('nominal_kredit') <span class="text-danger">{{ $message }}</span> @enderror

                                            <!-- Input deskripsi kredit -->
                                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                <input type="text" 
                                                    class="form-control" 
                                                    wire:model.defer="deskripsi_kredit" 
                                                    placeholder="deskripsi transaksi (bila perlu)" 
                                                    aria-label="deskripsi">
                                                @error('deskripsi_kredit') <span class="text-danger">{{ $message }}</span> @enderror

                                                <!-- Tombol simpan -->
                                                <button wire:click="simpanKredit" class="btn btn-success">
                                                    <i class="ri-printer-line align-bottom me-1"></i> Kredit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item shadow">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Debit
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                                        <div class="accordion-body">
                                            <h5 class="fs-15">Debit</h5>
                                            <p class="text-muted">Debit adalah pencatatan <b>pengurangan</b> nominal saldo tabungan</p>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">RP</span>
                                                <input type="number" 
                                                    class="form-control" 
                                                    placeholder="123" 
                                                    wire:model.defer="nominal_debit" 
                                                    aria-label="123" 
                                                    aria-describedby="addon-wrapping">
                                            </div>
                                            @error('nominal_debit') <span class="text-danger">{{ $message }}</span> @enderror

                                            <!-- Input deskripsi debit -->
                                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                <input type="text" 
                                                    class="form-control" 
                                                    wire:model.defer="deskripsi_debit" 
                                                    placeholder="deskripsi transaksi (bila perlu)" 
                                                    aria-label="deskripsi">
                                                @error('deskripsi_debit') <span class="text-danger">{{ $message }}</span> @enderror

                                                <!-- Tombol simpan -->
                                                <button wire:click="simpanDebit" class="btn btn-danger">
                                                    <i class="ri-printer-line align-bottom me-1"></i> Debit
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
            {{-- DATA TABUNGAN --}}
            <div class="col-xxl-8 ps-0">
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
                                                wire:click.prevent="$emit('confirmDelete', {{ $item->ms_tabungan_siswa_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi Tabungan">
                                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                                </a>
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

                                            <td>{{ $item->ms_pengguna->nama }}</td>
                                            <td>
                                                <span class="fw-medium text-success">
                                                    {{ $item->jenis_transaksi === 'setoran' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-medium text-danger">
                                                    {{ $item->jenis_transaksi === 'penarikan' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="fw-medium text-info">
                                                    RP{{ number_format($item->saldo, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-start">
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Pesan Transaksi">
                                                        <a href="" wire:click.prevent="kirimWhatsapp({{ $item->ms_tabungan_siswa_id }})" class="btn btn-sm btn-soft-success d-inline-flex align-items-center gap-1">
                                                            <i class="ri-whatsapp-line align-bottom"></i> Kirim Whatsapp
                                                        </a>
                                                    </li>
                                                    {{-- <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Kirim Bukti Transaksi">
                                                        <a href="" class="btn btn-danger d-inline-flex align-items-center gap-1">
                                                            <i class="ri-printer-line align-bottom"></i> Cetak
                                                        </a>
                                                    </li> --}}
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Tidak ada transaksi tabungan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table><!--end table-->
                        </div>

                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">   
                            <a href="" 
                            wire:click.prevent="" 
                            class="btn btn-success">
                                <i class="ri-printer-line align-bottom me-1"></i> Bayarkan
                            </a>
                            {{-- <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
