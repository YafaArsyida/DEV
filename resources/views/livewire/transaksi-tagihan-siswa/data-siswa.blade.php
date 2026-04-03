
<div class="card-body p-4 pb-0">
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
        <div class="flex-shrink-0">
            @if ($ms_penempatan_siswa_id)
                <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Siswa">
                    <a href="#ModalEditSiswa" data-bs-toggle="modal" class="btn btn-light" wire:click.prevent="$emit('loadDataSiswa', {{ $ms_penempatan_siswa_id }})">
                        <i class="ri-pencil-fill align-bottom"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div class="mt-4 text-muted">
        <p>{{ $deskripsi ?? 'Tidak ada catatan' }}</p>
    </div>  

    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="p-2 border border-dashed rounded">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-danger fs-24">
                            <i class="ri-stack-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <a href="#ModalDetailTagihan" data-bs-toggle="modal" class="text-muted mb-1">Kekurangan :
                            <i class="ri-information-line text-danger fs-24 float-end align-bottom" role="button"
                                wire:click.prevent="$emit('showDetailTagihan', {
                                    ms_penempatan_siswa_id: {{ $ms_penempatan_siswa_id }},
                                    jenjang: {{ $ms_jenjang_id }},
                                    tahunAjar: {{ $ms_tahun_ajar_id }}
                                })" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                title="Detail Tagihan"></i>
                        </a>
                        <h5 class="mb-0">RP{{ number_format($totalKekurangan, 0, ',', '.') }}</h5>
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
                    <div class="flex-grow-1">
                        <a href="" class="text-muted mb-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasHistori"
                            aria-controls="offcanvasHistori">Dibayarkan :
                            <i class="ri-history-line text-success fs-24 float-end align-bottom" role="button"
                                wire:click.prevent="$emit('showHistoriTagihan', {
                                    ms_penempatan_siswa_id: {{ $ms_penempatan_siswa_id }},
                                    jenjang: {{ $ms_jenjang_id }},
                                    tahunAjar: {{ $ms_tahun_ajar_id }}
                                })" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                title="Histori Pembayaran"></i>
                        </a>
                        {{-- <button type="button" class="btn btn-soft-secondary shadow-none float-end align-bottom">
                            ALL
                        </button> --}}
                        <h5 class="mb-0">RP{{ number_format($totalDibayarkan, 0, ',', '.') }}</h5>
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
                    <div class="flex-grow-1">
                        <a href="" class="text-muted mb-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTabungan"
                            aria-controls="offcanvasTabungan">Tabungan :
                            <i class="ri-money-dollar-circle-line text-primary fs-24 float-end align-bottom" role="button"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                title="Transaksi Tabungan"></i>
                        </a>
                        <h5 class="mb-0">RP{{ number_format($saldoTabunganSiswa, 0, ',', '.') }}</h5>
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
                    <div class="flex-grow-1">
                        <a href="" class="text-muted mb-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEduPay"
                            aria-controls="offcanvasEduPay">EduPay Uang Digital :
                            <i class="ri-money-dollar-circle-line text-warning fs-24 float-end align-bottom" role="button"
                                wire:click.prevent="$emitTo('transaksi-edu-pay-siswa.index', 'showEduPay', {
                                    ms_penempatan_siswa_id: {{ $ms_penempatan_siswa_id }},
                                })" {{--
                                wire:click.prevent="$emitTo('transaksi-edu-pay-siswa.index', 'showEduPay', {{ $ms_siswa_id }}, {{ $ms_penempatan_siswa_id }})"
                                --}} data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                title="Transaksi EduPay"></i>
                        </a>
                        <h5 class="mb-0">RP{{ number_format($saldoEduPaySiswa, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
</div>

