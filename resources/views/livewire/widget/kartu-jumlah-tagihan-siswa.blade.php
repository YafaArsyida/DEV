<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    @php
                        $textClass = match($jenisRekapitulasi) {
                            'dibayar' => 'text-success',
                            'kekurangan' => 'text-danger',
                            default => 'text-info',
                        };
                        $bgClass = match($jenisRekapitulasi) {
                            'dibayar' => 'bg-success',
                            'kekurangan' => 'bg-danger',
                            default => 'bg-info',
                        };
                    @endphp
                    <h4 class="card-title mb-0 flex-grow-1">Rekapitulasi Tagihan Siswa</h4>
                    <div class="dropdown">
                        <a href="#" class="text-reset dropdown-btn" data-bs-toggle="dropdown">
                            <span class="fw-semibold text-uppercase fs-12">by:</span>
                            <span class="text-muted">
                                @if($jenisRekapitulasi === 'tagihan') Estimasi
                                @elseif($jenisRekapitulasi === 'dibayar') Dibayarkan
                                @elseif($jenisRekapitulasi === 'kekurangan') Kekurangan
                                @endif
                                <i class="mdi mdi-chevron-down ms-1"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" wire:click.prevent="setJenisRekapitulasi('tagihan')">Estimasi Tagihan</a>
                            <a class="dropdown-item" wire:click.prevent="setJenisRekapitulasi('dibayar')">Tagihan Dibayarkan</a>
                            <a class="dropdown-item" wire:click.prevent="setJenisRekapitulasi('kekurangan')">Kekurangan Tagihan</a>
                        </div>
                    </div>

                    <h2 class="mt-4 ff-secondary fw-semibold {{ $textClass }}">
                        Rp{{ number_format($totalTagihan, 0, ',', '.') }}
                    </h2>
                    <p class="mb-0 text-muted">
                        @if($jenisRekapitulasi === 'tagihan') Total Estimasi
                            @elseif($jenisRekapitulasi === 'dibayar') Dana Dibayarkan
                            @elseif($jenisRekapitulasi === 'kekurangan') Belum Dibayarkan
                        @endif
                    </p>
                </div>
                <div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title {{ $bgClass }} rounded-circle fs-2">
                            <i class="bx bx-receipt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div><!-- end card body -->
    </div> <!-- end card-->
</div> <!-- end col-->
