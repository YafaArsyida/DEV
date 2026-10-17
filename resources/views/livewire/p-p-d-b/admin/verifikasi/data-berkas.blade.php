<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-0">
    {{-- HEADER --}}
    <div class="card-header bg-transparent">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-file-upload-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Berkas Persyaratan</h5>
                <small class="text-muted">
                    Pemeriksaan dokumen persyaratan calon siswa.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        @php
            $daftarBerkas = [
                [
                    'judul' => 'Kartu Keluarga',
                    'deskripsi' => 'Dokumen identitas keluarga calon siswa.',
                    'ikon' => 'ri-file-text-line',
                    'berkas' => $kartuKeluarga,
                    'wajib' => true,
                ],
                [
                    'judul' => 'Akta Kelahiran',
                    'deskripsi' => 'Dokumen identitas dan bukti kelahiran calon siswa.',
                    'ikon' => 'ri-file-list-3-line',
                    'berkas' => $aktaKelahiran,
                    'wajib' => true,
                ],
                [
                    'judul' => 'Pas Foto',
                    'deskripsi' => 'Foto calon siswa untuk keperluan administrasi pendaftaran.',
                    'ikon' => 'ri-image-line',
                    'berkas' => $pasFoto,
                    'wajib' => true,
                ],
                [
                    'judul' => 'Dokumen Pendukung',
                    'deskripsi' => 'Dokumen tambahan jika dipersyaratkan oleh sekolah.',
                    'ikon' => 'ri-attachment-2',
                    'berkas' => $dokumenPendukung,
                    'wajib' => false,
                ],
            ];
        @endphp

        @foreach ($daftarBerkas as $item)
            <div class="border rounded-4 p-3 {{ !$loop->last ? 'mb-3' : '' }}">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    {{-- INFORMASI BERKAS --}}
                    <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title {{ $item['wajib'] ? 'bg-primary-subtle text-primary' : 'bg-light text-muted' }} rounded-circle fs-20">
                                <i class="{{ $item['ikon'] }}"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-semibold mb-1">
                                {{ $item['judul'] }}

                                @if ($item['wajib'])
                                    <span class="text-danger">*</span>
                                @else
                                    <span class="text-muted fw-normal small">(Opsional)</span>
                                @endif
                            </h6>

                            <p class="text-muted small mb-2">
                                {{ $item['deskripsi'] }}
                            </p>

                            @if ($item['berkas'])
                                <div class="text-success small mb-2">
                                    <i class="ri-checkbox-circle-line me-1"></i>
                                    Sudah diunggah
                                </div>

                                <div class="small text-muted text-break">
                                    <i class="ri-attachment-2 me-1"></i>
                                    {{ $item['berkas']->nama_file ?: 'Nama file tidak tersedia' }}
                                </div>

                                @if ($item['berkas']->catatan_verifikasi)
                                    <div class="small text-muted mt-2">
                                        <i class="ri-information-line me-1"></i>
                                        <span class="fw-semibold">Catatan verifikasi:</span>
                                        {{ $item['berkas']->catatan_verifikasi }}
                                    </div>
                                @endif
                            @else
                                <div class="{{ $item['wajib'] ? 'text-warning' : 'text-muted' }} small">
                                    @if ($item['wajib'])
                                        <i class="ri-error-warning-line me-1"></i>
                                        Belum diunggah
                                    @else
                                        <i class="ri-information-line me-1"></i>
                                        Tidak ada dokumen tambahan
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- TOMBOL PREVIEW --}}
                    @if ($item['berkas'])
                        <div class="flex-shrink-0">
                            <button
                                type="button"
                                class="btn btn-outline-primary rounded-pill px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#ModalPreviewBerkas"
                                wire:click="$emit('showPreviewBerkas', {{ $item['berkas']->ppdb_berkas_id }})">
                                <i class="ri-eye-line me-1"></i>
                                Lihat Berkas
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- KETERANGAN --}}
        <div class="small text-muted mt-3">
            <i class="ri-information-line me-1"></i>
            Dokumen bertanda <span class="text-danger">*</span> merupakan berkas persyaratan utama.
        </div>
    </div>
</div>