{{-- Success is as dangerous as failure. --}}
<div>
    <div wire:ignore.self class="modal fade" id="ModalImportTagihan" tabindex="-1" aria-labelledby="ModalImportTagihan" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Import Jenis Tagihan</h5>
                    <a href="{{ url('storage/templates/template_import_tagihan.xlsx') }}" 
                        class="text-success d-inline-block detail-item-btn ms-3" 
                        download>
                            <i class="ri-file-excel-2-line fs-17 align-middle"></i> Template
                        </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="createJenisTagihan">
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                        </div>
                        <div class="row g-3">
                            <h4 class="fs-semibold text-center">Import Dokumen</h4>
                            <div class="col-lg-6">
                                <label for="file_import" class="form-label">Upload File Excel</label>
                                <input type="file" wire:model="file_import" id="file_import" class="form-control">
                                @error('file_import') 
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="selectedKategoriTagihan" class="form-label">Kategori Tagihan</label>
                                <select id="selectedKategoriTagihan" wire:model="selectedKategoriTagihan" class="form-select">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($select_kategori as $item)    
                                    <option value="{{ $item->ms_kategori_tagihan_siswa_id }}">{{ $item->nama_kategori_tagihan_siswa }}</option>
                                    @endforeach
                                </select>
                                @error('ms_kategori_tagihan_siswa_id') 
                                    <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover nowrap align-middle" >
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-uppercase">No</th>
                                            <th class="text-uppercase">Tagihan</th>
                                            <th class="text-uppercase">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($newJenisTagihan) && is_array($newJenisTagihan))
                                            @foreach($newJenisTagihan as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}.</td>
                                                    <td>{{ $item['nama_jenis_tagihan_siswa'] ?? 'Tidak ada' }}</td>
                                                    <td>{{ $namaKategori ?? 'Belum dipilih' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">Belum ada data baru yang diimpor.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
