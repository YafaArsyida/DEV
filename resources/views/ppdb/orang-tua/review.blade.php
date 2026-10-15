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
                                    <h6 class="fw-bold text-primary mb-1">
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

                        {{-- INFORMASI --}}
                        <div class="alert alert-light border rounded-3 mb-4">
                            <div class="d-flex gap-3">
                                <i class="ri-information-line text-primary fs-20"></i>
                                <div>
                                    <h6 class="fw-semibold mb-1">Sebelum Mengirim Pendaftaran</h6>
                                    <p class="text-muted small mb-0">
                                        Periksa kembali seluruh data dan dokumen. Jika ada kesalahan,
                                        gunakan tombol Ubah Data pada bagian terkait. Setelah dikirim,
                                        pendaftaran akan masuk ke proses verifikasi panitia PPDB.
                                    </p>
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
                                        <h6 class="fw-bold mb-1">Data Calon Siswa</h6>
                                        <small class="text-muted">Identitas calon siswa</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.data-siswa') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Ubah Data
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
                                        <h6 class="fw-bold mb-1">Data Orang Tua/Wali</h6>
                                        <small class="text-muted">Informasi orang tua atau wali</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Ubah Data
                                    </a>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Nama Ayah</small>
                                        <span class="fw-semibold">
                                            {{ $orangTua->nama_ayah ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Nama Ibu</small>
                                        <span class="fw-semibold">
                                            {{ $orangTua->nama_ibu ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-1">Nomor WhatsApp</small>
                                        <span class="fw-semibold">
                                            {{ $orangTua->no_whatsapp ?? $orangTua->nomor_whatsapp ?? '—' }}
                                        </span>
                                    </div>
                                </div>
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
                                        <h6 class="fw-bold mb-1">Data Alamat</h6>
                                        <small class="text-muted">Alamat tempat tinggal calon siswa</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.alamat') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Ubah Data
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
                                        <h6 class="fw-bold mb-1">Pendidikan & Jalur Pendaftaran</h6>
                                        <small class="text-muted">Asal pendidikan dan jalur yang dipilih</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.pendidikan') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Ubah Data
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
                        <div class="border rounded-4 overflow-hidden">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-file-upload-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-1">Berkas Persyaratan</h6>
                                        <small class="text-muted">Status dokumen yang dipersyaratkan</small>
                                    </div>
                                </div>

                                @if ($pendaftaran->status === 'draft')
                                    <a href="{{ route('ppdb.pendaftaran.berkas') }}"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        <i class="ri-edit-line me-1"></i> Kelola Berkas
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

                        {{-- PERNYATAAN DAN PENGIRIMAN --}}
                        @if ($pendaftaran->status === 'draft')
                            <form
                                {{-- action="{{ route('ppdb.pendaftaran.kirim') }}" --}}
                                method="POST"
                                id="submitPendaftaranForm">
                                @csrf

                                <div class="border rounded-4 p-3 mt-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="pernyataan"
                                            value="1"
                                            id="agree-review"
                                            required
                                        >

                                        <label class="form-check-label" for="agree-review">
                                            Saya menyatakan bahwa data yang saya isi adalah benar
                                            dan dapat dipertanggungjawabkan.
                                        </label>
                                    </div>

                                    @error('pernyataan')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                                    <a href="{{ route('ppdb.pendaftaran.berkas') }}"
                                        class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="ri-arrow-left-line me-1"></i>
                                        Kembali
                                    </a>

                                    <button
                                        type="submit"
                                        class="btn btn-primary rounded-pill px-4"
                                        onclick="return confirm('Apakah Anda yakin ingin mengirim pendaftaran ini?')"
                                    >
                                        <i class="ri-send-plane-line me-1"></i>
                                        Kirim Pendaftaran
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-light border rounded-3 mt-4 mb-0">
                                <div class="d-flex gap-3">
                                    <i class="ri-information-line text-primary fs-20"></i>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Pendaftaran sudah dikirim</h6>
                                        <p class="text-muted small mb-0">
                                            Anda dapat melihat informasi pendaftaran ini.
                                            Perubahan data mengikuti ketentuan dan proses verifikasi panitia PPDB.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div
    class="modal fade"
    id="confirmSubmitModal"
    tabindex="-1"
    aria-labelledby="confirmSubmitLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-send-plane-line"></i>
                        </div>
                    </div>

                    <h5 class="modal-title fw-bold" id="confirmSubmitLabel">
                        Kirim Pendaftaran?
                    </h5>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>
            </div>

            <div class="modal-body">
                <p class="text-muted mb-0">
                    Setelah dikirim, pendaftaran akan masuk ke proses verifikasi
                    panitia PPDB. Pastikan data dan dokumen Anda sudah benar.
                </p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    Periksa Kembali
                </button>

                <button
                    type="button"
                    class="btn btn-primary rounded-pill px-4"
                    id="confirmSubmitButton">
                    Ya, Kirim Pendaftaran
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('submitPendaftaranForm');
        const confirmButton = document.getElementById('confirmSubmitButton');

        confirmButton.addEventListener('click', function () {
            if (!form.reportValidity()) {
                const modalElement = document.getElementById('confirmSubmitModal');
                const modal = bootstrap.Modal.getInstance(modalElement);

                if (modal) {
                    modal.hide();
                }

                return;
            }

            confirmButton.disabled = true;
            confirmButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengirim...';

            form.submit();
        });
    });
</script>

@endsection