<div>
    <div wire:ignore.self class="modal fade" id="ModalDetailPengguna" tabindex="-1" aria-labelledby="ModalDetailPenggunaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detail Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="nama" class="form-label">Nama</label>
                            <p>{{ $nama }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Username</label>
                            <p>{{ $email }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label for="peran" class="form-label">Peran</label>
                            <p class="text-secondary text-uppercase">{{ $peran }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label for="created_at" class="form-label">Tanggal Pendaftaran</label>
                            <p>{{ $created_at }}</p>
                        </div>
                        <div class="col-12">
                            <label for="aksesJenjang" class="form-label">Akses Jenjang</label>
                            <p>{{ implode(', ', $aksesJenjang) }}</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                </div>
            </div>
        </div>
    </div>
</div>
