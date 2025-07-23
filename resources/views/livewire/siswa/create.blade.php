{{-- Success is as dangerous as failure. --}}
<div>
    <div wire:ignore.self class="modal fade" id="ModalAddSiswa" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Siswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Header Card -->
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Personalisasi Aplikasi</h5>
                                            </div>
                                        </div>

                                        <!-- Informasi Jenjang -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-info text-white shadow">
                                                    <i class="ri-pencil-line"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="jenjangInput" placeholder="Jenjang Pendidikan"
                                                value="{{ $nama_jenjang ?? 'Tidak tersedia' }}" readonly>
                                        </div>

                                        <!-- Informasi Tahun Ajar -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-warning text-white shadow">
                                                    <i class="ri-calendar-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="tahunAjarInput" placeholder="Tahun Ajar"
                                                value="{{ $nama_tahun_ajar ?? 'Tidak tersedia' }}" readonly>
                                        </div>

                                        <!-- Informasi Kelas -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-success text-white shadow">
                                                    <i class="ri-home-3-fill"></i>
                                                </span>
                                            </div>
                                            <select id="ms_kelas_id" wire:model.defer="ms_kelas_id" class="form-select">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($select_kelas as $item)    
                                                <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                            @error('ms_kelas_id') 
                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                            @enderror
                                        </div>

                                        <!-- Telepon -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-success text-white shadow">
                                                    <i class="ri-phone-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" wire:model.defer="telepon" class="form-control" placeholder="Nomor Telepon" />
                                            @error('telepon') 
                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                            @enderror
                                        </div>

                                        <!-- EduCard -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-body text-body shadow">
                                                    <i class="ri-bank-card-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" wire:model.defer="educard" class="form-control" placeholder="EduCard Smart Card" />
                                            @error('educard') 
                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Siswa -->
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Informasi Siswa</h5>
                                            </div>
                                        </div>

                                        <div class="tab-content">
                                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                                <div class="row">
                                                    <!-- Nama Siswa -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="studentNameInput" class="form-label">Nama Siswa</label>
                                                            <input type="text" wire:model.defer="nama_siswa" class="form-control" placeholder="Nama Siswa" />
                                                            @error('nama_siswa') 
                                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- NISN -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="nisnInput" class="form-label">NISN</label>
                                                            <input type="text" id="nisn" wire:model.defer="nisn" class="form-control" placeholder="NISN" />
                                                        </div>
                                                    </div>
                                                    <!-- Tempat Lahir -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="birthplaceInput" class="form-label">Tempat Lahir</label>
                                                            <input type="text" id="tempat-lahir" wire:model.defer="tempat_lahir" class="form-control" placeholder="Tempat Lahir" />    
                                                        </div>
                                                    </div>
                                                    <!-- Tanggal Lahir -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="birthdateInput" class="form-label">Tanggal Lahir</label>
                                                            <input type="date" id="tanggal-lahir" wire:model.defer="tanggal_lahir" class="form-control" />
                                                            @error('tanggal_lahir') 
                                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- Nama Ayah -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="fatherNameInput" class="form-label">Nama Ayah</label>
                                                            <input type="text" id="nama-ayah" wire:model.defer="nama_ayah" class="form-control" placeholder="Nama Ayah" />
                                                        </div>
                                                    </div>
                                                    <!-- Nama Ibu -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="motherNameInput" class="form-label">Nama Ibu</label>
                                                            <input type="text" id="nama-ibu" wire:model.defer="nama_ibu" class="form-control" placeholder="Nama Ibu" />
                                                        </div>
                                                    </div>
                                                    <!-- Alamat -->
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="addressInput" class="form-label">Alamat</label>
                                                            <textarea id="alamat" wire:model.defer="alamat" class="form-control" placeholder="Alamat" rows="1"></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Deskripsi -->
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="descriptionInput" class="form-label">Catatan Siswa</label>
                                                            <textarea id="deskripsi" wire:model.defer="deskripsi" class="form-control" placeholder="Subsidi siswa RP200.000..." rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
