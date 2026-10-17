{{-- DATA ORANG TUA --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header bg-transparent border-bottom p-3 p-md-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-team-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-semibold mb-1">Data Orang Tua / Wali</h5>
                <p class="text-muted mb-0 small">
                    Informasi identitas, pendidikan, pekerjaan, dan kontak orang tua atau wali calon siswa.
                </p>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if ($orangTua)
            {{-- DATA AYAH --}}
            <div class="card border rounded-4 shadow-none mb-4">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-user-line"></i>
                            </div>
                        </div>

                        <div>
                            <h6 class="fw-semibold mb-1">Data Ayah</h6>
                            <small class="text-muted">
                                Informasi ayah kandung calon siswa.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Nama Lengkap</small>
                            <div class="fw-semibold">{{ $orangTua->nama_ayah ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">NIK</small>
                            <div class="fw-semibold">{{ $orangTua->nik_ayah ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tempat Lahir</small>
                            <div class="fw-semibold">{{ $orangTua->tempat_lahir_ayah ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                            <div class="fw-semibold">
                                {{ $orangTua->tanggal_lahir_ayah
                                    ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_ayah)->translatedFormat('d F Y')
                                    : '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Berkebutuhan Khusus</small>
                            <div class="fw-semibold">
                                {{ $orangTua->berkebutuhan_khusus_ayah ?: '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Pendidikan Terakhir</small>
                            <div class="fw-semibold">
                                @switch($orangTua->pendidikan_ayah)
                                    @case('TIDAK_SEKOLAH')
                                        Tidak Sekolah
                                        @break
                                    @case('SD')
                                        SD/Sederajat
                                        @break
                                    @case('SMP')
                                        SMP/Sederajat
                                        @break
                                    @case('SMA')
                                        SMA/SMK/Sederajat
                                        @break
                                    @case('D1_D2_D3')
                                        Diploma
                                        @break
                                    @case('S1')
                                        S1/D4
                                        @break
                                    @case('S2')
                                        S2
                                        @break
                                    @case('S3')
                                        S3
                                        @break
                                    @default
                                        -
                                @endswitch
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Pekerjaan</small>
                            <div class="fw-semibold">{{ $orangTua->pekerjaan_ayah ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Penghasilan per Bulan</small>
                            <div class="fw-semibold">
                                @switch($orangTua->penghasilan_ayah)
                                    @case('TIDAK_ADA')
                                        Tidak Berpenghasilan
                                        @break
                                    @case('KURANG_1JT')
                                        Kurang dari Rp1.000.000
                                        @break
                                    @case('1JT_3JT')
                                        Rp1.000.000–Rp3.000.000
                                        @break
                                    @case('3JT_5JT')
                                        Rp3.000.000–Rp5.000.000
                                        @break
                                    @case('5JT_7JT')
                                        Rp5.000.000–Rp7.000.000
                                        @break
                                    @case('LEBIH_7JT')
                                        Lebih dari Rp7.000.000
                                        @break
                                    @default
                                        -
                                @endswitch
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Nomor Telepon / WhatsApp</small>
                            <div class="fw-semibold">{{ $orangTua->telepon_ayah ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATA IBU --}}
            <div class="card border rounded-4 shadow-none mb-4">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-20">
                                <i class="ri-user-heart-line"></i>
                            </div>
                        </div>

                        <div>
                            <h6 class="fw-semibold mb-1">Data Ibu</h6>
                            <small class="text-muted">
                                Informasi ibu kandung calon siswa.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Nama Lengkap</small>
                            <div class="fw-semibold">{{ $orangTua->nama_ibu ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">NIK</small>
                            <div class="fw-semibold">{{ $orangTua->nik_ibu ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tempat Lahir</small>
                            <div class="fw-semibold">{{ $orangTua->tempat_lahir_ibu ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                            <div class="fw-semibold">
                                {{ $orangTua->tanggal_lahir_ibu
                                    ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_ibu)->translatedFormat('d F Y')
                                    : '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Berkebutuhan Khusus</small>
                            <div class="fw-semibold">
                                {{ $orangTua->berkebutuhan_khusus_ibu ?: '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Pendidikan Terakhir</small>
                            <div class="fw-semibold">
                                @switch($orangTua->pendidikan_ibu)
                                    @case('TIDAK_SEKOLAH')
                                        Tidak Sekolah
                                        @break
                                    @case('SD')
                                        SD/Sederajat
                                        @break
                                    @case('SMP')
                                        SMP/Sederajat
                                        @break
                                    @case('SMA')
                                        SMA/SMK/Sederajat
                                        @break
                                    @case('D1_D2_D3')
                                        Diploma
                                        @break
                                    @case('S1')
                                        S1/D4
                                        @break
                                    @case('S2')
                                        S2
                                        @break
                                    @case('S3')
                                        S3
                                        @break
                                    @default
                                        -
                                @endswitch
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Pekerjaan</small>
                            <div class="fw-semibold">{{ $orangTua->pekerjaan_ibu ?: '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Penghasilan per Bulan</small>
                            <div class="fw-semibold">
                                @switch($orangTua->penghasilan_ibu)
                                    @case('TIDAK_ADA')
                                        Tidak Berpenghasilan
                                        @break
                                    @case('KURANG_1JT')
                                        Kurang dari Rp1.000.000
                                        @break
                                    @case('1JT_3JT')
                                        Rp1.000.000–Rp3.000.000
                                        @break
                                    @case('3JT_5JT')
                                        Rp3.000.000–Rp5.000.000
                                        @break
                                    @case('5JT_7JT')
                                        Rp5.000.000–Rp7.000.000
                                        @break
                                    @case('LEBIH_7JT')
                                        Lebih dari Rp7.000.000
                                        @break
                                    @default
                                        -
                                @endswitch
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Nomor Telepon / WhatsApp</small>
                            <div class="fw-semibold">{{ $orangTua->telepon_ibu ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATA WALI --}}
            @if (filled($orangTua->nama_wali))
                <div class="card border border-primary-subtle rounded-4 shadow-none mb-0">
                    <div class="card-header bg-primary-subtle bg-opacity-25">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary text-white rounded-circle fs-20">
                                    <i class="ri-user-settings-line"></i>
                                </div>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">Data Wali</h6>
                                <small class="text-muted">
                                    Informasi wali yang bertanggung jawab atas calon siswa.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Nama Lengkap</small>
                                <div class="fw-semibold">{{ $orangTua->nama_wali ?: '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">NIK</small>
                                <div class="fw-semibold">{{ $orangTua->nik_wali ?: '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Hubungan dengan Siswa</small>
                                <div class="fw-semibold">
                                    @switch($orangTua->hubungan_wali)
                                        @case('KAKEK')
                                            Kakek
                                            @break
                                        @case('NENEK')
                                            Nenek
                                            @break
                                        @case('PAMAN')
                                            Paman
                                            @break
                                        @case('BIBI')
                                            Bibi
                                            @break
                                        @case('SAUDARA')
                                            Saudara
                                            @break
                                        @case('LAINNYA')
                                            Lainnya
                                            @break
                                        @default
                                            -
                                    @endswitch
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Tempat Lahir</small>
                                <div class="fw-semibold">{{ $orangTua->tempat_lahir_wali ?: '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                                <div class="fw-semibold">
                                    {{ $orangTua->tanggal_lahir_wali
                                        ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_wali)->translatedFormat('d F Y')
                                        : '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Berkebutuhan Khusus</small>
                                <div class="fw-semibold">
                                    {{ $orangTua->berkebutuhan_khusus_wali ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Pekerjaan</small>
                                <div class="fw-semibold">{{ $orangTua->pekerjaan_wali ?: '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Nomor Telepon / WhatsApp</small>
                                <div class="fw-semibold">{{ $orangTua->telepon_wali ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-team-line"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">Data orang tua belum diisi</h6>
                <p class="text-muted small mb-0">
                    Informasi orang tua atau wali belum tersedia pada pendaftaran ini.
                </p>
            </div>
        @endif
    </div>
</div>