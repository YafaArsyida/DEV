<div wire:ignore.self class="modal fade" id="ModalImportKontakPegawai" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Import Kontak Pegawai {{ $namaJenjang }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                            <h4 class="fs-semibold">Import Kontak Pegawai</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">
                                Ikuti langkah-langkah import di panel sebelah, lalu periksa perubahan nomor telepon dan email lama dengan yang terbaru.
                            </p>
                        </div>
                        <h6>Data Pegawai Saat Ini</h6>
                        <div class="table-responsive">
                            <table class="table table-hover nowrap align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Pegawai</th>
                                        <th>Nama</th>
                                        <th>Unit</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($oldPegawaiList as $index => $pegawai)
                                        <tr>
                                            <td>{{ $pegawai->ms_pegawai_id }}</td>
                                            <td>{{ $pegawai->nama_pegawai }}</td>
                                            <td>{{ $namaJenjang }}</td>
                                            <td>{{ $pegawai->telepon ?: 'Belum Ada' }}</td>
                                            <td>{{ $pegawai->email ?: 'Belum Ada' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data pegawai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Petunjuk Import</h6>
                        <p>Unduh format data pegawai {{ $namaJenjang }}
                            <a style="cursor: pointer" class="text-success d-inline-block detail-item-btn ms-3" wire:click="exportKontakPegawai">
                                <i class="ri-file-excel-2-line fs-17 align-middle"></i> Template
                            </a>

                        </p>
                        <p>Isikan pada kolom 'telepon' dan 'email'. Ingat! jangan mengubah data ID dan nama.</p>
                        <p>Simpan dan unggah data perubahan:</p>
                        <input type="file" wire:model="file_import" id="file_import" class="form-control">
                        @error('file_import') 
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        
                        <div class="mt-4">
                            <h6>Data Pegawai yang Diunggah:</h6>
                            <div class="table-responsive">
                                <table class="table table-hover nowrap align-middle" >
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Pegawai</th>
                                            <th>Nama</th>
                                            <th>Unit</th>
                                            <th>Telepon</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($newPegawaiList) && is_array($newPegawaiList))
                                            @foreach($newPegawaiList as $index => $pegawai)
                                                <tr>
                                                    <td>{{ $pegawai['ms_pegawai_id'] }}</td>
                                                    <td>{{ $pegawai['nama_pegawai'] ?? 'Tidak ada nama' }}</td>
                                                    <td>{{ $namaJenjang }}</td>
                                                    <td>{{ $pegawai['telepon'] ?? 'Tidak ada telepon' }}</td>
                                                    <td>{{ $pegawai['email'] ?? 'Tidak ada email' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">Belum ada data pegawai baru yang diimpor.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button class="btn btn-primary" wire:click="saveChanges">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</div>
