<div wire:ignore.self class="modal fade" id="ModalImportEduCard" tabindex="-1" aria-labelledby="ModalImportEduCard" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>
                    <div>
                        <div>
                            <h5 class="modal-title mb-1">Import EduCard Siswa {{ $namaKelas }}</h5>
                            <small class="text-muted">Unggah dan periksa data kartu sebelum diperbarui.</small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <lord-icon
                        src="https://cdn.lordicon.com/fjvfsqea.json"
                        trigger="loop"
                        colors="primary:#405189,secondary:#f06548"
                        style="width:90px;height:90px">
                    </lord-icon>
                    <h4 class="fs-semibold">Import EduCard Siswa</h4>
                    <p class="text-muted">
                        Unduh template sesuai kelas, isi kolom <strong>educard</strong>,
                        kemudian unggah kembali file untuk melihat pratinjau perubahan sebelum disimpan.
                    </p>
                </div>

                <div class="row g-4">
                    <!-- PETUNJUK -->
                    <div class="col-lg-5">
                        <div class="card border shadow-sm h-100 mb-0">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    Petunjuk Import
                                </h6>
                                <small class="text-muted">Ikuti langkah berikut agar data kartu terbaca dengan benar.</small>
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
                                            <strong>educard</strong>
                                            dengan nomor kartu yang akan digunakan.
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
                                            Kosongkan kolom
                                            <strong>educard</strong>
                                            apabila kartu ingin dihapus.
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <span class="badge bg-primary me-3">5</span>
                                        <div>
                                            Upload kembali file Excel dan periksa pratinjau perubahan sebelum menekan tombol
                                            <strong>Simpan</strong>.
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-success" wire:click="exportEduCardSiswa">
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
                                <small class="text-muted">Pilih file Excel berisi data EduCard terbaru.</small>
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
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Preview Perubahan EduCard
                        </h5>
                        <small class="text-muted">Periksa perubahan sebelum diterapkan.</small>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>EduCard Lama</th>
                                        <th>EduCard Baru</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($previewSiswaList as $siswa)
                                        <tr>
                                            <td>{{ $siswa['ms_siswa_id'] }}</td>
                                            <td>{{ $siswa['nama_siswa'] }}</td>
                                            <td>{{ $siswa['kelas'] }}</td>
                                            <td>
                                                @if($siswa['educard_lama'])
                                                    <span class="fw-semibold">
                                                        {{ $siswa['educard_lama'] }}
                                                    </span>
                                                @else
                                                    <em class="text-muted">Belum memiliki kartu</em>
                                                @endif
                                            </td>
                                            <td>
                                                @if($siswa['educard_baru'])
                                                    <span class="fw-semibold">
                                                        {{ $siswa['educard_baru'] }}
                                                    </span>
                                                @else
                                                    <em class="text-muted">Kartu akan dihapus</em>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($siswa['status'])
                                                    @case('tambah')
                                                        <span class="badge bg-success">
                                                            Kartu Baru
                                                        </span>
                                                        @break
                                                    @case('update')
                                                        <span class="badge bg-warning text-dark">
                                                            Akan Diperbarui
                                                        </span>
                                                        @break
                                                    @case('hapus')
                                                        <span class="badge bg-danger">
                                                            Akan Dihapus
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
                <button class="btn btn-primary rounded-pill px-4" wire:click="saveChanges">
                    <i class="ri-save-3-line me-1"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
