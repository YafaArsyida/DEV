{{-- Be like water. --}}
<div>
    <div wire:ignore.self class="modal fade" id="ModalAddPegawai" tabindex="-1" aria-labelledby="ModalAddPegawai" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Pegawai Baru</h5>
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
                                                    <i class="ri-home-3-fill"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="jenjangInput" placeholder="Jenjang Pendidikan"
                                                value="{{ $nama_jenjang ?? 'Tidak tersedia' }}" readonly>
                                        </div>

                                        <!-- Informasi Jabatan -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-primary text-white shadow">
                                                    <i class="ri-medal-fill"></i>
                                                </span>
                                            </div>
                                            <select id="ms_jabatan_id" wire:model.defer="ms_jabatan_id" class="form-select">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach ($select_jabatan as $item)    
                                                <option value="{{ $item->ms_jabatan_id }}">{{ $item->nama_jabatan }}</option>
                                                @endforeach
                                            </select>
                                            @error('ms_jabatan_id') 
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
                                        <!-- Email -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-danger text-white shadow">
                                                    <i class="ri-at-line"></i>
                                                </span>
                                            </div>
                                            <input type="text" wire:model.defer="email" class="form-control" placeholder="E-Mail" />
                                            @error('email') 
                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                            @enderror
                                        </div>

                                        <!-- EduCard -->
                                        <div class="mb-3 d-flex">
                                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                                <span class="avatar-title rounded-circle fs-16 bg-warning text-white shadow">
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

                            <!-- Informasi Pegawai -->
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Informasi Pegawai</h5>
                                            </div>
                                        </div>

                                        <div class="tab-content">
                                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                                <div class="row">
                                                    <!-- Nama Siswa -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="studentNameInput" class="form-label">Nama Pegawai</label>
                                                            <input type="text" wire:model.defer="nama_pegawai" class="form-control" placeholder="Nama Siswa" />
                                                            @error('nama_pegawai') 
                                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <!-- NIP -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="nip" class="form-label">Nomor Induk Pegawai</label>
                                                            <input type="text" id="nip" wire:model.defer="nip" class="form-control" placeholder="NIP" />
                                                            @error('nip') 
                                                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                                            @enderror
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
                                                            <label for="descriptionInput" class="form-label">Catatan Pegawai</label>
                                                            <textarea id="deskripsi" wire:model.defer="deskripsi" class="form-control" placeholder="Guru Matematika SD..." rows="2"></textarea>
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
