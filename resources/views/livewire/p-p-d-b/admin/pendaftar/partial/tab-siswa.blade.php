<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-user-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Data Calon Siswa</h5>
                <small class="text-muted">
                    Lengkapi identitas calon siswa sesuai dengan dokumen resmi.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- 1. IDENTITAS SISWA --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Identitas Siswa</h6>
                        <small class="text-muted">
                            Masukkan identitas sesuai dokumen resmi calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-nama-siswa">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="bantu-nama-siswa"
                            class="form-control @error('nama_siswa') is-invalid @enderror"
                            wire:model.defer="nama_siswa"
                            maxlength="150"
                            placeholder="Nama sesuai dokumen resmi"
                        >
                        @error('nama_siswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-nama-panggilan">
                            Nama Panggilan
                        </label>
                        <input
                            type="text"
                            id="bantu-nama-panggilan"
                            class="form-control @error('nama_panggilan') is-invalid @enderror"
                            wire:model.defer="nama_panggilan"
                            maxlength="50"
                            placeholder="Nama yang biasa digunakan"
                        >
                        @error('nama_panggilan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-nik">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="bantu-nik"
                            class="form-control @error('nik') is-invalid @enderror"
                            wire:model.defer="nik"
                            inputmode="numeric"
                            maxlength="16"
                            placeholder="16 digit NIK"
                        >
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-nisn">NISN</label>
                        <input
                            type="text"
                            id="bantu-nisn"
                            class="form-control @error('nisn') is-invalid @enderror"
                            wire:model.defer="nisn"
                            inputmode="numeric"
                            maxlength="10"
                            placeholder="10 digit NISN (opsional)"
                        >
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-tempat-lahir">
                            Tempat Lahir <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="bantu-tempat-lahir"
                            class="form-control @error('tempat_lahir') is-invalid @enderror"
                            wire:model.defer="tempat_lahir"
                            maxlength="100"
                            placeholder="Contoh: Surakarta"
                        >
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-tanggal-lahir">
                            Tanggal Lahir <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            id="bantu-tanggal-lahir"
                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                            wire:model.defer="tanggal_lahir"
                            max="{{ date('Y-m-d') }}"
                        >
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-jenis-kelamin">
                            Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <select
                            id="bantu-jenis-kelamin"
                            class="form-select @error('jenis_kelamin') is-invalid @enderror"
                            wire:model.defer="jenis_kelamin"
                        >
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-Laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-agama">
                            Agama <span class="text-danger">*</span>
                        </label>
                        <select
                            id="bantu-agama"
                            class="form-select @error('agama') is-invalid @enderror"
                            wire:model.defer="agama"
                        >
                            <option value="">Pilih agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-kewarganegaraan">
                            Kewarganegaraan <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="bantu-kewarganegaraan"
                            class="form-control @error('kewarganegaraan') is-invalid @enderror"
                            wire:model.defer="kewarganegaraan"
                            maxlength="50"
                            placeholder="Contoh: Indonesia"
                        >
                        @error('kewarganegaraan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-cita-cita">Cita-cita</label>
                        <input
                            type="text"
                            id="bantu-cita-cita"
                            class="form-control @error('cita_cita') is-invalid @enderror"
                            wire:model.defer="cita_cita"
                            maxlength="100"
                            placeholder="Cita-cita siswa"
                        >
                        @error('cita_cita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="bantu-hobi">Hobi</label>
                        <textarea
                            id="bantu-hobi"
                            class="form-control @error('hobi') is-invalid @enderror"
                            wire:model.defer="hobi"
                            rows="2"
                            maxlength="255"
                            placeholder="Contoh: membaca, menggambar, bermain sepak bola"
                        ></textarea>
                        @error('hobi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. DATA KELUARGA --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                            <i class="ri-parent-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Data Keluarga</h6>
                        <small class="text-muted">
                            Informasi urutan anak dan kondisi orang tua.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="bantu-anak-ke">
                            Anak ke- <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="bantu-anak-ke"
                            class="form-control @error('anak_ke') is-invalid @enderror"
                            wire:model.defer="anak_ke"
                            min="1"
                            max="100"
                        >
                        @error('anak_ke')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="bantu-saudara-kandung">
                            Jumlah Saudara Kandung <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="bantu-saudara-kandung"
                            class="form-control @error('jumlah_saudara_kandung') is-invalid @enderror"
                            wire:model.defer="jumlah_saudara_kandung"
                            min="0"
                            max="100"
                        >
                        @error('jumlah_saudara_kandung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="bantu-saudara-tiri">
                            Jumlah Saudara Tiri <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="bantu-saudara-tiri"
                            class="form-control @error('jumlah_saudara_tiri') is-invalid @enderror"
                            wire:model.defer="jumlah_saudara_tiri"
                            min="0"
                            max="100"
                        >
                        @error('jumlah_saudara_tiri')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-status-yatim">
                            Status Yatim/Piatu <span class="text-danger">*</span>
                        </label>
                        <select
                            id="bantu-status-yatim"
                            class="form-select @error('status_yatim_piatu') is-invalid @enderror"
                            wire:model.defer="status_yatim_piatu"
                        >
                            <option value="Tidak">Tidak</option>
                            <option value="Yatim">Yatim</option>
                            <option value="Piatu">Piatu</option>
                            <option value="Yatim Piatu">Yatim Piatu</option>
                        </select>
                        @error('status_yatim_piatu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. KONDISI KHUSUS DAN KESEHATAN --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                            <i class="ri-heart-pulse-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Kondisi Khusus dan Kesehatan</h6>
                        <small class="text-muted">
                            Informasi untuk membantu sekolah memberikan pendampingan yang sesuai.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-kebutuhan-khusus">
                            Berkebutuhan Khusus <span class="text-danger">*</span>
                        </label>
                        <select
                            id="bantu-kebutuhan-khusus"
                            class="form-select @error('berkebutuhan_khusus') is-invalid @enderror"
                            wire:model.defer="berkebutuhan_khusus"
                        >
                            <option value="">Pilih status</option>
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                        @error('berkebutuhan_khusus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Informasi ini membantu sekolah menyiapkan dukungan yang sesuai.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-penyakit">
                            Penyakit yang Sedang/Pernah Diderita
                        </label>
                        <textarea
                            id="bantu-penyakit"
                            class="form-control @error('penyakit') is-invalid @enderror"
                            wire:model.defer="penyakit"
                            rows="3"
                            maxlength="5000"
                            placeholder="Tuliskan riwayat penyakit jika ada"
                        ></textarea>
                        @error('penyakit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="bantu-alergi">Alergi</label>
                        <textarea
                            id="bantu-alergi"
                            class="form-control @error('alergi') is-invalid @enderror"
                            wire:model.defer="alergi"
                            rows="3"
                            maxlength="5000"
                            placeholder="Tuliskan alergi jika ada"
                        ></textarea>
                        @error('alergi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. KONDISI FISIK --}}
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                            <i class="ri-ruler-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Kondisi Fisik</h6>
                        <small class="text-muted">
                            Isi berdasarkan pengukuran terbaru jika tersedia.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- Tinggi Badan --}}
                    <div class="col-md-4">
                        <label class="form-label" for="bantu-tinggi-badan">
                            Tinggi Badan
                        </label>
                        <div class="input-group">
                            <input
                                type="number"
                                id="bantu-tinggi-badan"
                                class="form-control @error('tinggi_badan') is-invalid @enderror"
                                wire:model.defer="tinggi_badan"
                                min="0.01"
                                max="300"
                                step="0.01"
                                placeholder="Contoh: 120"
                            >
                            <span class="input-group-text">cm</span>
                            @error('tinggi_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Berat Badan --}}
                    <div class="col-md-4">
                        <label class="form-label" for="bantu-berat-badan">
                            Berat Badan
                        </label>
                        <div class="input-group">
                            <input
                                type="number"
                                id="bantu-berat-badan"
                                class="form-control @error('berat_badan') is-invalid @enderror"
                                wire:model.defer="berat_badan"
                                min="0.01"
                                max="300"
                                step="0.01"
                                placeholder="Contoh: 25"
                            >
                            <span class="input-group-text">kg</span>
                            @error('berat_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Ukuran Baju --}}
                    <div class="col-md-4">
                        <label class="form-label" for="bantu-ukuran-baju">
                            Ukuran Baju <span class="text-danger">*</span>
                        </label>
                        <select
                            id="bantu-ukuran-baju"
                            class="form-select @error('ukuran_baju') is-invalid @enderror"
                            wire:model.defer="ukuran_baju"
                        >
                            <option value="">Pilih ukuran</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                        @error('ukuran_baju')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- 5. BANTUAN SOSIAL --}}
        <div class="card border rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Bantuan Sosial</h6>
                        <small class="text-muted">
                            Pilih bantuan yang diterima siswa jika ada.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3 mb-3">
                    @foreach ([
                        'KPS' => 'Kartu Perlindungan Sosial (KPS)',
                        'KKS' => 'Kartu Keluarga Sejahtera (KKS)',
                        'PKH' => 'Program Keluarga Harapan (PKH)',
                        'KIP' => 'Kartu Indonesia Pintar (KIP)',
                    ] as $kode => $label)
                        <div class="col-md-6" wire:key="bantu-sosial-{{ $kode }}">
                            <div class="form-check border rounded-3 p-3 ps-5 h-100">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="bantu-sosial-{{ $kode }}"
                                    wire:model.defer="penerima_bantuan_sosial"
                                    value="{{ $kode }}"
                                >
                                <label
                                    class="form-check-label"
                                    for="bantu-sosial-{{ $kode }}"
                                >
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('penerima_bantuan_sosial')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                @error('penerima_bantuan_sosial.*')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <div>
                    <label class="form-label" for="bantu-nomor-bantuan-sosial">
                        Nomor KS/KKS/PKH/KIP
                    </label>
                    <input
                        type="text"
                        id="bantu-nomor-bantuan-sosial"
                        class="form-control @error('nomor_bantuan_sosial') is-invalid @enderror"
                        wire:model.defer="nomor_bantuan_sosial"
                        maxlength="100"
                        placeholder="Masukkan nomor kartu jika ada"
                    >
                    @error('nomor_bantuan_sosial')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>