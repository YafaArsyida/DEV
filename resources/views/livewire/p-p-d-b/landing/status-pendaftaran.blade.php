
<div>
    {{-- FORM PENCARIAN STATUS --}}
    <div class="card border-0 shadow rounded-4 p-4 p-lg-5 bg-white text-body ppdb-status-lookup">
        <h4 class="fs-5 fw-semibold mb-2">
            Cek status pendaftaran
        </h4>

        <p class="text-muted mb-4">
            Siapkan nomor pendaftaran yang Anda terima setelah mengirim formulir.
        </p>

        <form wire:submit.prevent="cekStatus">
            <label for="ppdb-registration-number" class="form-label fw-semibold">
                Nomor pendaftaran
            </label>

            <input
                type="text"
                class="form-control @error('nomorPendaftaran') is-invalid @enderror"
                id="ppdb-registration-number"
                wire:model.defer="nomorPendaftaran"
                placeholder="Contoh: PPDB-2027-0001"
                autocomplete="off"
                aria-describedby="ppdb-registration-hint"
            >

            @error('nomorPendaftaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small class="form-text" id="ppdb-registration-hint">
                Masukkan nomor sesuai bukti pendaftaran.
            </small>

            @if ($pesanStatus)
                <div class="alert alert-warning alert-dismissible fade show mt-3 mb-0" role="alert">
                    <i class="ri-information-line me-1"></i>
                    {{ $pesanStatus }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <button
                type="submit"
                class="btn btn-primary rounded-pill w-100 mt-3"
                wire:loading.attr="disabled"
                wire:target="cekStatus"
            >
                <span wire:loading.remove wire:target="cekStatus">
                    Cek Status <i class="ri-arrow-right-line ms-1"></i>
                </span>

                <span wire:loading wire:target="cekStatus">
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                    Memeriksa...
                </span>
            </button>
        </form>

        <div class="ppdb-status-divider d-flex align-items-center gap-3 my-4 small text-muted">
            <span class="border-top flex-grow-1"></span>
            atau
            <span class="border-top flex-grow-1"></span>
        </div>

        <div class="ppdb-status-login d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3">
            <div>
                <strong class="d-block mb-1">Sudah memiliki akun?</strong>
                <span class="small text-muted">
                    Masuk ke Portal Pendaftar untuk melihat detail.
                </span>
            </div>

            @auth
                <a
                    href="{{ route('ppdb.dashboard', ['jenjang' => $jenjangSlug]) }}"
                    class="btn btn-outline-primary rounded-pill flex-shrink-0"
                >
                    Dashboard <i class="ri-home-line ms-1"></i>
                </a>
            @else
                <a
                    href="{{ route('ppdb.login', ['jenjang' => $jenjangSlug]) }}"
                    class="btn btn-outline-primary rounded-pill flex-shrink-0"
                >
                    Login <i class="ri-login-box-line ms-1"></i>
                </a>
            @endauth
        </div>
    </div>

    {{-- MODAL STATUS PENDAFTARAN --}}
    <div wire:ignore.self class="modal fade" id="ModalStatusPendaftaran"
        tabindex="-1" aria-labelledby="ModalStatusPendaftaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">

                {{-- HEADER --}}
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-file-search-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5
                                class="modal-title mb-1 text-dark"
                                id="ModalStatusPendaftaranLabel"
                            >
                                Status Pendaftaran
                            </h5>
                            <p class="text-muted small mb-0">
                                Informasi penerimaan peserta didik baru
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body text-dark">
                    @if (!$pendaftaran)
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3"></div>
                            <p class="text-muted mb-0">
                                Memuat informasi pendaftaran...
                            </p>
                        </div>
                    @else
                        @php
                            $statusLabel = [
                                'draft' => 'Belum Dikirim',
                                'pengecekan' => 'Sedang Diperiksa',
                                'diterima' => 'Diterima',
                                'perbaikan' => 'Perlu Perbaikan',
                            ];

                            $statusClass = [
                                'draft' => 'bg-secondary-subtle text-secondary',
                                'pengecekan' => 'bg-warning-subtle text-warning',
                                'diterima' => 'bg-success-subtle text-success',
                                'perbaikan' => 'bg-danger-subtle text-danger',
                            ];
                        @endphp

                        {{-- RINGKASAN PENDAFTARAN --}}
                        <div class="card border rounded-4 shadow-none mb-3">
                            <div class="card-body p-3 p-md-4">
                                <div class="text-muted small mb-1">
                                    Nomor Pendaftaran
                                </div>

                                <h5 class="text-primary fw-bold mb-3">
                                    {{ $pendaftaran['nomor_pendaftaran'] ?? '-' }}
                                </h5>

                                <div class="d-flex flex-column gap-3">
                                    <div>
                                        <div class="text-muted small mb-1">
                                            Calon Siswa
                                        </div>
                                        <div class="fw-semibold text-dark">
                                            {{ $pendaftaran['nama_siswa'] ?? '-' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">
                                            Jenjang Pendidikan
                                        </div>
                                        <div class="fw-semibold text-dark">
                                            {{ $pendaftaran['nama_jenjang'] ?? '-' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-muted small mb-1">
                                            Jalur Pendaftaran
                                        </div>
                                        <div class="fw-semibold text-dark">
                                            {{ $pendaftaran['nama_jalur'] ?? '-' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-muted small mb-1">
                                            Gelombang
                                        </div>
                                        <div class="fw-semibold text-dark">
                                            {{ $pendaftaran['nama_gelombang'] ?? '-' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-muted small mb-1">
                                            Status Pendaftaran
                                        </div>
                                        <span class="badge rounded-pill px-3 py-2 {{ $statusClass[$pendaftaran['status'] ?? ''] ?? 'bg-secondary-subtle text-secondary' }}">
                                            {{ $statusLabel[$pendaftaran['status'] ?? ''] ?? ucfirst($pendaftaran['status'] ?? '-') }}
                                        </span>
                                    </div>

                                    <div>
                                        <div class="text-muted small mb-1">
                                            Pendaftaran
                                        </div>
                                        <div class="text-dark">
                                            @if (!empty($pendaftaran['created_at']))
                                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                                    $pendaftaran['created_at'],
                                                    'd F Y, H:i'
                                                ) }}
                                            @else
                                                Belum dikirim
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CATATAN PERBAIKAN --}}
                        @if (
                            ($pendaftaran['status'] ?? null) === 'perbaikan'
                            && !empty($pendaftaran['catatan_perbaikan'])
                        )
                            <div class="alert alert-danger rounded-4 mb-0">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="ri-error-warning-line fs-20"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">
                                            Catatan Perbaikan
                                        </h6>
                                        <p class="mb-0">
                                            {{ $pendaftaran['catatan_perbaikan'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- INFORMASI TAMBAHAN --}}
                        @if (($pendaftaran['status'] ?? null) === 'draft')
                            <div class="alert alert-info rounded-4 mt-3 mb-0">
                                <i class="ri-information-line me-1"></i>
                                Pendaftaran Anda masih berupa draft. Silakan masuk ke portal untuk melanjutkan dan mengirim pendaftaran.
                            </div>
                        @elseif (($pendaftaran['status'] ?? null) === 'pengecekan')
                            <div class="alert alert-warning rounded-4 mt-3 mb-0">
                                <i class="ri-time-line me-1"></i>
                                Pendaftaran sedang diperiksa oleh panitia. Silakan periksa kembali status secara berkala.
                            </div>
                        @elseif (($pendaftaran['status'] ?? null) === 'diterima')
                            <div class="alert alert-success rounded-4 mt-3 mb-0">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Selamat! Pendaftaran Anda telah diterima.
                            </div>
                        @endif
                    @endif
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal"
                    >
                        Tutup
                    </button>

                    <a
                        href="{{ route('ppdb.login', ['jenjang' => $jenjangSlug]) }}"
                        class="btn btn-primary rounded-pill px-4"
                    >
                        <i class="ri-login-box-line me-1"></i>
                        Portal Pendaftar
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('showStatusPendaftaranModal', function () {
            const modalElement = document.getElementById('ModalStatusPendaftaran');

            if (modalElement && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        });
    });
</script>
@endpush
