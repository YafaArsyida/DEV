<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-file-upload-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Berkas Persyaratan
                </h5>
                <small class="text-muted">
                    Unggah dokumen sesuai ketentuan pendaftaran.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">

        {{-- KETENTUAN --}}
        <div class="alert alert-light border rounded-3 mb-4">
            <div class="d-flex gap-3">
                <i class="ri-information-line text-primary fs-20"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Ketentuan Unggah Dokumen</h6>
                    <p class="text-muted small mb-0">
                        Format JPG, JPEG, PNG, WebP, atau PDF dengan ukuran maksimal
                        2 MB per file. Pas foto harus berupa gambar.
                        Gambar akan dikonversi menjadi WebP saat disimpan,
                        sedangkan PDF tetap dalam format PDF.
                    </p>
                </div>
            </div>
        </div>

        
        {{-- KARTU KELUARGA --}}
        <div class="border rounded-4 p-3 mb-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                {{-- INFORMASI BERKAS --}}
                <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">

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
                            Unggah scan atau foto Kartu Keluarga yang terbaca jelas.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($berkasKartuKeluarga ?? null)

                            <div class="mb-2">
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Sudah diunggah
                                </span>

                                <div class="small text-muted text-break mt-1">
                                    <i class="ri-attachment-2 me-1"></i>
                                    {{ $berkasKartuKeluarga->nama_file ?: 'Nama file tidak tersedia' }}
                                </div>
                            </div>

                        @else

                            <span class="text-warning small">
                                <i class="ri-information-line me-1"></i>
                                {{ $modeEdit
                                    ? 'Belum ada berkas tersimpan'
                                    : 'Silakan pilih berkas untuk diunggah' }}
                            </span>

                        @endif

                        {{-- FILE BARU / PENGGANTI --}}
                        @if ($fileKartuKeluarga)

                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>

                                <span class="fw-semibold">
                                    {{ ($berkasKartuKeluarga ?? null)
                                        ? 'File pengganti:'
                                        : 'File dipilih:' }}
                                </span>

                                <span class="text-break">
                                    {{ $fileKartuKeluarga->getClientOriginalName() }}
                                </span>

                                ({{ number_format($fileKartuKeluarga->getSize() / 1024, 0) }} KB)
                            </div>

                            @php
                                $ekstensiKK = strtolower(
                                    $fileKartuKeluarga->getClientOriginalExtension()
                                );
                            @endphp

                            {{-- PREVIEW GAMBAR BARU --}}
                            @if (in_array($ekstensiKK, ['jpg', 'jpeg', 'png', 'webp']))

                                <div class="mt-2">
                                    <a
                                        href="{{ $fileKartuKeluarga->temporaryUrl() }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="d-inline-block"
                                        title="Lihat preview gambar yang dipilih"
                                    >
                                        <img
                                            src="{{ $fileKartuKeluarga->temporaryUrl() }}"
                                            alt="Preview Kartu Keluarga"
                                            class="rounded border"
                                            style="max-width: 180px; max-height: 140px; object-fit: contain;"
                                        >
                                    </a>
                                </div>

                            {{-- INDIKATOR PDF BARU --}}
                            @elseif ($ekstensiKK === 'pdf')

                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded border bg-light"
                                        style="width: 44px; height: 44px;"
                                    >
                                        <i class="ri-file-pdf-2-line text-danger fs-24"></i>
                                    </div>

                                    <span class="small text-muted">
                                        Dokumen PDF siap diunggah.
                                    </span>
                                </div>

                            @endif

                            {{-- BATALKAN FILE BARU --}}
                            <button
                                type="button"
                                wire:click="$set('fileKartuKeluarga', null)"
                                class="btn btn-sm btn-light text-danger rounded-pill mt-2"
                            >
                                <i class="ri-delete-bin-line me-1"></i>
                                Batalkan file {{ ($berkasKartuKeluarga ?? null)
                                    ? 'pengganti'
                                    : 'pilihan' }}
                            </button>

                        @endif

                        {{-- VALIDASI --}}
                        @error('fileKartuKeluarga')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex-shrink-0 d-flex flex-wrap align-items-center gap-2">

                    {{-- PREVIEW BERKAS LAMA --}}
                    @if ($berkasKartuKeluarga ?? null)

                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $berkasKartuKeluarga->ppdb_berkas_id }})"
                            title="Lihat berkas lama yang sudah tersimpan"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>

                    @endif

                    {{-- PILIH / GANTI FILE --}}
                    <label
                        for="{{ $modeEdit ? 'edit-file-kk' : 'create-file-kk' }}"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="fileKartuKeluarga"
                    >
                        <i class="ri-upload-2-line me-1"></i>

                        {{ $fileKartuKeluarga
                            ? 'Pilih Ulang'
                            : (($berkasKartuKeluarga ?? null)
                                ? 'Ganti File'
                                : 'Pilih File') }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="{{ $modeEdit ? 'edit-file-kk' : 'create-file-kk' }}"
                        type="file"
                        class="d-none"
                        wire:model="fileKartuKeluarga"
                        accept=".jpg,.jpeg,.png,.webp,.pdf"
                    >

                </div>

            </div>

            {{-- INDIKATOR UPLOAD --}}
            <div
                wire:loading
                wire:target="fileKartuKeluarga"
                class="small text-primary mt-2"
            >
                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat file...
            </div>

        </div>
        
        {{-- AKTA KELAHIRAN --}}
        <div class="border rounded-4 p-3 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                {{-- INFORMASI BERKAS --}}
                <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">

                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-text-line"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">

                        <h6 class="fw-semibold mb-1">
                            Akta Kelahiran
                            <span class="text-danger">*</span>
                        </h6>

                        <p class="text-muted small mb-2">
                            Unggah scan atau foto akta kelahiran calon siswa.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($berkasAktaKelahiran ?? null)

                            <div class="mb-2">
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Sudah diunggah
                                </span>

                                <div class="small text-muted text-break mt-1">
                                    <i class="ri-attachment-2 me-1"></i>
                                    {{ $berkasAktaKelahiran->nama_file ?: 'Nama file tidak tersedia' }}
                                </div>
                            </div>

                        @else

                            <span class="text-warning small">
                                <i class="ri-information-line me-1"></i>
                                {{ $modeEdit
                                    ? 'Belum ada berkas tersimpan'
                                    : 'Silakan pilih berkas untuk diunggah' }}
                            </span>

                        @endif

                        {{-- FILE BARU / PENGGANTI --}}
                        @if ($fileAktaKelahiran)

                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>

                                <span class="fw-semibold">
                                    {{ ($berkasAktaKelahiran ?? null)
                                        ? 'File pengganti:'
                                        : 'File dipilih:' }}
                                </span>

                                <span class="text-break">
                                    {{ $fileAktaKelahiran->getClientOriginalName() }}
                                </span>

                                ({{ number_format($fileAktaKelahiran->getSize() / 1024, 0) }} KB)
                            </div>

                            @php
                                $ekstensiAkta = strtolower(
                                    $fileAktaKelahiran->getClientOriginalExtension()
                                );
                            @endphp

                            {{-- PREVIEW GAMBAR BARU --}}
                            @if (in_array($ekstensiAkta, ['jpg', 'jpeg', 'png', 'webp']))

                                <div class="mt-2">
                                    <a
                                        href="{{ $fileAktaKelahiran->temporaryUrl() }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="d-inline-block"
                                        title="Lihat preview Akta Kelahiran"
                                    >
                                        <img
                                            src="{{ $fileAktaKelahiran->temporaryUrl() }}"
                                            alt="Preview Akta Kelahiran"
                                            class="rounded border"
                                            style="max-width: 180px; max-height: 140px; object-fit: contain;"
                                        >
                                    </a>
                                </div>

                            {{-- INDIKATOR PDF BARU --}}
                            @elseif ($ekstensiAkta === 'pdf')

                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded border bg-light"
                                        style="width: 44px; height: 44px;"
                                    >
                                        <i class="ri-file-pdf-2-line text-danger fs-24"></i>
                                    </div>

                                    <span class="small text-muted">
                                        Dokumen PDF siap diunggah.
                                    </span>
                                </div>

                            @endif

                            {{-- BATALKAN FILE BARU --}}
                            <button
                                type="button"
                                wire:click="$set('fileAktaKelahiran', null)"
                                class="btn btn-sm btn-light text-danger rounded-pill mt-2"
                            >
                                <i class="ri-delete-bin-line me-1"></i>
                                Batalkan file
                                {{ ($berkasAktaKelahiran ?? null)
                                    ? 'pengganti'
                                    : 'pilihan' }}
                            </button>

                        @endif

                        {{-- VALIDASI --}}
                        @error('fileAktaKelahiran')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex-shrink-0 d-flex flex-wrap align-items-center gap-2">

                    {{-- PREVIEW BERKAS LAMA --}}
                    @if ($berkasAktaKelahiran ?? null)

                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $berkasAktaKelahiran->ppdb_berkas_id }})"
                            title="Lihat berkas lama yang sudah tersimpan"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>

                    @endif

                    {{-- PILIH / GANTI FILE --}}
                    <label
                        for="{{ $modeEdit ? 'edit-file-akta' : 'create-file-akta' }}"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="fileAktaKelahiran"
                    >
                        <i class="ri-upload-2-line me-1"></i>

                        {{ $fileAktaKelahiran
                            ? 'Pilih Ulang'
                            : (($berkasAktaKelahiran ?? null)
                                ? 'Ganti File'
                                : 'Pilih File') }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="{{ $modeEdit ? 'edit-file-akta' : 'create-file-akta' }}"
                        type="file"
                        class="d-none"
                        wire:model="fileAktaKelahiran"
                        accept=".jpg,.jpeg,.png,.webp,.pdf"
                    >

                </div>
            </div>

            {{-- INDIKATOR UPLOAD --}}
            <div
                wire:loading
                wire:target="fileAktaKelahiran"
                class="small text-primary mt-2"
            >
                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat file...
            </div>
        </div>

        {{-- PAS FOTO --}}
        <div class="border rounded-4 p-3 mb-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                {{-- INFORMASI BERKAS --}}
                <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">

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
                            Unggah pas foto calon siswa. Format PDF tidak diperbolehkan.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($berkasPasFoto ?? null)

                            <div class="mb-2">
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Sudah diunggah
                                </span>

                                <div class="small text-muted text-break mt-1">
                                    <i class="ri-attachment-2 me-1"></i>
                                    {{ $berkasPasFoto->nama_file ?: 'Nama file tidak tersedia' }}
                                </div>
                            </div>

                        @else

                            <span class="text-warning small">
                                <i class="ri-information-line me-1"></i>
                                {{ $modeEdit
                                    ? 'Belum ada berkas tersimpan'
                                    : 'Silakan pilih berkas untuk diunggah' }}
                            </span>

                        @endif

                        {{-- FILE BARU / PENGGANTI --}}
                        @if ($filePasFoto)

                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>

                                <span class="fw-semibold">
                                    {{ ($berkasPasFoto ?? null)
                                        ? 'File pengganti:'
                                        : 'File dipilih:' }}
                                </span>

                                <span class="text-break">
                                    {{ $filePasFoto->getClientOriginalName() }}
                                </span>

                                ({{ number_format($filePasFoto->getSize() / 1024, 0) }} KB)
                            </div>

                            {{-- PREVIEW GAMBAR BARU --}}
                            <div class="mt-2">
                                <a
                                    href="{{ $filePasFoto->temporaryUrl() }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="d-inline-block"
                                    title="Lihat preview Pas Foto"
                                >
                                    <img
                                        src="{{ $filePasFoto->temporaryUrl() }}"
                                        alt="Preview Pas Foto"
                                        class="rounded border"
                                        style="max-width: 180px; max-height: 180px; object-fit: contain;"
                                    >
                                </a>
                            </div>

                            {{-- BATALKAN FILE BARU --}}
                            <button
                                type="button"
                                wire:click="$set('filePasFoto', null)"
                                class="btn btn-sm btn-light text-danger rounded-pill mt-2"
                            >
                                <i class="ri-delete-bin-line me-1"></i>
                                Batalkan file
                                {{ ($berkasPasFoto ?? null)
                                    ? 'pengganti'
                                    : 'pilihan' }}
                            </button>

                        @endif

                        {{-- VALIDASI --}}
                        @error('filePasFoto')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex-shrink-0 d-flex flex-wrap align-items-center gap-2">

                    {{-- PREVIEW BERKAS LAMA --}}
                    @if ($berkasPasFoto ?? null)

                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $berkasPasFoto->ppdb_berkas_id }})"
                            title="Lihat berkas Pas Foto lama"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>

                    @endif

                    {{-- PILIH / GANTI FILE --}}
                    <label
                        for="{{ $modeEdit ? 'edit-file-foto' : 'create-file-foto' }}"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="filePasFoto"
                    >
                        <i class="ri-upload-2-line me-1"></i>

                        {{ $filePasFoto
                            ? 'Pilih Ulang'
                            : (($berkasPasFoto ?? null)
                                ? 'Ganti File'
                                : 'Pilih File') }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="{{ $modeEdit ? 'edit-file-foto' : 'create-file-foto' }}"
                        type="file"
                        class="d-none"
                        wire:model="filePasFoto"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                </div>

            </div>

            {{-- INDIKATOR UPLOAD --}}
            <div
                wire:loading
                wire:target="filePasFoto"
                class="small text-primary mt-2"
            >
                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat file...
            </div>

        </div>

        {{-- DOKUMEN PENDUKUNG --}}
        <div class="border rounded-4 p-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                {{-- INFORMASI BERKAS --}}
                <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">

                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                            <i class="ri-attachment-2"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">

                        <h6 class="fw-semibold mb-1">
                            Dokumen Pendukung
                            <span class="text-muted small fw-normal">(Opsional)</span>
                        </h6>

                        <p class="text-muted small mb-2">
                            Unggah dokumen tambahan jika diperlukan.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($berkasDokumenPendukung ?? null)

                            <div class="mb-2">
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Sudah diunggah
                                </span>

                                <div class="small text-muted text-break mt-1">
                                    <i class="ri-attachment-2 me-1"></i>
                                    {{ $berkasDokumenPendukung->nama_file ?: 'Nama file tidak tersedia' }}
                                </div>
                            </div>

                        @else

                            <span class="text-warning small">
                                <i class="ri-information-line me-1"></i>
                                {{ $modeEdit
                                    ? 'Belum ada berkas tersimpan'
                                    : 'Belum memilih file (opsional)' }}
                            </span>

                        @endif

                        {{-- FILE BARU / PENGGANTI --}}
                        @if ($fileDokumenPendukung)

                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>

                                <span class="fw-semibold">
                                    {{ ($berkasDokumenPendukung ?? null)
                                        ? 'File pengganti:'
                                        : 'File dipilih:' }}
                                </span>

                                <span class="text-break">
                                    {{ $fileDokumenPendukung->getClientOriginalName() }}
                                </span>

                                ({{ number_format($fileDokumenPendukung->getSize() / 1024, 0) }} KB)
                            </div>

                            @php
                                $ekstensiDokumen = strtolower(
                                    $fileDokumenPendukung->getClientOriginalExtension()
                                );
                            @endphp

                            {{-- PREVIEW GAMBAR BARU --}}
                            @if (in_array($ekstensiDokumen, ['jpg', 'jpeg', 'png', 'webp']))

                                <div class="mt-2">
                                    <a
                                        href="{{ $fileDokumenPendukung->temporaryUrl() }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="d-inline-block"
                                        title="Lihat preview Dokumen Pendukung"
                                    >
                                        <img
                                            src="{{ $fileDokumenPendukung->temporaryUrl() }}"
                                            alt="Preview Dokumen Pendukung"
                                            class="rounded border"
                                            style="max-width: 180px; max-height: 140px; object-fit: contain;"
                                        >
                                    </a>
                                </div>

                            {{-- INDIKATOR PDF BARU --}}
                            @elseif ($ekstensiDokumen === 'pdf')

                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded border bg-light"
                                        style="width: 44px; height: 44px;"
                                    >
                                        <i class="ri-file-pdf-2-line text-danger fs-24"></i>
                                    </div>

                                    <span class="small text-muted">
                                        Dokumen PDF siap diunggah.
                                    </span>
                                </div>

                            @endif

                            {{-- BATALKAN FILE BARU --}}
                            <button
                                type="button"
                                wire:click="$set('fileDokumenPendukung', null)"
                                class="btn btn-sm btn-light text-danger rounded-pill mt-2"
                            >
                                <i class="ri-delete-bin-line me-1"></i>
                                Batalkan file
                                {{ ($berkasDokumenPendukung ?? null)
                                    ? 'pengganti'
                                    : 'pilihan' }}
                            </button>

                        @endif

                        {{-- VALIDASI --}}
                        @error('fileDokumenPendukung')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex-shrink-0 d-flex flex-wrap align-items-center gap-2">

                    {{-- PREVIEW BERKAS LAMA --}}
                    @if ($berkasDokumenPendukung ?? null)

                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $berkasDokumenPendukung->ppdb_berkas_id }})"
                            title="Lihat Dokumen Pendukung lama"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>

                    @endif

                    {{-- PILIH / GANTI FILE --}}
                    <label
                        for="{{ $modeEdit ? 'edit-file-pendukung' : 'create-file-pendukung' }}"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="fileDokumenPendukung"
                    >
                        <i class="ri-upload-2-line me-1"></i>

                        {{ $fileDokumenPendukung
                            ? 'Pilih Ulang'
                            : (($berkasDokumenPendukung ?? null)
                                ? 'Ganti File'
                                : 'Pilih File') }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="{{ $modeEdit ? 'edit-file-pendukung' : 'create-file-pendukung' }}"
                        type="file"
                        class="d-none"
                        wire:model="fileDokumenPendukung"
                        accept=".jpg,.jpeg,.png,.webp,.pdf"
                    >

                </div>

            </div>

            {{-- INDIKATOR UPLOAD --}}
            <div
                wire:loading
                wire:target="fileDokumenPendukung"
                class="small text-primary mt-2"
            >
                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat file...
            </div>

        </div>


    </div>
</div>