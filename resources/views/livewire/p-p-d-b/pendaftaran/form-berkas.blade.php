
<div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- HEADER --}}
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
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-file-text-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">Kartu Keluarga <span class="text-danger">*</span></h6>
                            <p class="text-muted small mb-2">
                                Unggah salinan Kartu Keluarga yang masih terbaca.
                            </p>

                            @if ($berkas->get('KARTU_KELUARGA'))
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Sudah diunggah
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ $berkas->get('KARTU_KELUARGA')->nama_file }}
                                </div>
                                <a href="{{ route('ppdb.berkas.preview', $berkas->get('KARTU_KELUARGA')->ppdb_berkas_id) }}"
                                    target="_blank" rel="noopener"
                                    class="small">Lihat berkas tersimpan</a>
                            @else
                                <span class="text-warning small">
                                    <i class="ri-error-warning-line me-1"></i>
                                    Belum diunggah
                                </span>
                            @endif

                            @if ($fileKartuKeluarga)
                                <div class="small text-primary mt-2">
                                    <i class="ri-file-check-line me-1"></i>
                                    File pilihan:

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
                    <div class="flex-shrink-0">
                        <label for="file-kk" class="btn btn-outline-primary rounded-pill px-4 mb-0">
                            <i class="ri-upload-2-line me-1"></i>
                            {{ $berkas->get('KARTU_KELUARGA') ? 'Ganti' : 'Pilih File' }}
                        </label>
                        <input id="file-kk" type="file" class="d-none"
                            wire:model="fileKartuKeluarga"
                            accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>
                </div>
                <div wire:loading wire:target="fileKartuKeluarga" class="small text-primary mt-2">
                    Memuat file...
                </div>
            </div>

            {{-- AKTA KELAHIRAN --}}
            <div class="border rounded-4 p-3 mb-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-file-text-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">Akta Kelahiran <span class="text-danger">*</span></h6>
                            <p class="text-muted small mb-2">
                                Unggah salinan akta kelahiran calon siswa.
                            </p>

                            @if ($berkas->get('AKTA_KELAHIRAN'))
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>Sudah diunggah
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ $berkas->get('AKTA_KELAHIRAN')->nama_file }}
                                </div>
                                <a href="{{ route('ppdb.berkas.preview', $berkas->get('AKTA_KELAHIRAN')->ppdb_berkas_id) }}"
                                    target="_blank" rel="noopener" class="small">Lihat berkas tersimpan</a>
                            @else
                                <span class="text-warning small">
                                    <i class="ri-error-warning-line me-1"></i>Belum diunggah
                                </span>
                            @endif

                            @if ($fileAktaKelahiran)
                                <div class="small text-primary mt-2">
                                    <i class="ri-file-check-line me-1"></i>
                                    File pilihan:

                                    <span class="text-break">
                                        {{ $fileAktaKelahiran->getClientOriginalName() }}
                                    </span>

                                    ({{ number_format($fileAktaKelahiran->getSize() / 1024, 0) }} KB)
                                </div>

                                {{-- PREVIEW FILE BARU DI BAWAH FILE PENGGANTI --}}
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

                            @error('fileAktaKelahiran')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <label for="file-akta" class="btn btn-outline-primary rounded-pill px-4 mb-0">
                            <i class="ri-upload-2-line me-1"></i>
                            {{ $berkas->get('AKTA_KELAHIRAN') ? 'Ganti' : 'Pilih File' }}
                        </label>
                        <input id="file-akta" type="file" class="d-none"
                            wire:model="fileAktaKelahiran"
                            accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>
                </div>
                <div wire:loading wire:target="fileAktaKelahiran" class="small text-primary mt-2">
                    Memuat file...
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
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">Pas Foto <span class="text-danger">*</span></h6>
                            <p class="text-muted small mb-2">
                                Unggah pas foto calon siswa. Format PDF tidak diperbolehkan.
                            </p>

                            @if ($berkas->get('PAS_FOTO'))
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>Sudah diunggah
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ $berkas->get('PAS_FOTO')->nama_file }}
                                </div>
                                <a href="{{ route('ppdb.berkas.preview', $berkas->get('PAS_FOTO')->ppdb_berkas_id) }}"
                                    target="_blank" rel="noopener" class="small">Lihat berkas tersimpan</a>
                            @else
                                <span class="text-warning small">
                                    <i class="ri-error-warning-line me-1"></i>Belum diunggah
                                </span>
                            @endif

                            @if ($filePasFoto)
                                <div class="small text-primary mt-2">
                                    File pilihan: {{ $filePasFoto->getClientOriginalName() }}
                                    ({{ number_format($filePasFoto->getSize() / 1024, 0) }} KB)
                                </div>
                                <img src="{{ $filePasFoto->temporaryUrl() }}"
                                    alt="Preview Pas Foto"
                                    class="img-fluid rounded border mt-2"
                                    style="max-height: 180px; object-fit: contain;">
                            @endif

                            @error('filePasFoto')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <label for="file-foto" class="btn btn-outline-primary rounded-pill px-4 mb-0">
                            <i class="ri-upload-2-line me-1"></i>
                            {{ $berkas->get('PAS_FOTO') ? 'Ganti' : 'Pilih File' }}
                        </label>
                        <input id="file-foto" type="file" class="d-none"
                            wire:model="filePasFoto"
                            accept=".jpg,.jpeg,.png,.webp">
                    </div>
                </div>
                <div wire:loading wire:target="filePasFoto" class="small text-primary mt-2">
                    Memuat file...
                </div>
            </div>

            {{-- DOKUMEN PENDUKUNG --}}
            <div class="border rounded-4 p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                                <i class="ri-attachment-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">Dokumen Pendukung
                                <span class="text-muted small fw-normal">(Opsional)</span>
                            </h6>
                            <p class="text-muted small mb-2">
                                Dokumen tambahan sesuai jalur pendaftaran, jika dipersyaratkan.
                            </p>

                            @if ($berkas->get('DOKUMEN_PENDUKUNG'))
                                <span class="text-success small">
                                    <i class="ri-checkbox-circle-line me-1"></i>Sudah diunggah
                                </span>
                                <div class="small text-muted mt-1">
                                    {{ $berkas->get('DOKUMEN_PENDUKUNG')->nama_file }}
                                </div>
                                <a href="{{ route('ppdb.berkas.preview', $berkas->get('DOKUMEN_PENDUKUNG')->ppdb_berkas_id) }}"
                                    target="_blank" rel="noopener" class="small">Lihat berkas tersimpan</a>
                            @else
                                <span class="text-muted small">
                                    <i class="ri-information-line me-1"></i>Belum ada dokumen (opsional)
                                </span>
                            @endif

                            @if ($fileDokumenPendukung)
                                <div class="small text-primary mt-2">
                                    <i class="ri-file-check-line me-1"></i>
                                    File pilihan:

                                    <span class="text-break">
                                        {{ $fileDokumenPendukung->getClientOriginalName() }}
                                    </span>

                                    ({{ number_format($fileDokumenPendukung->getSize() / 1024, 0) }} KB)
                                </div>

                                {{-- PREVIEW FILE BARU DI BAWAH FILE PENGGANTI --}}
                                @php
                                    $ekstensiDokumen = strtolower(
                                        $fileDokumenPendukung->getClientOriginalExtension()
                                    );
                                @endphp

                                @if (in_array($ekstensiDokumen, ['jpg', 'jpeg', 'png', 'webp']))
                                    <div class="mt-2">
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
                                                style="max-width: 180px; max-height: 140px; object-fit: contain;"
                                            >
                                        </a>
                                    </div>
                                @elseif ($ekstensiDokumen === 'pdf')
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

                            @error('fileDokumenPendukung')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <label for="file-pendukung" class="btn btn-outline-primary rounded-pill px-4 mb-0">
                            <i class="ri-upload-2-line me-1"></i>
                            {{ $berkas->get('DOKUMEN_PENDUKUNG') ? 'Ganti' : 'Pilih File' }}
                        </label>
                        <input id="file-pendukung" type="file" class="d-none"
                            wire:model="fileDokumenPendukung"
                            accept=".jpg,.jpeg,.png,.webp,.pdf">
                    </div>
                </div>
                <div wire:loading wire:target="fileDokumenPendukung" class="small text-primary mt-2">
                    Memuat file...
                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                <a href="{{ route('ppdb.pendaftaran.pendidikan', [
                        'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                    ]) }}"
                    class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Kembali
                </a>

                <button type="button"
                    wire:click="simpan"
                    wire:loading.attr="disabled"
                    wire:target="simpan"
                    class="btn btn-primary rounded-pill px-4">
                    <span wire:loading.remove wire:target="simpan">
                        Unggah &amp; Lanjutkan
                        <i class="ri-arrow-right-line ms-1"></i>
                    </span>
                    <span wire:loading wire:target="simpan">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Menyimpan berkas...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
