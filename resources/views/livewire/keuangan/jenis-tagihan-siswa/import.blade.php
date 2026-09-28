{{-- Success is as dangerous as failure. --}}
<div wire:ignore.self class="modal fade" id="ModalImportTagihan" tabindex="-1" aria-labelledby="ModalImportTagihan" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title">Import Jenis Tagihan</h5>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>
            <form wire:submit.prevent="createJenisTagihan">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <lord-icon
                            src="https://cdn.lordicon.com/fjvfsqea.json"
                            trigger="loop"
                            colors="primary:#405189,secondary:#f06548"
                            style="width:90px;height:90px">
                        </lord-icon>

                        <h4 class="fs-semibold mt-2">
                            Import Jenis Tagihan
                        </h4>
                        <p class="text-muted">
                            Download template, isi data jenis tagihan, pilih kategori tagihan,
                            kemudian upload kembali file untuk melihat data sebelum disimpan.
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
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex">
                                            <span class="badge bg-primary me-3">1</span>
                                            <div>
                                                Download template jenis tagihan.
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <span class="badge bg-primary me-3">2</span>
                                            <div>
                                                Isi daftar
                                                <strong>Nama Jenis Tagihan</strong>
                                                pada template.
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <span class="badge bg-primary me-3">3</span>
                                            <div>
                                                Pilih kategori tagihan yang akan digunakan.
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <span class="badge bg-primary me-3">4</span>
                                            <div>
                                                Upload kembali file Excel.
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <span class="badge bg-primary me-3">5</span>
                                            <div>
                                                Periksa data sebelum menekan tombol
                                                <strong>Simpan</strong>.
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="d-grid">
                                        <a href="{{ url('storage/templates/template_import_tagihan.xlsx') }}" download class="btn btn-success">
                                            <i class="ri-file-excel-2-line me-1"></i>
                                            Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPLOAD -->
                        <div class="col-lg-7">
                            <div class="card border shadow-sm mb-0">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">
                                        Upload Dokumen
                                    </h6>
                                </div>

                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Kategori Tagihan
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select wire:model="selectedKategoriTagihan" class="form-select">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($select_kategori as $item)
                                                <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">
                                                    {{ $item->nama_kategori_tagihan_siswa }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('selectedKategoriTagihan')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>

                                    <div>
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
                    </div>
                    @if(count($newJenisTagihan))
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    Preview Jenis Tagihan
                                </h5>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="70">No</th>
                                                <th>Nama Jenis Tagihan</th>
                                                <th>Kategori</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($newJenisTagihan as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item['nama_jenis_tagihan_siswa'] }}</td>
                                                    <td>{{ $namaKategori ?: '-' }}</td>
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
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan Penempatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
