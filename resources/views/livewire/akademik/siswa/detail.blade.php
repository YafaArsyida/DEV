<div wire:ignore.self
    class="modal fade"
    id="ModalDetailSiswa"
    tabindex="-1"
    aria-labelledby="ModalDetailSiswaLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" id="ModalDetailSiswaLabel">
                            Detail Siswa
                        </h5>
                        <small class="text-muted">
                            Informasi pribadi dan akademik siswa.
                        </small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body px-4 pt-2 pb-4">

                {{-- CONTEXT --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            {{ $siswaDetail->ms_jenjang->nama_jenjang ?? '-' }}
                        </span>
                        <span class="text-muted">
                            {{ $siswaDetail->ms_tahun_ajar->nama_tahun_ajar ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <small class="text-muted">
                            Tabungan:
                            <span class="fw-semibold text-success">Rp {{ number_format($saldo_tabungan, 0, ',', '.') }}</span>
                        </small>
                        <small class="text-muted">
                            EduPay:
                            <span class="fw-semibold text-primary">Rp {{ number_format($saldo_edupay, 0, ',', '.') }}</span>
                        </small>
                    </div>
                </div>

                {{-- DATA UTAMA --}}
                <section class="mb-4">
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Data Utama</h6>
                        <small class="text-muted">Informasi dasar dan penempatan siswa.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Siswa</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->nama_siswa ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">NISN</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->nisn ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_kelas->nama_kelas ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->telepon ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->tempat_lahir ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->tanggal_lahir ?? '-' }}</div>
                        </div>
                    </div>
                </section>

                {{-- DATA KELUARGA --}}
                <section class="mb-4 pt-1">
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Data Keluarga</h6>
                        <small class="text-muted">Informasi orang tua dan alamat siswa.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Ayah</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->nama_ayah ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Ibu</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->nama_ibu ?? '-' }}</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <div class="form-control bg-light text-wrap">{{ $siswaDetail->ms_siswa->alamat ?? '-' }}</div>
                        </div>
                    </div>
                </section>

                {{-- INFORMASI TAMBAHAN --}}
                <section>
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Informasi Tambahan</h6>
                        <small class="text-muted">Informasi pendukung administrasi siswa.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">EduCard</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_siswa->ms_educard?->kode_kartu ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Petugas</label>
                            <div class="form-control bg-light">{{ $siswaDetail->ms_pengguna?->nama ?? '-' }}</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan Siswa</label>
                            <div class="form-control bg-light text-wrap">{{ $siswaDetail->ms_siswa->deskripsi ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="ri-time-line me-1"></i>
                            Terdaftar pada {{ $created_at_formatted ?? '-' }}
                        </small>
                    </div>
                </section>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>