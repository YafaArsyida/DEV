@extends('template_portal_ppdb.v_template')

@section('title', 'Status Pendaftaran')

@section('content')

<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-xxl-12">
                @include('ppdb.orang-tua.layouts.progress-pendaftaran', [
                    'stepAktif' => $stepAktif
                ])
                
                {{-- Status Utama --}}
                @php
                    $statusConfig = [
                        'draft' => [
                            'label' => 'Belum Dikirim',
                            'badge' => 'bg-secondary-subtle text-secondary',
                            'icon' => 'ri-draft-line',
                            'iconClass' => 'bg-secondary-subtle text-secondary',
                            'judul' => 'Pendaftaran Belum Dikirim',
                            'deskripsi' => 'Silakan lengkapi dan kirim data pendaftaran Anda.',
                        ],
                        'pengecekan' => [
                            'label' => 'Sedang Dicek',
                            'badge' => 'bg-warning-subtle text-warning',
                            'icon' => 'ri-time-line',
                            'iconClass' => 'bg-warning-subtle text-warning',
                            'judul' => 'Pendaftaran Berhasil Dikirim',
                            'deskripsi' => 'Pendaftaran Anda sedang menunggu proses verifikasi oleh panitia.',
                        ],
                        'perbaikan' => [
                            'label' => 'Perlu Perbaikan',
                            'badge' => 'bg-danger-subtle text-danger',
                            'icon' => 'ri-edit-circle-line',
                            'iconClass' => 'bg-danger-subtle text-danger',
                            'judul' => 'Pendaftaran Memerlukan Perbaikan',
                            'deskripsi' => 'Silakan periksa informasi dan lakukan perbaikan sesuai catatan panitia.',
                        ],
                        'diterima' => [
                            'label' => 'Diterima',
                            'badge' => 'bg-success-subtle text-success',
                            'icon' => 'ri-checkbox-circle-line',
                            'iconClass' => 'bg-success-subtle text-success',
                            'judul' => 'Pendaftaran Diterima',
                            'deskripsi' => 'Selamat, pendaftaran Anda telah dinyatakan diterima.',
                        ],
                        'ditolak' => [
                            'label' => 'Ditolak',
                            'badge' => 'bg-danger-subtle text-danger',
                            'icon' => 'ri-close-circle-line',
                            'iconClass' => 'bg-danger-subtle text-danger',
                            'judul' => 'Pendaftaran Tidak Diterima',
                            'deskripsi' => 'Silakan lihat informasi hasil seleksi yang disampaikan oleh panitia.',
                        ],
                    ];

                    $status = strtolower($pendaftaran->status);

                    $config = $statusConfig[$status] ?? [
                        'label' => ucfirst($status),
                        'badge' => 'bg-secondary-subtle text-secondary',
                        'icon' => 'ri-information-line',
                        'iconClass' => 'bg-secondary-subtle text-secondary',
                        'judul' => 'Informasi Pendaftaran',
                        'deskripsi' => 'Silakan periksa informasi terbaru mengenai pendaftaran Anda.',
                    ];
                @endphp

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                    {{-- HEADER STATUS --}}
                    <div class="card-header bg-transparent">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title {{ $config['iconClass'] }} rounded-circle fs-20">
                                        <i class="{{ $config['icon'] }}"></i>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $config['judul'] }}
                                    </h5>

                                    <small class="text-muted">
                                        {{ $config['deskripsi'] }}
                                    </small>
                                </div>
                            </div>

                            <span class="badge {{ $config['badge'] }} rounded-pill px-3 py-2">
                                {{ $config['label'] }}
                            </span>

                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        <div class="row align-items-center g-4">
                            {{-- NOMOR DAN AKSI --}}
                            <div class="col-lg-7">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">
                                        Nomor Pendaftaran
                                    </small>

                                    <h4 class="fw-bold text-primary mb-0">
                                        {{ $pendaftaran->nomor_pendaftaran }}
                                    </h4>
                                </div>

                                <p class="text-muted mb-4">
                                    Gunakan nomor pendaftaran ini untuk memantau perkembangan
                                    proses PPDB dan melihat informasi pendaftaran Anda.
                                </p>

                                @if ($pendaftaran->status === 'perbaikan' && filled($pendaftaran->catatan_perbaikan))
                                    <div class="border rounded-4 p-3 my-3">
                                        <div class="d-flex gap-3">
                                            <div class="avatar-sm flex-shrink-0">
                                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                                    <i class="ri-message-2-line"></i>
                                                </div>
                                            </div>

                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-2">
                                                    Catatan dari Panitia
                                                </h6>

                                                <p class="text-muted mb-0 text-break">
                                                    {{ $pendaftaran->catatan_perbaikan }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex flex-column flex-sm-row flex-wrap gap-2">
                                    {{-- PERBAIKAN DATA --}}
                                    @if (in_array($status, ['draft', 'perbaikan']))
                                        <a href="{{ route('ppdb.pendaftaran.review') }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            <i class="ri-edit-line me-1"></i>
                                            {{ $status === 'perbaikan' ? 'Perbaiki Data' : 'Lanjutkan Pendaftaran' }}
                                        </a>
                                    @else
                                        {{-- PREVIEW PENDAFTARAN --}}
                                        <a href="{{ route('ppdb.pendaftaran.review') }}"
                                            class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="ri-eye-line me-1"></i>
                                            Preview Pendaftaran
                                        </a>
                                    @endif

                                    {{-- KIRIM ULANG UNTUK VERIFIKASI --}}
                                    @if ($status === 'perbaikan')
                                        <button type="button"
                                            class="btn btn-danger rounded-pill px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalKirimUlang">
                                            <i class="ri-send-plane-line me-1"></i>
                                            Perbaikan Selesai
                                        </button>
                                    @endif

                                    {{-- CETAK KARTU --}}
                                    @if ($status === 'diterima')
                                        <a href="#"
                                            class="btn btn-success rounded-pill px-4">
                                            <i class="ri-printer-line me-1"></i>
                                            Cetak Kartu Aljabar
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- RINGKASAN PENDAFTARAN --}}
                            <div class="col-lg-5">
                                <div class="card border shadow-none rounded-4 mb-0">
                                    <div class="card-body">

                                        <div class="d-flex justify-content-between gap-3 mb-3">
                                            <span class="text-muted">Tanggal Pendaftaran</span>
                                            <strong class="text-end">
                                                {{ $pendaftaran->submitted_at
                                                    ? \Carbon\Carbon::parse($pendaftaran->submitted_at)->translatedFormat('d F Y')
                                                    : '-' }}
                                            </strong>
                                        </div>

                                        <div class="d-flex justify-content-between gap-3 mb-3">
                                            <span class="text-muted">Nama Calon Siswa</span>
                                            <strong class="text-end">
                                                {{ $pendaftaran->ppdb_calon_siswa->nama_siswa ?? '-' }}
                                            </strong>
                                        </div>

                                        <div class="d-flex justify-content-between gap-3 mb-3">
                                            <span class="text-muted">Jalur</span>
                                            <strong class="text-end">
                                                {{ $pendaftaran->ppdb_jalur->nama_jalur ?? '-' }}
                                            </strong>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <span class="text-muted">Status</span>
                                            <span class="badge {{ $config['badge'] }} rounded-pill px-3 py-1">
                                                {{ $config['label'] }}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                {{-- Timeline --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-calendar-event-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Jadwal Proses PPDB
                                </h5>
                                <small class="text-muted">
                                    Informasi tahapan penerimaan peserta didik baru.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- PENDAFTARAN DIBUKA --}}
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0 text-center" style="width: 64px;">
                                <h4 class="fw-bold text-primary mb-0">01</h4>
                                <small class="text-muted">Okt</small>
                            </div>

                            <div class="pb-4 border-bottom w-100">
                                <h6 class="fw-semibold mb-1">
                                    Pendaftaran Dibuka
                                </h6>
                                <small class="text-muted">
                                    Pendaftaran dimulai secara online.
                                </small>
                            </div>
                        </div>

                        {{-- PENDAFTARAN DITUTUP --}}
                        <div class="d-flex gap-3 pt-4">
                            <div class="flex-shrink-0 text-center" style="width: 64px;">
                                <h4 class="fw-bold text-primary mb-0">30</h4>
                                <small class="text-muted">Nov</small>
                            </div>

                            <div class="pb-4 border-bottom w-100">
                                <h6 class="fw-semibold mb-1">
                                    Pendaftaran Ditutup
                                </h6>
                                <small class="text-muted">
                                    Batas waktu pengiriman formulir pendaftaran.
                                </small>
                            </div>
                        </div>

                        {{-- VERIFIKASI BERKAS --}}
                        <div class="d-flex gap-3 pt-4">
                            <div class="flex-shrink-0 text-center" style="width: 64px;">
                                <h4 class="fw-bold text-primary mb-0">05</h4>
                                <small class="text-muted">Des</small>
                            </div>

                            <div class="pb-4 border-bottom w-100">
                                <h6 class="fw-semibold mb-1">
                                    Verifikasi Berkas
                                </h6>
                                <small class="text-muted">
                                    Penilaian dan validasi dokumen pendaftaran.
                                </small>
                            </div>
                        </div>

                        {{-- PENGUMUMAN --}}
                        <div class="d-flex gap-3 pt-4">
                            <div class="flex-shrink-0 text-center" style="width: 64px;">
                                <h4 class="fw-bold text-primary mb-0">10</h4>
                                <small class="text-muted">Des</small>
                            </div>

                            <div class="pb-4 border-bottom w-100">
                                <h6 class="fw-semibold mb-1">
                                    Pengumuman
                                </h6>
                                <small class="text-muted">
                                    Hasil seleksi calon peserta didik diumumkan.
                                </small>
                            </div>
                        </div>

                        {{-- DAFTAR ULANG --}}
                        <div class="d-flex gap-3 pt-4">
                            <div class="flex-shrink-0 text-center" style="width: 64px;">
                                <h4 class="fw-bold text-primary mb-0">11–15</h4>
                                <small class="text-muted">Des</small>
                            </div>

                            <div class="w-100">
                                <h6 class="fw-semibold mb-1">
                                    Daftar Ulang
                                </h6>
                                <small class="text-muted">
                                    Konfirmasi pendaftaran bagi calon siswa yang diterima.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @if ($status === 'perbaikan')
        <div
            wire:ignore.self
            class="modal fade"
            id="modalKirimUlang"
            tabindex="-1"
            aria-labelledby="modalKirimUlangLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="modal-header border-0 pb-0">
                        <button
                            type="button"
                            class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"
                        >
                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-4 pb-4 pt-2 text-center">

                        {{-- ICON --}}
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/sbnjyzil.json"
                                        trigger="loop"
                                        colors="primary:#0d6efd,secondary:#0d6efd"
                                        style="width:70px;height:70px"
                                    >
                                    </lord-icon>
                                </div>
                            </div>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-2">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                                Konfirmasi Perbaikan
                            </span>

                            <h3
                                class="fw-bold mb-2"
                                id="modalKirimUlangLabel"
                            >
                                Kirim Ulang Pendaftaran?
                            </h3>

                            <p class="text-muted mb-0 lh-lg px-lg-2">
                                Pastikan seluruh data yang diminta panitia telah
                                diperbaiki. Setelah dikirim, pendaftaran Anda akan
                                diperiksa kembali oleh panitia PPDB.
                            </p>
                        </div>

                        {{-- INFORMASI --}}
                        <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="ri-information-line text-primary fs-20"></i>
                                </div>

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        Sebelum Mengirim
                                    </h6>

                                    <p class="text-muted mb-0 fs-13">
                                        Pastikan perbaikan sudah selesai dan data
                                        yang diperbarui sudah benar. Setelah dikirim,
                                        status pendaftaran akan berubah menjadi pengecekan. Anda dapat memantau
                                        hasil pemeriksaan melalui halaman status PPDB.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                        <button
                            type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal"
                        >
                            <i class="ri-arrow-go-back-line me-1"></i>
                            Periksa Kembali
                        </button>

                        <form
                            action="{{ route('ppdb.pendaftaran.kirim-ulang', [
                                'ppdb_pendaftaran_id' => $pendaftaran->ppdb_pendaftaran_id
                            ]) }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary rounded-pill px-4"
                            >
                                <i class="ri-send-plane-line me-1"></i>
                                Ya, Kirim Ulang
                            </button>
                        </form>

                    </div>

                </div>
            </div>
</div>
    @endif
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                alertify.success(@json(session('success')));
            });
        </script>
    @endif
</div>

@endsection