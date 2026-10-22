<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- Header --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            <div>
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
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

            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                Langkah 1 dari 6
            </span>

        </div>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="simpan">
            {{-- 1. Identitas Siswa --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
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
                            <label class="form-label" for="nama-lengkap">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('nama_siswa') is-invalid @enderror"
                                id="nama-lengkap"
                                wire:model.defer="nama_siswa"
                                maxlength="150"
                                autocomplete="name"
                                placeholder="Nama sesuai dokumen resmi">

                            @error('nama_siswa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="nama-panggilan">
                                Nama Panggilan
                            </label>
                            <input type="text"
                                class="form-control @error('nama_panggilan') is-invalid @enderror"
                                id="nama-panggilan"
                                wire:model.defer="nama_panggilan"
                                maxlength="50"
                                placeholder="Nama yang biasa digunakan">

                            @error('nama_panggilan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="nik">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('nik') is-invalid @enderror"
                                id="nik"
                                wire:model.defer="nik"
                                inputmode="numeric"
                                maxlength="16"
                                placeholder="16 digit NIK">

                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="nisn">NISN</label>
                            <input type="text"
                                class="form-control @error('nisn') is-invalid @enderror"
                                id="nisn"
                                wire:model.defer="nisn"
                                inputmode="numeric"
                                maxlength="10"
                                placeholder="10 digit NISN (opsional)">

                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="tempat-lahir">
                                Tempat Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                id="tempat-lahir"
                                wire:model.defer="tempat_lahir"
                                maxlength="100"
                                placeholder="Contoh: Surakarta">

                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="tanggal-lahir">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal-lahir"
                                wire:model.defer="tanggal_lahir"
                                max="{{ date('Y-m-d') }}">

                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="jenis-kelamin">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                id="jenis-kelamin"
                                wire:model.defer="jenis_kelamin">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="Laki-Laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>

                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="agama">
                                Agama <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select @error('agama') is-invalid @enderror"
                                id="agama"
                                wire:model.defer="agama">
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
                            <label class="form-label" for="kewarganegaraan">
                                Kewarganegaraan <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('kewarganegaraan') is-invalid @enderror"
                                id="kewarganegaraan"
                                wire:model.defer="kewarganegaraan"
                                maxlength="50"
                                placeholder="Contoh: Indonesia">

                            @error('kewarganegaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="cita-cita">Cita-cita</label>
                            <input type="text"
                                class="form-control @error('cita_cita') is-invalid @enderror"
                                id="cita-cita"
                                wire:model.defer="cita_cita"
                                maxlength="100"
                                placeholder="Cita-cita siswa">

                            @error('cita_cita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="hobi">Hobi</label>
                            <textarea
                                class="form-control @error('hobi') is-invalid @enderror"
                                id="hobi"
                                wire:model.defer="hobi"
                                rows="2"
                                maxlength="255"
                                placeholder="Contoh: membaca, menggambar, bermain sepak bola"></textarea>

                            @error('hobi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Data Keluarga --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
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
                            <label class="form-label" for="anak-ke">
                                Anak ke- <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control @error('anak_ke') is-invalid @enderror"
                                id="anak-ke"
                                wire:model.defer="anak_ke"
                                min="1"
                                max="100">

                            @error('anak_ke')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="jumlah-saudara-kandung">
                                Jumlah Saudara Kandung <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control @error('jumlah_saudara_kandung') is-invalid @enderror"
                                id="jumlah-saudara-kandung"
                                wire:model.defer="jumlah_saudara_kandung"
                                min="0"
                                max="100">

                            @error('jumlah_saudara_kandung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="jumlah-saudara-tiri">
                                Jumlah Saudara Tiri <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control @error('jumlah_saudara_tiri') is-invalid @enderror"
                                id="jumlah-saudara-tiri"
                                wire:model.defer="jumlah_saudara_tiri"
                                min="0"
                                max="100">

                            @error('jumlah_saudara_tiri')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="status-yatim-piatu">
                                Status Yatim/Piatu <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select @error('status_yatim_piatu') is-invalid @enderror"
                                id="status-yatim-piatu"
                                wire:model.defer="status_yatim_piatu">
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

            {{-- 3. Kondisi Khusus dan Kesehatan --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
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
                            <label class="form-label" for="berkebutuhan-khusus">
                                Berkebutuhan Khusus <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select @error('berkebutuhan_khusus') is-invalid @enderror"
                                id="berkebutuhan-khusus"
                                wire:model.defer="berkebutuhan_khusus">

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
                            <label class="form-label" for="penyakit">
                                Penyakit yang Sedang/Pernah Diderita
                            </label>
                            <textarea
                                class="form-control @error('penyakit') is-invalid @enderror"
                                id="penyakit"
                                wire:model.defer="penyakit"
                                rows="3"
                                maxlength="5000"
                                placeholder="Tuliskan riwayat penyakit atau Tidak ada"></textarea>

                            @error('penyakit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="alergi">
                                Alergi
                            </label>
                            <textarea
                                class="form-control @error('alergi') is-invalid @enderror"
                                id="alergi"
                                wire:model.defer="alergi"
                                rows="3"
                                maxlength="5000"
                                placeholder="Contoh: alergi makanan atau obat, atau Tidak ada"></textarea>

                            @error('alergi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Kondisi Fisik --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
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
                        <div class="col-md-6">
                            <label class="form-label" for="tinggi-badan">
                                Tinggi Badan (cm)
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control @error('tinggi_badan') is-invalid @enderror"
                                    id="tinggi-badan"
                                    wire:model.defer="tinggi_badan"
                                    min="0.01"
                                    max="300"
                                    step="0.01"
                                    placeholder="Contoh: 120">
                                <span class="input-group-text">cm</span>

                                @error('tinggi_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="berat-badan">
                                Berat Badan (kg)
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control @error('berat_badan') is-invalid @enderror"
                                    id="berat-badan"
                                    wire:model.defer="berat_badan"
                                    min="0.01"
                                    max="300"
                                    step="0.01"
                                    placeholder="Contoh: 25">
                                <span class="input-group-text">kg</span>

                                @error('berat_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Bantuan Sosial --}}
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
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
                            <div class="col-md-6" wire:key="bantuan-{{ $kode }}">
                                <div class="form-check border rounded-3 p-3 ps-5 h-100">
                                    <input
                                        class="form-check-input @error('penerima_bantuan_sosial') is-invalid @enderror"
                                        type="checkbox"
                                        id="bantuan-{{ $kode }}"
                                        wire:model.defer="penerima_bantuan_sosial"
                                        value="{{ $kode }}">
                                    <label class="form-check-label"
                                        for="bantuan-{{ $kode }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('penerima_bantuan_sosial')
                        <div class="text-danger fs-12 mb-2">{{ $message }}</div>
                    @enderror

                    @error('penerima_bantuan_sosial.*')
                        <div class="text-danger fs-12 mb-2">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label class="form-label" for="nomor-bantuan-sosial">
                            Nomor KS/KKS/PKH/KIP
                        </label>
                        <input type="text"
                            class="form-control @error('nomor_bantuan_sosial') is-invalid @enderror"
                            id="nomor-bantuan-sosial"
                            wire:model.defer="nomor_bantuan_sosial"
                            maxlength="100"
                            placeholder="Masukkan nomor kartu jika ada">

                        @error('nomor_bantuan_sosial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Navigasi --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('ppdb.pendaftaran.jalur', [
                        'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                    ]) }}"
                    class="btn btn-light border rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Kembali
                </a>

                <button type="submit"
                    class="btn btn-primary rounded-pill px-4"
                    wire:loading.attr="disabled"
                    wire:target="simpan">

                    <span wire:loading.remove wire:target="simpan">
                        Simpan &amp; Lanjutkan
                        <i class="ri-arrow-right-line ms-1"></i>
                    </span>

                    <span wire:loading wire:target="simpan">
                        <span class="spinner-border spinner-border-sm me-1"
                            role="status"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>