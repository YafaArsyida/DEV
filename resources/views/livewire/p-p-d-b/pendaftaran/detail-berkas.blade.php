<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-file-upload-line"></i>
                    </div>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Berkas Persyaratan</h5>
                    <small class="text-muted">
                        Unggah dokumen sesuai ketentuan pendaftaran.
                    </small>
                </div>
            </div>

            @if ($bisaUbahData)
                <a
                    href="{{ request()->fullUrlWithQuery(['mode' => 'edit-berkas']) }}"
                    class="btn btn-outline-primary rounded-pill px-3">
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

    <div class="card-body">
        {{-- KARTU KELUARGA --}}
        <div class="border rounded-4 p-3 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-text-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-semibold mb-1">
                            Kartu Keluarga
                            <span class="text-danger">*</span>
                        </h6>

                        <p class="text-muted small mb-2">
                            Dokumen identitas keluarga sebagai salah satu persyaratan pendaftaran.
                        </p>

                        @if ($kartuKeluarga)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-2">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $kartuKeluarga->nama_file }}
                            </div>

                            @if ($kartuKeluarga->catatan_verifikasi)
                                <div class="small text-muted mt-2">
                                    <i class="ri-information-line me-1"></i>
                                    Catatan: {{ $kartuKeluarga->catatan_verifikasi }}
                                </div>
                            @endif
                        @else
                            <span class="text-warning small">
                                <i class="ri-error-warning-line me-1"></i>
                                Belum diunggah
                            </span>
                        @endif
                    </div>
                </div>

                @if ($kartuKeluarga)
                    <div class="flex-shrink-0">
                        <button type="button"
                            class="btn btn-outline-primary rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $kartuKeluarga->ppdb_berkas_id }})">
                            <i class="ri-eye-line me-1"></i>
                            Lihat Berkas
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- AKTA KELAHIRAN --}}
        <div class="border rounded-4 p-3 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-semibold mb-1">
                            Akta Kelahiran
                            <span class="text-danger">*</span>
                        </h6>

                        <p class="text-muted small mb-2">
                            Dokumen identitas calon siswa sebagai salah satu persyaratan pendaftaran.
                        </p>

                        @if ($aktaKelahiran)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-2">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $aktaKelahiran->nama_file }}
                            </div>

                            @if ($aktaKelahiran->catatan_verifikasi)
                                <div class="small text-muted mt-2">
                                    <i class="ri-information-line me-1"></i>
                                    Catatan: {{ $aktaKelahiran->catatan_verifikasi }}
                                </div>
                            @endif
                        @else
                            <span class="text-warning small">
                                <i class="ri-error-warning-line me-1"></i>
                                Belum diunggah
                            </span>
                        @endif
                    </div>
                </div>

                @if ($aktaKelahiran)
                    <div class="flex-shrink-0">
                        <button type="button"
                            class="btn btn-outline-primary rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $aktaKelahiran->ppdb_berkas_id }})">
                            <i class="ri-eye-line me-1"></i>
                            Lihat Berkas
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- PAS FOTO --}}
        <div class="border rounded-4 p-3 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-image-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-semibold mb-1">
                            Pas Foto
                            <span class="text-danger">*</span>
                        </h6>

                        <p class="text-muted small mb-2">
                            Foto calon siswa dalam format gambar sebagai salah satu persyaratan pendaftaran.
                        </p>

                        @if ($pasFoto)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-2">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $pasFoto->nama_file }}
                            </div>

                            @if ($pasFoto->catatan_verifikasi)
                                <div class="small text-muted mt-2">
                                    <i class="ri-information-line me-1"></i>
                                    Catatan: {{ $pasFoto->catatan_verifikasi }}
                                </div>
                            @endif
                        @else
                            <span class="text-warning small">
                                <i class="ri-error-warning-line me-1"></i>
                                Belum diunggah
                            </span>
                        @endif
                    </div>
                </div>


                @if ($pasFoto)
                    <div class="flex-shrink-0">
                        <button type="button"
                            class="btn btn-outline-primary rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $pasFoto->ppdb_berkas_id }})">
                            <i class="ri-eye-line me-1"></i>
                            Lihat Berkas
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- DOKUMEN PENDUKUNG --}}
        <div class="border rounded-4 p-3 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                            <i class="ri-attachment-2"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-semibold mb-1">
                            Dokumen Pendukung
                            <span class="text-muted fw-normal small">(Opsional)</span>
                        </h6>

                        <p class="text-muted small mb-2">
                            Dokumen tambahan jika dipersyaratkan oleh sekolah.
                        </p>

                        @if ($dokumenPendukung)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-2">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $dokumenPendukung->nama_file }}
                            </div>

                            @if ($dokumenPendukung->catatan_verifikasi)
                                <div class="small text-muted mt-2">
                                    <i class="ri-information-line me-1"></i>
                                    Catatan: {{ $dokumenPendukung->catatan_verifikasi }}
                                </div>
                            @endif
                        @else
                            <span class="text-muted small">
                                <i class="ri-information-line me-1"></i>
                                Belum ada dokumen tambahan
                            </span>
                        @endif
                    </div>
                </div>

                @if ($dokumenPendukung)
                    <div class="flex-shrink-0">
                        <button type="button"
                            class="btn btn-outline-primary rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $dokumenPendukung->ppdb_berkas_id }})">
                            <i class="ri-eye-line me-1"></i>
                            Lihat Berkas
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- NAVIGASI --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
            <a
                href="{{ route('ppdb.pendaftaran.pendidikan',  [
                    'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                ]) }}"
                class="btn btn-light border rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>
                Data Pendidikan
            </a>

            <a href="{{ route('ppdb.pendaftaran.review',  [
                    'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                ]) }}"
                class="btn btn-primary rounded-pill px-4">
                Review
                <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>
    </div>
</div>