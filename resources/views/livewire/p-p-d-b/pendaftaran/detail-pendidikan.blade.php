
<div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-school-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">Asal Pendidikan</h5>
                        <small class="text-muted">
                            Informasi pendidikan sebelumnya yang telah tersimpan.
                        </small>
                    </div>
                </div>

                @if ($bisaUbahData)
                    <a
                        href="{{ request()->fullUrlWithQuery(['mode' => 'edit-pendidikan']) }}"
                        class="btn btn-outline-primary rounded-pill px-3"
                    >
                        <i class="ri-edit-line me-1"></i>
                        Ubah Data
                    </a>
                @else
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                        <i class="ri-lock-line me-1"></i>
                        Tidak Dapat Diubah
                    </span>
                @endif

            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
                {{-- JENJANG ASAL --}}
                <div class="col-md-6">
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
                    {{-- ASAL SEKOLAH --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Nama Sekolah Asal
                        </small>

                        <span class="fw-semibold">
                            {{ $pendidikan->asal_sekolah ?: '-' }}
                        </span>
                    </div>

                    {{-- NPSN --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            NPSN
                        </small>

                        <span class="fw-semibold">
                            {{ $pendidikan->npsn_asal_sekolah ?: '-' }}
                        </span>
                    </div>

                    {{-- ALAMAT ASAL SEKOLAH --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Alamat Asal Sekolah
                        </small>

                        <span class="fw-semibold">
                            {{ $pendidikan->alamat_asal_sekolah ?: '-' }}
                        </span>
                    </div>

                    {{-- TAHUN LULUS --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Tahun Lulus
                        </small>

                        <span class="fw-semibold">
                            {{ $pendidikan->tahun_lulus ?: '-' }}
                        </span>
                    </div>

                    {{-- NOMOR IJAZAH --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Nomor Peserta / Bukti Kelulusan
                        </small>

                        <span class="fw-semibold">
                            {{ $pendidikan->nomor_ijazah ?: '-' }}
                        </span>
                    </div>

                @else
                    <div class="col-12">
                        <div class="alert alert-light border rounded-3 mb-0">
                            <div class="d-flex gap-2">
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

            {{-- NAVIGASI --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">

                <a href="{{ route('ppdb.pendaftaran.alamat') }}"
                    class="btn btn-light border rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Data Alamat
                </a>

                <a href="{{ route('ppdb.pendaftaran.berkas') }}"
                    class="btn btn-primary rounded-pill px-4">
                    Data Berkas
                    <i class="ri-arrow-right-line ms-1"></i>
                </a>

            </div>

        </div>
    </div>
</div>
