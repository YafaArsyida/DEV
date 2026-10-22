<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-file-edit-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">Ubah Berkas Persyaratan</h5>
                    <small class="text-muted">
                        Perbarui dokumen yang telah diunggah jika diperlukan.
                    </small>
                </div>
            </div>

            <span class="text-muted small">Langkah 6 dari 8</span>
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
                        Gambar akan dikonversi menjadi WebP saat disimpan.
                        PDF tetap dalam format PDF.
                    </p>
                </div>
            </div>
        </div>

        {{-- KARTU KELUARGA --}}
        <div class="border rounded-4 p-3 mb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                {{-- INFORMASI BERKAS --}}
                <div class="d-flex align-items-start gap-3 flex-grow-1">

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
                            Salinan Kartu Keluarga yang masih terbaca.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($kartuKeluarga)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-1">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $kartuKeluarga->nama_file }}
                            </div>
                        @else
                            <span class="text-warning small">
                                <i class="ri-error-warning-line me-1"></i>
                                Belum diunggah
                            </span>
                        @endif

                        {{-- INFORMASI FILE PENGGANTI --}}
                        @if ($fileKartuKeluarga)
                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>
                                File pengganti:

                                <span class="text-break">
                                    {{ $fileKartuKeluarga->getClientOriginalName() }}
                                </span>

                                ({{ number_format($fileKartuKeluarga->getSize() / 1024, 0) }} KB)
                            </div>

                            {{-- PREVIEW FILE BARU DI BAWAH FILE PENGGANTI --}}
                            @php
                                $ekstensiKK = strtolower(
                                    $fileKartuKeluarga->getClientOriginalExtension()
                                );
                            @endphp

                            @if (in_array($ekstensiKK, ['jpg', 'jpeg', 'png', 'webp']))
                                <div class="mt-2">
                                    <a
                                        href="{{ $fileKartuKeluarga->temporaryUrl() }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="d-inline-block"
                                        title="Preview file pengganti"
                                    >
                                        <img
                                            src="{{ $fileKartuKeluarga->temporaryUrl() }}"
                                            alt="Preview Kartu Keluarga"
                                            class="rounded border"
                                            style="max-width: 180px; max-height: 140px; object-fit: contain;"
                                        >
                                    </a>
                                </div>
                            @elseif ($ekstensiKK === 'pdf')
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded border bg-light"
                                        style="width: 44px; height: 44px;"
                                        title="File PDF dipilih"
                                    >
                                        <i class="ri-file-pdf-2-line text-danger fs-24"></i>
                                    </div>

                                    <span class="small text-muted">
                                        Dokumen PDF siap diunggah.
                                    </span>
                                </div>
                            @endif
                        @endif

                        {{-- VALIDASI --}}
                        @error('fileKartuKeluarga')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL BERKAS LAMA DAN PILIH FILE --}}
                <div class="flex-shrink-0 d-flex align-items-center gap-2">
                    {{-- TOMBOL BERKAS LAMA --}}
                    @if ($kartuKeluarga)
                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $kartuKeluarga->ppdb_berkas_id }})"
                            title="Lihat berkas lama yang sudah diunggah"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>
                    @endif

                    {{-- TOMBOL PILIH / GANTI FILE --}}
                    <label
                        for="edit-file-kk"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="fileKartuKeluarga"
                    >
                        <i class="ri-upload-2-line me-1"></i>
                        {{ $kartuKeluarga ? 'Ganti File' : 'Pilih File' }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="edit-file-kk"
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
                <div class="d-flex align-items-start gap-3 flex-grow-1">

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
                            Salinan akta kelahiran calon siswa yang jelas dan terbaca.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($aktaKelahiran)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-1">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $aktaKelahiran->nama_file }}
                            </div>
                        @else
                            <span class="text-warning small">
                                <i class="ri-error-warning-line me-1"></i>
                                Belum diunggah
                            </span>
                        @endif

                        {{-- INFORMASI FILE PENGGANTI --}}
                        @if ($fileAktaKelahiran)
                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>
                                File pengganti:

                                <span class="text-break">
                                    {{ $fileAktaKelahiran->getClientOriginalName() }}
                                </span>

                                ({{ number_format($fileAktaKelahiran->getSize() / 1024, 0) }} KB)
                            </div>

                            {{-- PREVIEW FILE BARU --}}
                            @php
                                $ekstensiAkta = strtolower(
                                    $fileAktaKelahiran->getClientOriginalExtension()
                                );
                            @endphp

                            @if (in_array($ekstensiAkta, ['jpg', 'jpeg', 'png', 'webp']))
                                <div class="mt-2">
                                    <a
                                        href="{{ $fileAktaKelahiran->temporaryUrl() }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="d-inline-block"
                                        title="Preview file pengganti"
                                    >
                                        <img
                                            src="{{ $fileAktaKelahiran->temporaryUrl() }}"
                                            alt="Preview Akta Kelahiran"
                                            class="rounded border"
                                            style="max-width: 180px; max-height: 140px; object-fit: contain;"
                                        >
                                    </a>
                                </div>
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
                        @endif

                        {{-- VALIDASI --}}
                        @error('fileAktaKelahiran')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL BERKAS LAMA DAN PILIH FILE --}}
                <div class="flex-shrink-0 d-flex align-items-center gap-2">

                    {{-- TOMBOL BERKAS LAMA --}}
                    @if ($aktaKelahiran)
                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $aktaKelahiran->ppdb_berkas_id }})"
                            title="Lihat berkas yang sudah diunggah"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>
                    @endif

                    {{-- TOMBOL PILIH / GANTI FILE --}}
                    <label
                        for="edit-file-akta"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="fileAktaKelahiran"
                    >
                        <i class="ri-upload-2-line me-1"></i>
                        {{ $aktaKelahiran ? 'Ganti File' : 'Pilih File' }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="edit-file-akta"
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
                <div class="d-flex align-items-start gap-3 flex-grow-1">

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
                            Pas foto calon siswa. Berkas harus berupa gambar, bukan PDF.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($pasFoto)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-1">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $pasFoto->nama_file }}
                            </div>
                        @else
                            <span class="text-warning small">
                                <i class="ri-error-warning-line me-1"></i>
                                Belum diunggah
                            </span>
                        @endif

                        {{-- INFORMASI FILE PENGGANTI --}}
                        @if ($filePasFoto)
                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>
                                File pengganti:

                                <span class="text-break">
                                    {{ $filePasFoto->getClientOriginalName() }}
                                </span>

                                ({{ number_format($filePasFoto->getSize() / 1024, 0) }} KB)
                            </div>

                            {{-- PREVIEW FILE BARU --}}
                            <div class="mt-2">
                                <a
                                    href="{{ $filePasFoto->temporaryUrl() }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="d-inline-block"
                                    title="Preview file pengganti"
                                >
                                    <img
                                        src="{{ $filePasFoto->temporaryUrl() }}"
                                        alt="Preview Pas Foto"
                                        class="rounded border"
                                        style="max-width: 180px; max-height: 180px; object-fit: contain;"
                                    >
                                </a>
                            </div>
                        @endif

                        {{-- VALIDASI --}}
                        @error('filePasFoto')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- TOMBOL BERKAS LAMA DAN PILIH FILE --}}
                <div class="flex-shrink-0 d-flex align-items-center gap-2">

                    {{-- TOMBOL BERKAS LAMA --}}
                    @if ($pasFoto)
                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $pasFoto->ppdb_berkas_id }})"
                            title="Lihat berkas yang sudah diunggah"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>
                    @endif

                    {{-- TOMBOL PILIH / GANTI FILE --}}
                    <label
                        for="edit-file-pas-foto"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="filePasFoto"
                    >
                        <i class="ri-upload-2-line me-1"></i>
                        {{ $pasFoto ? 'Ganti File' : 'Pilih File' }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="edit-file-pas-foto"
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
        <div class="border rounded-4 p-3 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">

                {{-- INFORMASI BERKAS --}}
                <div class="d-flex align-items-start gap-3 flex-grow-1">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                            <i class="ri-attachment-2"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-semibold mb-1">
                            Dokumen Pendukung
                        </h6>

                        <p class="text-muted small mb-2">
                            Dokumen tambahan jika dipersyaratkan oleh sekolah.
                            Bagian ini bersifat opsional.
                        </p>

                        {{-- STATUS BERKAS LAMA --}}
                        @if ($dokumenPendukung)
                            <span class="text-success small">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                Sudah diunggah
                            </span>

                            <div class="small text-muted text-break mt-1">
                                <i class="ri-attachment-2 me-1"></i>
                                {{ $dokumenPendukung->nama_file }}
                            </div>
                        @else
                            <span class="text-muted small">
                                <i class="ri-information-line me-1"></i>
                                Belum ada dokumen pendukung
                            </span>
                        @endif

                        {{-- INFORMASI FILE PENGGANTI --}}
                        @if ($fileDokumenPendukung)
                            <div class="small text-primary mt-2">
                                <i class="ri-file-check-line me-1"></i>
                                File pengganti:
                                <span class="text-break">
                                    {{ $fileDokumenPendukung->getClientOriginalName() }}
                                </span>
                                ({{ number_format($fileDokumenPendukung->getSize() / 1024, 0) }} KB)
                            </div>
                        @endif

                        {{-- VALIDASI --}}
                        @error('fileDokumenPendukung')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- PREVIEW DAN TOMBOL PILIH FILE --}}
                <div class="flex-shrink-0 d-flex align-items-center gap-2">

                    {{-- PREVIEW FILE BARU --}}
                    @if ($fileDokumenPendukung)
                        @php
                            $ekstensiDokumenPendukung = strtolower(
                                $fileDokumenPendukung->getClientOriginalExtension()
                            );
                        @endphp

                        {{-- THUMBNAIL GAMBAR --}}
                        @if (in_array($ekstensiDokumenPendukung, ['jpg', 'jpeg', 'png', 'webp']))
                            <a
                                href="{{ $fileDokumenPendukung->temporaryUrl() }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="d-inline-block"
                                title="Preview file pengganti"
                            >
                                <img
                                    src="{{ $fileDokumenPendukung->temporaryUrl() }}"
                                    alt="Preview Dokumen Pendukung"
                                    class="rounded border"
                                    style="width: 44px; height: 44px; object-fit: cover;"
                                >
                            </a>

                        {{-- IKON PDF --}}
                        @elseif ($ekstensiDokumenPendukung === 'pdf')
                            <div
                                class="d-flex align-items-center justify-content-center rounded border bg-light"
                                style="width: 44px; height: 44px;"
                                title="File PDF dipilih"
                            >
                                <i class="ri-file-pdf-2-line text-danger fs-24"></i>
                            </div>
                        @endif

                    {{-- TOMBOL Berkas LAMA --}}
                    @elseif ($dokumenPendukung)
                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalPreviewBerkas"
                            wire:click="$emit('showPreviewBerkas', {{ $dokumenPendukung->ppdb_berkas_id }})"
                            title="Berkas yang sudah diunggah"
                        >
                            <i class="ri-eye-line me-1"></i>
                            Berkas Lama
                        </button>
                    @endif

                    {{-- TOMBOL PILIH / GANTI FILE --}}
                    <label
                        for="edit-file-dokumen-pendukung"
                        class="btn btn-outline-primary rounded-pill px-4 mb-0"
                        wire:loading.class="disabled"
                        wire:target="fileDokumenPendukung"
                    >
                        <i class="ri-upload-2-line me-1"></i>
                        {{ $dokumenPendukung ? 'Ganti File' : 'Pilih File' }}
                    </label>

                    {{-- INPUT FILE --}}
                    <input
                        id="edit-file-dokumen-pendukung"
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

        {{-- TOMBOL AKSI --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">    
            <a href="{{ request()->url() }}" class="btn btn-light border rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>
                Batal
            </a>

            <button type="button"
                    wire:click="simpan"
                    wire:loading.attr="disabled"
                    wire:target="simpan,fileKartuKeluarga,fileAktaKelahiran,filePasFoto,fileDokumenPendukung"
                    class="btn btn-primary rounded-pill px-4">
                <span wire:loading.remove
                        wire:target="simpan">
                    <i class="ri-save-line me-1"></i>
                    Simpan Perubahan
                </span>

                <span wire:loading
                        wire:target="simpan">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Menyimpan...
                </span>
            </button>
        </div>
    </div>
</div>