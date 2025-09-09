<div>
    {{-- Modal Scan Smart Card --}}
    <div>
        <div class="modal fade zoomIn" id="ModalScanRFID" tabindex="-1" aria-labelledby="scanRFIDLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="scanRFIDLabel">Scan Smart Card</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body text-center p-5">
                        {{-- Icon animasi --}}
                        <lord-icon
                            src="https://cdn.lordicon.com/qhgmphtg.json"
                            trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c"
                            style="width:90px;height:90px">
                        </lord-icon>

                        <div class="mt-4">
                            <h4 class="fs-semibold">Tempelkan kartu Anda ke reader</h4>
                            <p class="text-muted fs-14 mb-4">Sistem akan otomatis membaca nomor seri kartu.</p>

                            {{-- Input Smart Card --}}
                            <input type="text" 
                                id="rfidInput" wire:model="educard"
                                class="form-control text-center fs-5 fw-bold" 
                                placeholder="Tempel kartu di sini..."
                                autocomplete="off">

                            {{-- Tombol --}}
                            <div class="hstack gap-2 justify-content-center mt-4">
                                <button type="button" class="btn btn-link link-secondary fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Batal
                                </button>
                                <button type="button" class="btn btn-success">
                                    <i class="ri-check-line me-1 align-middle"></i> Konfirmasi
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Auto Focus Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var scanModal = document.getElementById('ModalScanRFID');
            scanModal.addEventListener('shown.bs.modal', function () {
                document.getElementById('rfidInput').focus();
            });
        });
    </script>

</div>
