{{-- CARD UTAMA --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-0">

    {{-- HEADER --}}
    <div class="card-header bg-transparent">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-home-4-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Alamat Tempat Tinggal
                </h5>
                <small class="text-muted">
                    Informasi domisili dan perjalanan calon siswa menuju sekolah.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        @if (!$alamat)
            <div class="text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-map-pin-line"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Data alamat belum tersedia
                </h6>

                <p class="text-muted small mb-0">
                    Informasi alamat calon siswa belum diisi.
                </p>
            </div>
        @else
            <div class="row g-3">

                {{-- INFORMASI ALAMAT --}}
                <div class="col-12">
                    <div class="card border rounded-4 shadow-none mb-0">

                        {{-- HEADER CARD --}}
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-home-4-line"></i>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        Informasi Alamat Tempat Tinggal
                                    </h6>
                                    <small class="text-muted">
                                        Alamat domisili calon siswa saat ini.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- BODY CARD --}}
                        <div class="card-body">
                            <div class="row g-4">

                                {{-- ALAMAT LENGKAP --}}
                                <div class="col-12">
                                    <small class="text-muted d-block mb-1">
                                        Alamat Lengkap
                                    </small>
                                    <div class="fw-semibold text-break">
                                        {{ $alamat->alamat_lengkap ?: '-' }}
                                    </div>
                                </div>

                                {{-- PROVINSI --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Provinsi
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->provinsi ?: '-' }}
                                    </div>
                                </div>

                                {{-- KABUPATEN/KOTA --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Kabupaten/Kota
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->kabupaten ?: '-' }}
                                    </div>
                                </div>

                                {{-- KECAMATAN --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Kecamatan
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->kecamatan ?: '-' }}
                                    </div>
                                </div>

                                {{-- DESA/KELURAHAN --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Desa/Kelurahan
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->kelurahan_desa ?: '-' }}
                                    </div>
                                </div>

                                {{-- RT --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        RT
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->rt ?: '-' }}
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        RW
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->rw ?: '-' }}
                                    </div>
                                </div>

                                {{-- KODE POS --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Kode Pos
                                    </small>
                                    <div class="fw-semibold">
                                        {{ $alamat->kode_pos ?: '-' }}
                                    </div>
                                </div>

                                {{-- JENIS TEMPAT TINGGAL --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Jenis Tempat Tinggal
                                    </small>
                                    <div class="fw-semibold">
                                        @switch($alamat->jenis_tempat_tinggal)
                                            @case('rumah_orang_tua')
                                                Rumah orang tua
                                                @break

                                            @case('rumah_saudara')
                                                Rumah saudara/keluarga
                                                @break

                                            @case('kos_kontrak')
                                                Kos/kontrak
                                                @break

                                            @case('lainnya')
                                                Lainnya
                                                @break

                                            @default
                                                -
                                        @endswitch
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI PERJALANAN --}}
                <div class="col-12">
                    <div class="card border rounded-4 shadow-none mb-0">

                        {{-- HEADER CARD --}}
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-map-pin-line"></i>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        Informasi Perjalanan ke Sekolah
                                    </h6>
                                    <small class="text-muted">
                                        Jarak, waktu tempuh, dan transportasi calon siswa.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- BODY CARD --}}
                        <div class="card-body">
                            <div class="row g-4">

                                {{-- JARAK --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Jarak ke Sekolah
                                    </small>
                                    <div class="fw-semibold">
                                        @if ($alamat->jarak_ke_sekolah !== null && $alamat->jarak_ke_sekolah !== '')
                                            {{ number_format((float) $alamat->jarak_ke_sekolah, 2, ',', '.') }}
                                            km
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                {{-- WAKTU TEMPUH --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Waktu Tempuh ke Sekolah
                                    </small>
                                    <div class="fw-semibold">
                                        @if ($alamat->waktu_tempuh !== null && $alamat->waktu_tempuh !== '')
                                            {{ $alamat->waktu_tempuh }} menit
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                {{-- MODA TRANSPORTASI --}}
                                <div class="col-12 col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        Moda Transportasi
                                    </small>
                                    <div class="fw-semibold">
                                        @switch($alamat->moda_transportasi)
                                            @case('jalan_kaki')
                                                Jalan kaki
                                                @break

                                            @case('sepeda')
                                                Sepeda
                                                @break

                                            @case('sepeda_motor')
                                                Sepeda motor
                                                @break

                                            @case('mobil')
                                                Mobil
                                                @break

                                            @case('angkutan_umum')
                                                Angkutan umum
                                                @break

                                            @case('antar_jemput')
                                                Antar-jemput sekolah
                                                @break

                                            @case('lainnya')
                                                Lainnya
                                                @break

                                            @default
                                                -
                                        @endswitch
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>