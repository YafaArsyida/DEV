<div wire:ignore.self
    class="modal fade"
    id="ModalAddSiswa"
    tabindex="-1"
    aria-labelledby="ModalAddSiswaLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-add-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalAddSiswaLabel">
                            Tambah Siswa Baru
                        </h5>

                        <small class="text-muted">
                            Tambahkan data siswa untuk tahun ajaran aktif.
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


            <form wire:submit.prevent="save">

                {{-- BODY --}}
                <div class="modal-body px-4 pt-2 pb-4">

                    {{-- CONTEXT --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-3 border-bottom">

                        <div class="d-flex align-items-center gap-2">

                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                {{ $nama_jenjang ?? '-' }}
                            </span>

                            <span class="text-muted">
                                {{ $nama_tahun_ajar ?? '-' }}
                            </span>

                        </div>

                        <small class="text-muted">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </small>

                    </div>


                    {{-- DATA UTAMA --}}
                    <section class="mb-4">

                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Data Utama
                            </h6>

                            <small class="text-muted">
                                Informasi dasar dan penempatan siswa.
                            </small>
                        </div>


                        <div class="row g-3">

                            {{-- NAMA --}}
                            <div class="col-md-8">

                                <label class="form-label">
                                    Nama Siswa
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    autofocus
                                    wire:model.defer="form.nama_siswa"
                                    class="form-control @error('form.nama_siswa') is-invalid @enderror">

                                @error('form.nama_siswa')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- NISN --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    NISN
                                </label>

                                <input type="text"
                                    wire:model.defer="form.nisn"
                                    class="form-control">

                            </div>


                            {{-- KELAS --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Kelas
                                    <span class="text-danger">*</span>
                                </label>

                                <select wire:model.defer="form.ms_kelas_id"
                                    class="form-select @error('form.ms_kelas_id') is-invalid @enderror">

                                    <option value="">
                                        Pilih Kelas
                                    </option>

                                    @foreach ($selectKelas as $item)
                                        <option value="{{ $item->ms_kelas_id }}">
                                            {{ $item->nama_kelas }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('form.ms_kelas_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- TELEPON --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Telepon
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    wire:model.defer="form.telepon"
                                    class="form-control @error('form.telepon') is-invalid @enderror"
                                    placeholder="08xxxxxxxxxx">

                                @error('form.telepon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- TEMPAT LAHIR --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Tempat Lahir
                                </label>

                                <input type="text"
                                    wire:model.defer="form.tempat_lahir"
                                    class="form-control">

                            </div>


                            {{-- TANGGAL LAHIR --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                    wire:model.defer="form.tanggal_lahir"
                                    class="form-control @error('form.tanggal_lahir') is-invalid @enderror">

                                @error('form.tanggal_lahir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </section>


                    {{-- DATA KELUARGA --}}
                    <section class="mb-4 pt-1">

                        <div class="mb-3">

                            <h6 class="fw-semibold mb-1">
                                Data Keluarga
                            </h6>

                            <small class="text-muted">
                                Informasi orang tua dan alamat siswa.
                            </small>

                        </div>


                        <div class="row g-3">

                            {{-- AYAH --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Nama Ayah
                                </label>

                                <input type="text"
                                    wire:model.defer="form.nama_ayah"
                                    class="form-control">

                            </div>


                            {{-- IBU --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Nama Ibu
                                </label>

                                <input type="text"
                                    wire:model.defer="form.nama_ibu"
                                    class="form-control">

                            </div>


                            {{-- ALAMAT --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Alamat
                                </label>

                                <textarea wire:model.defer="form.alamat"
                                    class="form-control"
                                    rows="2"></textarea>

                            </div>

                        </div>

                    </section>


                    {{-- INFORMASI TAMBAHAN --}}
                    <section>

                        <div class="mb-3">

                            <h6 class="fw-semibold mb-1">
                                Informasi Tambahan
                            </h6>

                            <small class="text-muted">
                                Informasi pendukung administrasi siswa.
                            </small>

                        </div>


                        <div class="row g-3">

                            {{-- EDUCARD --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    EduCard
                                </label>

                                <input type="text"
                                    wire:model.defer="form.educard"
                                    class="form-control @error('form.educard') is-invalid @enderror"
                                    placeholder="ID kartu">

                                @error('form.educard')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- PETUGAS --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Petugas
                                </label>

                                <input type="text"
                                    class="form-control bg-light"
                                    value="{{ auth()->user()->nama ?? '-' }}"
                                    disabled>

                            </div>


                            {{-- CATATAN --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Catatan Siswa
                                </label>

                                <textarea wire:model.defer="form.deskripsi"
                                    class="form-control"
                                    rows="2"></textarea>

                            </div>

                        </div>


                        {{-- SYSTEM INFO --}}
                        <div class="mt-3 pt-3 border-top">

                            <div class="d-flex flex-wrap justify-content-between gap-2">

                                <small class="text-muted">
                                    <i class="ri-user-line me-1"></i>
                                    Petugas:
                                    <span class="fw-medium text-body">
                                        {{ auth()->user()->nama ?? '-' }}
                                    </span>
                                </small>

                                <small class="text-muted">
                                    <i class="ri-time-line me-1"></i>
                                    {{ now()->format('d M Y H:i') }}
                                </small>

                            </div>

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

                    <button type="submit"
                        class="btn btn-primary rounded-pill px-4">

                        <i class="ri-save-3-line me-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>