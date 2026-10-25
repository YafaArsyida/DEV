@extends('template_portal_ppdb.v_template')

@section('title', 'Review Pendaftaran')

@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">

                {{-- PROGRESS PENDAFTARAN --}}
                @include('ppdb.orang-tua.layouts.progress-pendaftaran', [
                    'stepAktif' => $stepAktif
                ])

                {{-- Review Pendaftaran --}}
                @php
                    $calonSiswa = $pendaftaran->ppdb_calon_siswa;
                    $orangTua = $pendaftaran->ppdb_orangtua;
                    $alamat = $pendaftaran->ppdb_alamat;
                    $pendidikan = $pendaftaran->ppdb_pendidikan;

                    $statusLabel = [
                        'draft' => 'Draft',
                        'pengecekan' => 'Pengecekan',
                        'diterima' => 'Diterima',
                        'perbaikan' => 'Perlu Perbaikan',
                    ];

                    $statusClass = [
                        'draft' => 'bg-secondary-subtle text-secondary',
                        'pengecekan' => 'bg-warning-subtle text-warning',
                        'diterima' => 'bg-success-subtle text-success',
                        'perbaikan' => 'bg-danger-subtle text-danger',
                    ];

                    $daftarBerkas = [
                        'KARTU_KELUARGA' => 'Kartu Keluarga',
                        'AKTA_KELAHIRAN' => 'Akta Kelahiran',
                        'PAS_FOTO' => 'Pas Foto',
                        'DOKUMEN_PENDUKUNG' => 'Dokumen Pendukung',
                    ];

                    $berkasByJenis = $pendaftaran->ppdb_berkas->keyBy('jenis_berkas');
                @endphp

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-check-double-line"></i>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-1">Review Pendaftaran</h5>
                                    <small class="text-muted">
                                        Pastikan informasi yang Anda masukkan sudah benar.
                                    </small>
                                </div>
                            </div>

                            <span class="text-muted small">Langkah 7 dari 8</span>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- NOMOR PENDAFTARAN --}}
                        <div class="border rounded-4 p-3 mb-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                <div>
                                    <div class="text-muted small mb-1">
                                        Nomor Pendaftaran
                                    </div>

                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                        <h5 class="mb-0">
                                            {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
                                        </h5>

                                        @if (!empty($pendaftaran->nomor_pendaftaran))
                                            <button type="button"
                                                class="btn btn-sm btn-light btn-icon rounded-circle"
                                                title="Salin nomor pendaftaran"
                                                onclick="copyToClipboard(@js($pendaftaran->nomor_pendaftaran))">
                                                <i class="ri-file-copy-line"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="text-muted small">
                                        <i class="ri-calendar-line me-1"></i>
                                        {{ $pendaftaran->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                    </div>
                                </div>

                                <div class="text-md-end">
                                    <span class="badge rounded-pill {{ $statusClass[$pendaftaran->status] ?? 'bg-secondary-subtle text-secondary' }}">
                                        {{ $statusLabel[$pendaftaran->status] ?? ucfirst($pendaftaran->status ?? '-') }}
                                    </span>

                                    <div class="mt-2 fw-medium">
                                        {{ $pendaftaran->ppdb_jalur->nama_jalur ?? '-' }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $pendaftaran->ppdb_periode->nama_periode ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DATA CALON SISWA --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-user-3-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1">Data Calon Siswa</h6>
                                        <p class="text-muted small mb-0">
                                            Identitas calon peserta didik
                                        </p>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.data-siswa', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.data-siswa', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                @if ($calonSiswa)
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Lengkap</div>
                                            <div class="fw-medium">
                                                {{ $calonSiswa->nama_siswa ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">NIK</div>
                                            <div>
                                                {{ $calonSiswa->nik ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Tempat, Tanggal Lahir</div>
                                            <div>
                                                {{ $calonSiswa->tempat_lahir ?? '-' }},
                                                {{ $calonSiswa->tanggal_lahir
                                                    ? \Carbon\Carbon::parse($calonSiswa->tanggal_lahir)->translatedFormat('d F Y')
                                                    : '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Jenis Kelamin</div>
                                            <div>
                                                {{ $calonSiswa->jenis_kelamin ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">
                                        Data calon siswa belum diisi.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- DATA ORANG TUA --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                            <i class="ri-parent-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1">Data Orang Tua / Wali</h6>
                                        <p class="text-muted small mb-0">
                                            Informasi keluarga dan kontak
                                        </p>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.orang-tua', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.orang-tua', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                @if ($orangTua)
                                    <div class="row g-3">
                                        {{-- AYAH --}}
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Ayah</div>
                                            <div class="fw-medium">
                                                {{ $orangTua->nama_ayah ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Telepon Ayah</div>
                                            <div>
                                                {{ $orangTua->telepon_ayah ?? '-' }}
                                            </div>
                                        </div>

                                        {{-- IBU --}}
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Ibu</div>
                                            <div class="fw-medium">
                                                {{ $orangTua->nama_ibu ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Telepon Ibu</div>
                                            <div>
                                                {{ $orangTua->telepon_ibu ?? '-' }}
                                            </div>
                                        </div>

                                        {{-- WALI --}}
                                        @if (filled($orangTua->nama_wali))
                                            <div class="col-12">
                                                <hr class="my-0">
                                            </div>

                                            <div class="col-md-6">
                                                <div class="text-muted small mb-1">Nama Wali</div>
                                                <div class="fw-medium">
                                                    {{ $orangTua->nama_wali }}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="text-muted small mb-1">Telepon Wali</div>
                                                <div>
                                                    {{ $orangTua->telepon_wali ?? '-' }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted mb-0">
                                        Data orang tua belum diisi.
                                    </p>
                                @endif
                            </div>
                        </div>

                        
                        {{-- DATA ALAMAT --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                            <i class="ri-map-pin-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1">Data Alamat</h6>
                                        <p class="text-muted small mb-0">
                                            Domisili calon siswa
                                        </p>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.alamat', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.alamat', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                @if ($alamat)
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="text-muted small mb-1">Alamat Lengkap</div>
                                            <div class="fw-medium">
                                                {{ $alamat->alamat_lengkap ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Kelurahan / Desa</div>
                                            <div>
                                                {{ $alamat->kelurahan_desa ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Kecamatan</div>
                                            <div>
                                                {{ $alamat->kecamatan ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Jarak ke Sekolah</div>
                                            <div>
                                                {{ $alamat->jarak_ke_sekolah ?? '-' }} Km
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Waktu Tempuh</div>
                                            <div>
                                                {{ $alamat->waktu_tempuh_ke_sekolah ?? '-' }} Menit
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">
                                        Data alamat belum diisi.
                                    </p>
                                @endif
                            </div>
                        </div>

                    
                        {{-- RIWAYAT PENDIDIKAN --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="ri-graduation-cap-line fs-18"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="mb-1">Riwayat Pendidikan</h6>
                                        <p class="text-muted small mb-0">
                                            Informasi sekolah asal
                                        </p>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.pendidikan', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.pendidikan', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                @if ($pendidikan)
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Jenjang Asal</div>
                                            <div class="fw-medium">
                                                {{ $pendidikan->jenjang_asal ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Sekolah Asal</div>
                                            <div class="fw-medium">
                                                {{ $pendidikan->asal_sekolah ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Tahun Lulus</div>
                                            <div>
                                                {{ $pendidikan->tahun_lulus ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">NPSN Sekolah</div>
                                            <div>
                                                {{ $pendidikan->npsn_asal_sekolah ?? '-' }}
                                            </div>
                                        </div>

                                        @if (filled($pendidikan->alamat_asal_sekolah))
                                            <div class="col-12">
                                                <div class="text-muted small mb-1">Alamat Sekolah Asal</div>
                                                <div>
                                                    {{ $pendidikan->alamat_asal_sekolah }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted mb-0">
                                        Data riwayat pendidikan belum diisi.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- BERKAS PERSYARATAN --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                            <i class="ri-file-upload-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Berkas Persyaratan</h6>
                                        <p class="text-muted small mb-0">Dokumen pendukung pendaftaran</p>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.berkas', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Kelola Berkas
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.berkas', [
                                            'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                        ]) }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Perbaiki Berkas
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($daftarBerkas as $jenis => $nama)
                                        @php
                                            $berkas = $berkasByJenis->get($jenis);
                                        @endphp

                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div class="flex-grow-1">
                                                <div class="text-muted small mb-1">{{ $nama }}</div>

                                                @if ($berkas)
                                                    <div class="fw-medium text-break">
                                                        {{ $berkas->nama_file }}
                                                    </div>
                                                @else
                                                    <div class="text-muted small">Belum ada dokumen</div>
                                                @endif
                                            </div>

                                            @if ($berkas)
                                                <span class="text-success small text-nowrap">
                                                    <i class="ri-checkbox-circle-line me-1"></i>
                                                    Terunggah
                                                </span>
                                            @else
                                                <span class="text-warning small text-nowrap">
                                                    <i class="ri-error-warning-line me-1"></i>
                                                    Belum diunggah
                                                </span>
                                            @endif
                                        </div>

                                        @if (!$loop->last)
                                            <hr class="my-0">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        {{-- INFORMASI TAMBAHAN --}}
                        @if ($jawabanTambahan->isNotEmpty())
                            <div class="border rounded-4 mb-3 overflow-hidden">
                                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-question-answer-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-semibold mb-1">Informasi Tambahan</h6>
                                        <small class="text-muted">
                                            Jawaban atas pertanyaan tambahan pendaftaran
                                        </small>
                                    </div>
                                </div>

                                <div class="p-3">
                                    @foreach ($jawabanTambahan as $item)
                                        <div class="{{ !$loop->last ? 'mb-3' : '' }}">
                                            <small class="text-muted d-block mb-1">
                                                {{ $item->ppdb_pertanyaan->pertanyaan }}
                                            </small>

                                            <div class="fw-semibold text-break">
                                                {{ filled($item->jawaban) ? $item->jawaban : '—' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- PERNYATAAN DAN PENGIRIMAN --}}
                        @if ($pendaftaran->status === 'draft')
                            @livewire(
                                'p-p-d-b.pendaftaran.pertanyaan-tambahan',
                                [
                                    'pendaftaranId' => $pendaftaran->ppdb_pendaftaran_id,
                                ],
                                key('pertanyaan-tambahan-' . $pendaftaran->ppdb_pendaftaran_id)
                            )
                        @else
                            <div class="alert alert-light border rounded-3 mt-4 mb-0">
                                <div class="d-flex gap-3">
                                    <i class="ri-information-line text-primary fs-20"></i>

                                    <div>
                                        <h6 class="fw-semibold mb-1">
                                            Pendaftaran sudah dikirim
                                        </h6>

                                        <p class="text-muted small mb-0">
                                            Anda dapat melihat informasi pendaftaran ini.
                                            Perubahan data mengikuti ketentuan dan proses
                                            verifikasi panitia PPDB.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                                {{-- KEMBALI KE DASHBOARD --}}
                                <a href="{{ route('ppdb.dashboard', [
                                        'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                    ]) }}"
                                    class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="ri-dashboard-line me-1"></i>
                                    Dashboard
                                </a>

                                {{-- STATUS PENDAFTARAN --}}
                                <a href="{{ route('ppdb.pendaftaran.status', [
                                        'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                                    ]) }}"
                                    class="btn btn-primary rounded-pill px-4">
                                    Status Pendaftaran
                                    <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection