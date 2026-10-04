<div wire:ignore.self class="modal fade" id="ModalImportTelepon" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-smartphone-line"></i>
                        </div>
                    </div>
                    <div>
                        <div>
                            <h5 class="modal-title mb-1">Import Telepon Siswa {{ $namaKelas }}</h5>
                            <small class="text-muted">Unggah dan tinjau nomor telepon sebelum diperbarui.</small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <h4 class="fs-semibold">Import Nomor Telepon Siswa</h4>
                    <p class="text-muted">
                        Unduh template sesuai kelas, isi kolom <strong>telepon</strong>,
                        kemudian unggah kembali file untuk melihat pratinjau perubahan sebelum disimpan.
                    </p>
                </div>
                <div class="row g-4">
                    <!-- PETUNJUK -->
                    <div class="col-lg-5">
                        <div class="card border shadow-sm h-100 mb-0">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    Langkah Pembaruan
                                </h6>
                                <small class="text-muted">Ikuti langkah berikut sebelum mengunggah data.</small>
                            </div>

                            <div class="card-body">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex">
                                        <span class="badge bg-primary me-3">1</span>
                                        <div>
                                            Download template data siswa kelas
                                            <strong>{{ $namaKelas }}</strong>.
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <span class="badge bg-primary me-3">2</span>
                                        <div>
                                            Isi atau perbarui kolom
                                            <strong>telepon</strong>.
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <span class="badge bg-primary me-3">3</span>
                                        <div>
                                            Jangan mengubah
                                            <strong>ID Siswa</strong>
                                            maupun
                                            <strong>Nama Siswa</strong>.
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <span class="badge bg-primary me-3">4</span>
                                        <div>
                                            Upload kembali file Excel yang telah diperbarui.
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <span class="badge bg-primary me-3">5</span>
                                        <div>
                                            Periksa pratinjau perubahan sebelum menekan tombol
                                            <strong>Simpan</strong>.
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-grid">
                                    <button
                                        type="button"
                                        class="btn btn-success"
                                        wire:click="exportTeleponSiswa">

                                        <i class="ri-file-excel-2-line me-1"></i>
                                        Download Template
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD -->
                    <div class="col-lg-7">
                        <div class="card border shadow-sm mb-0">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    Upload Dokumen Pembaruan
                                </h6>
                                <small class="text-muted">Pilih file Excel berisi nomor telepon terbaru.</small>
                            </div>

                            <div class="card-body">
                                <label class="form-label fw-semibold">
                                    File Excel
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="file" wire:model="file_import" class="form-control">

                                @error('file_import')
                                    <small class="text-danger d-block mt-1">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <div class="form-text">
                                    Format yang didukung:
                                    <strong>.xlsx</strong>,
                                    <strong>.xls</strong>
                                </div>

                                <div wire:loading wire:target="file_import" class="mt-3 text-primary">
                                    <div class="spinner-border spinner-border-sm me-1"></div>
                                    Membaca file Excel...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($previewSiswaList))
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Preview Perubahan Nomor Telepon
                        </h5>
                        <small class="text-muted">Periksa perubahan nomor sebelum diterapkan.</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Nomor Lama</th>
                                        <th>Nomor Baru</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($previewSiswaList as $siswa)
                                    <tr>
                                        <td>{{ $siswa['ms_siswa_id'] }}</td>
                                        <td>{{ $siswa['nama_siswa'] }}</td>
                                        <td>{{ $siswa['kelas'] }}</td>
                                        <td>{{ $siswa['telepon_lama'] ?: '-' }}</td>
                                        <td>{{ $siswa['telepon_baru'] }}</td>
                                        <td>
                                            @switch($siswa['status'])
                                                @case('tambah')
                                                    <span class="badge bg-success">
                                                        Nomor Baru
                                                    </span>
                                                    @break
                                                @case('update')
                                                    <span class="badge bg-warning">
                                                        Akan Diperbarui
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">
                                                        Tidak Berubah
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
                <button wire:click="saveChanges" class="btn btn-primary rounded-pill px-4">
                    <i class="ri-save-3-line me-1"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
