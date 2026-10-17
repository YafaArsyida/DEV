<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-user-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Data Calon Siswa</h5>
                <small class="text-muted">
                    Identitas dan informasi calon peserta didik.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        @if ($siswa)

            {{-- 1. IDENTITAS SISWA --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
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
                            <span class="fw-semibold">{{ $siswa->nama_siswa ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Nama Panggilan</small>
                            <span class="fw-semibold">{{ $siswa->nama_panggilan ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">NIK</small>
                            <span class="fw-semibold">{{ $siswa->nik ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">NISN</small>
                            <span class="fw-semibold">{{ $siswa->nisn ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Tempat Lahir</small>
                            <span class="fw-semibold">{{ $siswa->tempat_lahir ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                            <span class="fw-semibold">
                                {{ $siswa->tanggal_lahir
                                    ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y')
                                    : '-' }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                            <span class="fw-semibold">
                                {{ in_array($siswa->jenis_kelamin, ['Laki-Laki', 'Perempuan'], true)
                                    ? $siswa->jenis_kelamin
                                    : '-' }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Agama</small>
                            <span class="fw-semibold">{{ $siswa->agama ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Kewarganegaraan</small>
                            <span class="fw-semibold">{{ $siswa->kewarganegaraan ?: '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Cita-cita</small>
                            <span class="fw-semibold">{{ $siswa->cita_cita ?: '-' }}</span>
                        </div>

                        <div class="col-md-8">
                            <small class="text-muted d-block mb-1">Hobi</small>
                            <span class="fw-semibold text-break">{{ $siswa->hobi ?: '-' }}</span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 2. DATA KELUARGA --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
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
                            <span class="fw-semibold">{{ $siswa->anak_ke ?? '-' }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Jumlah Saudara Kandung</small>
                            <span class="fw-semibold">
                                {{ $siswa->jumlah_saudara_kandung ?? '-' }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Jumlah Saudara Tiri</small>
                            <span class="fw-semibold">
                                {{ $siswa->jumlah_saudara_tiri ?? '-' }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Status Yatim/Piatu</small>
                            <span class="fw-semibold">
                                {{ $siswa->status_yatim_piatu ?: '-' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 3. KONDISI KHUSUS DAN KESEHATAN --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
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
                                {{ in_array($siswa->berkebutuhan_khusus, ['Ya', 'Tidak'], true)
                                    ? $siswa->berkebutuhan_khusus
                                    : '-' }}
                            </span>
                        </div>

                        <div class="col-md-8">
                            <small class="text-muted d-block mb-1">
                                Penyakit yang Sedang/Pernah Diderita
                            </small>
                            <span class="fw-semibold text-break">
                                {{ $siswa->penyakit ?: '-' }}
                            </span>
                        </div>

                        <div class="col-md-8">
                            <small class="text-muted d-block mb-1">Alergi</small>
                            <span class="fw-semibold text-break">
                                {{ $siswa->alergi ?: '-' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 4. KONDISI FISIK --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
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
                                {{ $siswa->tinggi_badan !== null && $siswa->tinggi_badan !== ''
                                    ? $siswa->tinggi_badan . ' cm'
                                    : '-' }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Berat Badan</small>
                            <span class="fw-semibold">
                                {{ $siswa->berat_badan !== null && $siswa->berat_badan !== ''
                                    ? $siswa->berat_badan . ' kg'
                                    : '-' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 5. BANTUAN SOSIAL --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
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
                                explode(',', $siswa->penerima_bantuan_sosial ?? '')
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
                                Nomor Bantuan Sosial
                            </small>
                            <span class="fw-semibold text-break">
                                {{ $siswa->nomor_bantuan_sosial ?: '-' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

        @else

            <div class="text-center py-4">
                <p class="text-muted mb-0">
                    Data calon siswa belum diisi.
                </p>
            </div>

        @endif

    </div>
</div>