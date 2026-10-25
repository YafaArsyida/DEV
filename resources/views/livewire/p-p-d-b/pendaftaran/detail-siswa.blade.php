<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-user-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">Data Calon Siswa</h5>
                    <small class="text-muted">
                        Identitas calon siswa yang telah tersimpan.
                    </small>
                </div>
            </div>

            @if ($bisaUbahData)
                <a
                    href="{{ request()->fullUrlWithQuery(['mode' => 'edit-siswa']) }}"
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
    <div class="card-body">
        {{-- NOMOR PENDAFTARAN --}}
        {{-- <div class="p-3 bg-light rounded-3 mb-4">
            <div class="text-muted small mb-1">
                Nomor Pendaftaran
            </div>

            <div class="fw-bold text-primary">
                {{ $pendaftaran->nomor_pendaftaran ?: '-' }}
            </div>
        </div> --}}
        
        {{-- 1. Identitas Siswa --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Identitas Siswa</h6>
                        <small class="text-muted">
                            Informasi identitas dan data pribadi calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Nama Lengkap</small>
                        <span class="fw-semibold">{{ $calonSiswa->nama_siswa ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Nama Panggilan</small>
                        <span class="fw-semibold">{{ $calonSiswa->nama_panggilan ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">NIK</small>
                        <span class="fw-semibold">{{ $calonSiswa->nik ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">NISN</small>
                        <span class="fw-semibold">{{ $calonSiswa->nisn ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Tempat Lahir</small>
                        <span class="fw-semibold">{{ $calonSiswa->tempat_lahir ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->tanggal_lahir
                                ? \Carbon\Carbon::parse($calonSiswa->tanggal_lahir)->translatedFormat('d F Y')
                                : '-' }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                        <span class="fw-semibold">
                            {{ in_array($calonSiswa->jenis_kelamin, ['Laki-Laki', 'Perempuan'], true)
                                ? $calonSiswa->jenis_kelamin
                                : '-' }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Agama</small>
                        <span class="fw-semibold">{{ $calonSiswa->agama ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Kewarganegaraan</small>
                        <span class="fw-semibold">{{ $calonSiswa->kewarganegaraan ?: '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Cita-cita</small>
                        <span class="fw-semibold">{{ $calonSiswa->cita_cita ?: '-' }}</span>
                    </div>

                    <div class="col-md-8">
                        <small class="text-muted d-block mb-1">Hobi</small>
                        <span class="fw-semibold">{{ $calonSiswa->hobi ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Data Keluarga --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                            <i class="ri-parent-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Data Keluarga</h6>
                        <small class="text-muted">
                            Urutan anak dan informasi kondisi keluarga.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Anak ke-</small>
                        <span class="fw-semibold">{{ $calonSiswa->anak_ke ?? '-' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Jumlah Saudara Kandung</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->jumlah_saudara_kandung ?? '-' }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Jumlah Saudara Tiri</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->jumlah_saudara_tiri ?? '-' }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Status Yatim/Piatu</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->status_yatim_piatu }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Kondisi Khusus dan Kesehatan --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                            <i class="ri-heart-pulse-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Kondisi Khusus dan Kesehatan</h6>
                        <small class="text-muted">
                            Informasi kesehatan untuk mendukung kebutuhan siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Berkebutuhan Khusus</small>
                        <span class="fw-semibold">
                            {{ in_array($calonSiswa->berkebutuhan_khusus, ['Ya', 'Tidak'], true)
                                ? $calonSiswa->berkebutuhan_khusus
                                : '-' }}
                        </span>
                    </div>

                    <div class="col-md-8">
                        <small class="text-muted d-block mb-1">
                            Penyakit yang Sedang/Pernah Diderita
                        </small>
                        <span class="fw-semibold text-break">
                            {{ $calonSiswa->penyakit ?: '-' }}
                        </span>
                    </div>

                    <div class="col-md-8">
                        <small class="text-muted d-block mb-1">Alergi</small>
                        <span class="fw-semibold text-break">
                            {{ $calonSiswa->alergi ?: '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Kondisi Fisik --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                            <i class="ri-ruler-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Kondisi Fisik</h6>
                        <small class="text-muted">
                            Informasi pengukuran fisik calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Tinggi Badan</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->tinggi_badan !== null && $calonSiswa->tinggi_badan !== ''
                                ? $calonSiswa->tinggi_badan . ' cm'
                                : '-' }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Berat Badan</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->berat_badan !== null && $calonSiswa->berat_badan !== ''
                                ? $calonSiswa->berat_badan . ' kg'
                                : '-' }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Ukuran Baju</small>
                        <span class="fw-semibold">
                            {{ $calonSiswa->ukuran_baju ?: '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Bantuan Sosial --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Bantuan Sosial</h6>
                        <small class="text-muted">
                            Informasi bantuan sosial yang diterima siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @php
                    $bantuanSosial = array_filter(
                        array_map(
                            'trim',
                            explode(',', $calonSiswa->penerima_bantuan_sosial ?? '')
                        )
                    );

                    $labelBantuanSosial = [
                        'KPS' => 'Kartu Perlindungan Sosial (KPS)',
                        'KKS' => 'Kartu Keluarga Sejahtera (KKS)',
                        'PKH' => 'Program Keluarga Harapan (PKH)',
                        'KIP' => 'Kartu Indonesia Pintar (KIP)',
                    ];
                @endphp

                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted d-block mb-2">
                            Jenis Bantuan yang Diterima
                        </small>

                        @if (count($bantuanSosial))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($bantuanSosial as $bantuan)
                                    <span class="badge bg-light text-body border rounded-pill px-3 py-2">
                                        {{ $labelBantuanSosial[$bantuan] ?? $bantuan }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="fw-semibold">-</span>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Nomor KS/KKS/PKH/KIP
                        </small>
                        <span class="fw-semibold text-break">
                            {{ $calonSiswa->nomor_bantuan_sosial ?: '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- NAVIGASI --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">

            <a href="{{ route('ppdb.pendaftaran.jalur', [
                    'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                ]) }}"
                class="btn btn-light border rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>
                Jalur
            </a>

            <a href="{{ route('ppdb.pendaftaran.orang-tua', [
                    'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                ]) }}"
                class="btn btn-primary rounded-pill px-4"
            >
                Data Orang Tua
                <i class="ri-arrow-right-line ms-1"></i>
            </a>

        </div>

    </div>
</div>