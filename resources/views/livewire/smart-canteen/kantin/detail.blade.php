<div wire:ignore.self class="modal fade" id="ModalDetailKantin" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detail Kantin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-lg-6">
                        <label class="form-label">Nama Kantin</label>
                        <p>{{ $nama_kantin }}</p>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label">Tanggal Dibuat</label>
                        <p>{{ $created_at ?? '-' }}</p>
                    </div>

                    <div class="col-lg-12">
                        <label class="form-label">Deskripsi</label>
                        <p>{{ $deskripsi ?: '-' }}</p>
                    </div>

                    <div class="col-lg-12">
                        <label class="form-label">Petugas Kantin</label>

                        @if(count($petugas))
                        <p>{{ implode(', ', $petugas) }}</p>
                        @else
                        <p class="text-muted">Belum ada petugas</p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i
                        class="ri-close-line me-1 align-middle"></i> Tutup</a>
            </div>

        </div>
    </div>
</div>