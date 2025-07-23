<div>
    <div wire:ignore.self class="modal fade" id="ModalDetailSiswa" tabindex="-1" aria-labelledby="ModalDetailSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detail Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Personalisasi Aplikasi -->
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
                                        <input type="text" class="form-control" id="kelasInput" placeholder="Nama Kelas"
                                            value="{{ $nama_kelas ?? 'Tidak tersedia' }}" readonly>
                                    </div>

                                    <!-- Telepon -->
                                    <div class="mb-3 d-flex">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <span class="avatar-title rounded-circle fs-16 bg-body text-body shadow">
                                                <i class="ri-phone-fill"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="phoneInput" placeholder="Nomor Telepon"
                                            value="{{ $telepon ?? 'Belum memiliki telepon' }}" readonly>
                                    </div>

                                    <!-- EduCard -->
                                    <div class="mb-3 d-flex">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <span class="avatar-title rounded-circle fs-16 bg-body text-body shadow">
                                                <i class="ri-bank-card-fill"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="educardInput" placeholder="Kode Kartu"
                                            value="{{ $educard ?? 'Belum memiliki kartu' }}" readonly>
                                    </div>

                                    <!-- Saldo EduPay -->
                                    <div class="mb-3 d-flex">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <span class="avatar-title rounded-circle fs-16 bg-primary text-white shadow">
                                                <i class="ri-wallet-3-fill"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="edupaySaldo" placeholder="Saldo EduPay"
                                            value="Rp{{ number_format($edupay, 0, ',', '.') }}" readonly>
                                    </div>

                                     <!-- Informasi Petugas -->
                                    <div class="mb-3 d-flex">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <span class="avatar-title rounded-circle fs-16 bg-secondary text-white shadow">
                                                <i class="ri-user-3-fill"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="petugasInput" placeholder="Nama Petugas"
                                            value="{{ $nama_petugas ?? 'Tidak tersedia' }}" readonly>
                                    </div>
                                    
                                    <!-- Tanggal Terdaftar -->
                                    <div class="d-flex">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <span class="avatar-title rounded-circle fs-16 bg-success text-white shadow">
                                                <i class="ri-time-fill"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="registeredDate" placeholder="Tanggal Terdaftar"
                                            value="{{ $created_at }}" readonly>
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
                                            <form action="javascript:void(0);">
                                                <div class="row">
                                                    <!-- Nama Siswa -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="studentNameInput" class="form-label">Nama Siswa</label>
                                                            <input type="text" class="form-control" id="studentNameInput"
                                                                placeholder="Masukkan nama siswa" value="{{ $nama_siswa }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- NISN -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="nisnInput" class="form-label">NISN</label>
                                                            <input type="text" class="form-control" id="nisnInput"
                                                                placeholder="Masukkan NISN" value="{{ $nisn }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Tempat Lahir -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="birthplaceInput" class="form-label">Tempat Lahir</label>
                                                            <input type="text" class="form-control" id="birthplaceInput"
                                                                placeholder="Masukkan tempat lahir" value="{{ $tempat_lahir }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Tanggal Lahir -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="birthdateInput" class="form-label">Tanggal Lahir</label>
                                                            <input type="text" class="form-control" id="birthdateInput"
                                                                placeholder="Masukkan tanggal lahir" value="{{ $tanggal_lahir }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Nama Ayah -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="fatherNameInput" class="form-label">Nama Ayah</label>
                                                            <input type="text" class="form-control" id="fatherNameInput"
                                                                placeholder="Masukkan nama ayah" value="{{ $nama_ayah }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Nama Ibu -->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="motherNameInput" class="form-label">Nama Ibu</label>
                                                            <input type="text" class="form-control" id="motherNameInput"
                                                                placeholder="Masukkan nama ibu" value="{{ $nama_ibu }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Alamat -->
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="addressInput" class="form-label">Alamat</label>
                                                            <input type="text" class="form-control" id="addressInput"
                                                                placeholder="Masukkan alamat" value="{{ $alamat }}" readonly>
                                                        </div>
                                                    </div>
                                                    <!-- Deskripsi -->
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="descriptionInput" class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" id="descriptionInput" rows="3"
                                                                placeholder="Masukkan deskripsi" readonly>{{ $deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
