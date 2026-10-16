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
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <div>
                                    <small class="text-muted d-block mb-1">Nomor Pendaftaran</small>
                                    <h6 class="fw-semibold text-primary mb-1">
                                        {{ $pendaftaran->nomor_pendaftaran ?? '—' }}
                                    </h6>
                                    <small class="text-muted">
                                        Periksa kembali seluruh informasi sebelum mengirim pendaftaran.
                                    </small>
                                </div>

                                <div class="text-sm-end">
                                    <small class="text-muted d-block mb-1">Status Pendaftaran</small>
                                    @switch($pendaftaran->status)
                                        @case('draft')
                                            <span class="badge bg-warning-subtle text-warning">
                                                Belum Dikirim
                                            </span>
                                            @break

                                        @case('submitted')
                                            <span class="badge bg-info-subtle text-info">
                                                Menunggu Verifikasi
                                            </span>
                                            @break

                                        @case('verified')
                                            <span class="badge bg-success-subtle text-success">
                                                Terverifikasi
                                            </span>
                                            @break

                                        @case('accepted')
                                            <span class="badge bg-success-subtle text-success">
                                                Diterima
                                            </span>
                                            @break

                                        @case('rejected')
                                            <span class="badge bg-danger-subtle text-danger">
                                                Ditolak
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-light text-muted">
                                                {{ $pendaftaran->status ?? 'Belum diketahui' }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        {{-- DATA CALON SISWA --}}
                        <div class="border rounded-4 mb-3 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-semibold mb-1">Data Calon Siswa</h6>
                                        <small class="text-muted">Identitas calon siswa</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.data-siswa') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.data-siswa') }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Nama Lengkap</small>
                                        <span class="fw-semibold">
                                            {{ $calonSiswa->nama_siswa ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">NIK</small>
                                        <span class="fw-semibold">
                                            {{ $calonSiswa->nik ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                                        <span class="fw-semibold">
                                            @if ($calonSiswa?->tanggal_lahir)
                                                {{ \Carbon\Carbon::parse($calonSiswa->tanggal_lahir)->translatedFormat('d F Y') }}
                                            @else
                                                —
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DATA ORANG TUA --}}
                        <div class="border rounded-4 mb-3 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-parent-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Data Orang Tua/Wali</h6>
                                        <small class="text-muted">Informasi orang tua atau wali</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            {{-- INFORMASI --}}
                            <div class="p-3">
                                <div class="row g-3">
                                    {{-- NAMA AYAH --}}
                                    <div class="col-12 col-md-6">
                                        <small class="text-muted d-block mb-1">Nama Ayah</small>
                                        <span class="fw-semibold">
                                            {{ $orangTua->nama_ayah ?: '—' }}
                                        </span>
                                    </div>

                                    {{-- NAMA IBU --}}
                                    <div class="col-12 col-md-6">
                                        <small class="text-muted d-block mb-1">Nama Ibu</small>
                                        <span class="fw-semibold">
                                            {{ $orangTua->nama_ibu ?: '—' }}
                                        </span>
                                    </div>

                                    {{-- NOMOR TELEPON UTAMA --}}
                                    <div class="col-12">
                                        <small class="text-muted d-block mb-1">
                                            Nomor Telepon
                                        </small>
                                        <span class="fw-semibold">
                                            @if (filled($orangTua->telepon_ayah))
                                                {{ $orangTua->telepon_ayah }}
                                                <small class="text-muted fw-normal">(Ayah)</small>
                                            @elseif (filled($orangTua->telepon_ibu))
                                                {{ $orangTua->telepon_ibu }}
                                                <small class="text-muted fw-normal">(Ibu)</small>
                                            @elseif (filled($orangTua->telepon_wali))
                                                {{ $orangTua->telepon_wali }}
                                                <small class="text-muted fw-normal">(Wali)</small>
                                            @else
                                                —
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                {{-- INFORMASI WALI --}}
                                @if (filled($orangTua->nama_wali))
                                    <div class="border-top mt-3 pt-3">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="ri-user-star-line text-primary fs-18"></i>
                                            <h6 class="fw-semibold mb-0">Informasi Wali</h6>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    Nama Wali
                                                </small>
                                                <span class="fw-semibold">
                                                    {{ $orangTua->nama_wali }}
                                                </span>
                                            </div>

                                            @if (filled($orangTua->hubungan_wali))
                                                <div class="col-12 col-md-6">
                                                    <small class="text-muted d-block mb-1">
                                                        Hubungan dengan Calon Siswa
                                                    </small>
                                                    <span class="fw-semibold">
                                                        {{ $orangTua->hubungan_wali }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- DATA ALAMAT --}}
                        <div class="border rounded-4 mb-3 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-map-pin-user-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-semibold mb-1">Data Alamat</h6>
                                        <small class="text-muted">Alamat tempat tinggal calon siswa</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.alamat') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.alamat') }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <small class="text-muted d-block mb-1">Alamat Lengkap</small>
                                        <span class="fw-semibold">
                                            {{ $alamat->alamat_lengkap ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <small class="text-muted d-block mb-1">Kecamatan</small>
                                        <span class="fw-semibold">
                                            {{ $alamat->kecamatan ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <small class="text-muted d-block mb-1">Kode Pos</small>
                                        <span class="fw-semibold">
                                            {{ $alamat->kode_pos ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PENDIDIKAN DAN JALUR --}}
                        <div class="border rounded-4 mb-3 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-school-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-semibold mb-1">Pendidikan & Jalur Pendaftaran</h6>
                                        <small class="text-muted">Asal pendidikan dan jalur yang dipilih</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.pendidikan') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Ubah Data
                                    </a>
                                @elseif ($pendaftaran->status === 'pendidikan')
                                    <a href="{{ route('ppdb.pendaftaran.pendidikan') }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Asal TK/RA</small>
                                        <span class="fw-semibold">
                                            {{ $pendidikan->nama_sekolah_asal ?? $pendidikan->asal_sekolah ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Tahun Lulus</small>
                                        <span class="fw-semibold">
                                            {{ $pendidikan->tahun_lulus ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Jalur Pendaftaran</small>
                                        <span class="fw-semibold">
                                            {{ $pendaftaran->ppdb_jalur->nama_jalur ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BERKAS PERSYARATAN --}}
                        <div class="border rounded-4 mb-3 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-file-upload-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-semibold mb-1">Berkas Persyaratan</h6>
                                        <small class="text-muted">Status dokumen yang dipersyaratkan</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.berkas') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Kelola Berkas
                                    </a>
                                @elseif ($pendaftaran->status === 'perbaikan')
                                    <a href="{{ route('ppdb.pendaftaran.berkas') }}"
                                        class="btn btn-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i>
                                        Perbaiki Berkas
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
                                            <div>
                                                <small class="text-muted d-block mb-1">{{ $nama }}</small>
                                                @if ($berkas)
                                                    <span class="fw-semibold">
                                                        {{ $berkas->nama_file }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if (!$berkas)
                                                <span class="text-warning small text-nowrap">
                                                    <i class="ri-error-warning-line me-1"></i>
                                                    Belum diunggah
                                                </span>
                                            @endif
                                        </div>
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
                                <a href="{{ route('ppdb.dashboard') }}"
                                    class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="ri-dashboard-line me-1"></i>
                                    Dashboard
                                </a>

                                {{-- STATUS PENDAFTARAN --}}
                                <a href="{{ route('ppdb.pendaftaran.status') }}"
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