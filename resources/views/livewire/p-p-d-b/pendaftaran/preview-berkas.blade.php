
<div wire:ignore.self
    class="modal fade"
    id="ModalPreviewBerkas"
    tabindex="-1"
    aria-labelledby="ModalPreviewBerkasLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3 min-w-0">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-search-line"></i>
                        </div>
                    </div>

                    <div class="min-w-0">
                        <h5 class="fw-bold mb-1 text-break"
                            id="ModalPreviewBerkasLabel">
                            {{ $berkas['jenis'] ?? 'Pratinjau Berkas' }}
                        </h5>

                        <small class="text-muted text-break">
                            {{ $berkas['nama_file'] ?? 'Belum ada berkas yang dipilih' }}
                        </small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle flex-shrink-0"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pt-2 pb-4">
                {{-- LOADING SAAT LIVEWIRE MEMUAT DATA BERKAS --}}
                <div wire:loading.flex
                    wire:target="showPreviewBerkas"
                    class="align-items-center justify-content-center text-center py-5"
                    style="min-height: 300px;">
                    <div>
                        <div
                            class="spinner-border text-primary mb-3"
                            role="status"
                        >
                            <span class="visually-hidden">Memuat...</span>
                        </div>

                        <p class="text-muted mb-0">
                            Menyiapkan pratinjau berkas...
                        </p>
                    </div>
                </div>

                {{-- KONTEN PREVIEW --}}
                <div wire:loading.remove wire:target="showPreviewBerkas">
                @if ($berkas)
                    <div class="bg-white border rounded-3 p-2 text-center">

                        @if (str_starts_with($berkas['mime_type'] ?? '', 'image/'))
                            <img
                                src="{{ $berkas['url'] }}"
                                alt="{{ $berkas['nama_file'] ?? 'Pratinjau berkas' }}"
                                class="img-fluid rounded-3"
                                style="max-height: 70vh;">

                        @elseif (($berkas['mime_type'] ?? '') === 'application/pdf')
                            <iframe
                                src="{{ $berkas['url'] }}"
                                title="{{ $berkas['nama_file'] ?? 'Pratinjau PDF' }}"
                                style="width: 100%; height: 70vh; border: 0;"
                                class="rounded-3">
                            </iframe>

                        @else
                            <div class="text-center py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-24">
                                        <i class="ri-file-warning-line"></i>
                                    </div>
                                </div>

                                <h6 class="fw-semibold mb-1">
                                    Pratinjau tidak tersedia
                                </h6>

                                <p class="text-muted small mb-0">
                                    Format berkas ini belum mendukung pratinjau langsung.
                                </p>
                            </div>
                        @endif

                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                <i class="ri-file-search-line"></i>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-1">
                            Belum ada berkas yang dipilih
                        </h6>

                        <p class="text-muted small mb-0">
                            Pilih berkas untuk melihat pratinjaunya.
                        </p>
                    </div>
                @endif
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
