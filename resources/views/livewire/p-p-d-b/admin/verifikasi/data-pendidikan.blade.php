{{-- CARD UTAMA --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-0">

    {{-- HEADER --}}
    <div class="card-header bg-transparent">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-school-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Asal Pendidikan
                </h5>
                <small class="text-muted">
                    Informasi pendidikan sebelumnya yang telah tersimpan.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body p-3 p-md-4">
        @if (!$pendidikan)
            <div class="text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-school-line"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Data pendidikan belum tersedia
                </h6>

                <p class="text-muted small mb-0">
                    Informasi pendidikan calon siswa belum diisi.
                </p>
            </div>
        @else
            <div class="row g-4">

                {{-- JENJANG ASAL --}}
                <div class="col-12 col-md-6">
                    <small class="text-muted d-block mb-1">
                        Jenjang Pendidikan Asal
                    </small>

                    <span class="fw-semibold">
                        @switch($pendidikan->jenjang_asal)
                            @case('TK')
                                TK
                                @break

                            @case('RA')
                                RA
                                @break

                            @case('PAUD')
                                PAUD
                                @break

                            @case('TIDAK_ADA')
                                Belum Pernah Bersekolah
                                @break

                            @default
                                -
                        @endswitch
                    </span>
                </div>

                @if ($pendidikan->jenjang_asal !== 'TIDAK_ADA')

                    {{-- NAMA SEKOLAH ASAL --}}
                    <div class="col-12 col-md-6">
                        <small class="text-muted d-block mb-1">
                            Nama Sekolah Asal
                        </small>

                        <div class="fw-semibold text-break">
                            {{ $pendidikan->asal_sekolah ?: '-' }}
                        </div>
                    </div>

                    {{-- NPSN --}}
                    <div class="col-12 col-md-6">
                        <small class="text-muted d-block mb-1">
                            NPSN
                        </small>

                        <div class="fw-semibold">
                            {{ $pendidikan->npsn_asal_sekolah ?: '-' }}
                        </div>
                    </div>

                    {{-- ALAMAT SEKOLAH --}}
                    <div class="col-12 col-md-6">
                        <small class="text-muted d-block mb-1">
                            Alamat Asal Sekolah
                        </small>

                        <div class="fw-semibold text-break">
                            {{ $pendidikan->alamat_asal_sekolah ?: '-' }}
                        </div>
                    </div>

                    {{-- TAHUN LULUS --}}
                    <div class="col-12 col-md-6">
                        <small class="text-muted d-block mb-1">
                            Tahun Lulus
                        </small>

                        <div class="fw-semibold">
                            {{ $pendidikan->tahun_lulus ?: '-' }}
                        </div>
                    </div>

                    {{-- NOMOR IJAZAH --}}
                    <div class="col-12 col-md-6">
                        <small class="text-muted d-block mb-1">
                            Nomor Peserta / Bukti Kelulusan
                        </small>

                        <div class="fw-semibold text-break">
                            {{ $pendidikan->nomor_ijazah ?: '-' }}
                        </div>
                    </div>

                @else

                    {{-- INFORMASI BELUM PERNAH BERSEKOLAH --}}
                    <div class="col-12">
                        <div class="alert alert-light border rounded-3 mb-0">
                            <div class="d-flex align-items-start gap-2">
                                <i class="ri-information-line fs-18 text-primary"></i>

                                <small class="text-muted">
                                    Calon siswa tercatat belum pernah bersekolah
                                    di TK, RA, atau PAUD.
                                </small>
                            </div>
                        </div>
                    </div>

                @endif

            </div>
        @endif
    </div>
</div>