
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-user-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Ubah Data Calon Siswa</h5>
                <small class="text-muted">
                    Perbarui identitas calon siswa dengan benar.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        <form wire:submit.prevent="update">

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
                                Perbarui identitas sesuai dokumen resmi calon siswa.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        {{-- Nama Lengkap --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-nama-siswa" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="edit-nama-siswa"
                                class="form-control @error('nama_siswa') is-invalid @enderror"
                                wire:model.defer="nama_siswa"
                                maxlength="150" autocomplete="name">
                            @error('nama_siswa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Panggilan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-nama-panggilan" class="form-label">
                                Nama Panggilan
                            </label>
                            <input type="text" id="edit-nama-panggilan"
                                class="form-control @error('nama_panggilan') is-invalid @enderror"
                                wire:model.defer="nama_panggilan" maxlength="50">
                            @error('nama_panggilan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NIK --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-nik" class="form-label">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="edit-nik"
                                class="form-control @error('nik') is-invalid @enderror"
                                wire:model.defer="nik" inputmode="numeric" maxlength="16">
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NISN --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-nisn" class="form-label">NISN</label>
                            <input type="text" id="edit-nisn"
                                class="form-control @error('nisn') is-invalid @enderror"
                                wire:model.defer="nisn" inputmode="numeric" maxlength="10">
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-tempat-lahir" class="form-label">
                                Tempat Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="edit-tempat-lahir"
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                wire:model.defer="tempat_lahir" maxlength="100">
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-tanggal-lahir" class="form-label">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="edit-tanggal-lahir"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                wire:model.defer="tanggal_lahir"
                                max="{{ now()->format('Y-m-d') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-jenis-kelamin" class="form-label">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select id="edit-jenis-kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                wire:model.defer="jenis_kelamin">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="Laki-Laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Agama --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-agama" class="form-label">
                                Agama <span class="text-danger">*</span>
                            </label>
                            <select id="edit-agama"
                                class="form-select @error('agama') is-invalid @enderror"
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

                        {{-- Kewarganegaraan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-kewarganegaraan" class="form-label">
                                Kewarganegaraan <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="edit-kewarganegaraan"
                                class="form-control @error('kewarganegaraan') is-invalid @enderror"
                                wire:model.defer="kewarganegaraan" maxlength="50">
                            @error('kewarganegaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Cita-cita --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-cita-cita" class="form-label">Cita-cita</label>
                            <input type="text" id="edit-cita-cita"
                                class="form-control @error('cita_cita') is-invalid @enderror"
                                wire:model.defer="cita_cita" maxlength="100">
                            @error('cita_cita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hobi --}}
                        <div class="col-12">
                            <label for="edit-hobi" class="form-label">Hobi</label>
                            <textarea id="edit-hobi" rows="2"
                                class="form-control @error('hobi') is-invalid @enderror"
                                wire:model.defer="hobi" maxlength="255"
                                placeholder="Contoh: membaca, menggambar, olahraga"></textarea>
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
                                Perbarui informasi urutan anak dan kondisi keluarga.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        {{-- Anak Ke --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-anak-ke" class="form-label">
                                Anak ke- <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="edit-anak-ke"
                                class="form-control @error('anak_ke') is-invalid @enderror"
                                wire:model.defer="anak_ke" min="1" max="100">
                            @error('anak_ke')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Saudara Kandung --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-saudara-kandung" class="form-label">
                                Jumlah Saudara Kandung <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="edit-saudara-kandung"
                                class="form-control @error('jumlah_saudara_kandung') is-invalid @enderror"
                                wire:model.defer="jumlah_saudara_kandung" min="0" max="100">
                            @error('jumlah_saudara_kandung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Saudara Tiri --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-saudara-tiri" class="form-label">
                                Jumlah Saudara Tiri <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="edit-saudara-tiri"
                                class="form-control @error('jumlah_saudara_tiri') is-invalid @enderror"
                                wire:model.defer="jumlah_saudara_tiri" min="0" max="100">
                            @error('jumlah_saudara_tiri')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Yatim Piatu --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-status-yatim-piatu" class="form-label">
                                Status Yatim/Piatu <span class="text-danger">*</span>
                            </label>
                            <select id="edit-status-yatim-piatu"
                                class="form-select @error('status_yatim_piatu') is-invalid @enderror"
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
                                Informasi ini membantu sekolah menyiapkan dukungan yang sesuai.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        {{-- Berkebutuhan Khusus --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-berkebutuhan-khusus" class="form-label">
                                Berkebutuhan Khusus <span class="text-danger">*</span>
                            </label>
                            <select id="edit-berkebutuhan-khusus"
                                class="form-select @error('berkebutuhan_khusus') is-invalid @enderror"
                                wire:model.defer="berkebutuhan_khusus">
                                <option value="">Pilih status</option>
                                <option value="Tidak">Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>
                            @error('berkebutuhan_khusus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Pilih sesuai kondisi calon siswa.
                            </div>
                        </div>

                        {{-- Penyakit --}}
                        <div class="col-12">
                            <label for="edit-penyakit" class="form-label">Riwayat Penyakit</label>
                            <textarea id="edit-penyakit" rows="3"
                                class="form-control @error('penyakit') is-invalid @enderror"
                                wire:model.defer="penyakit"
                                placeholder="Tuliskan riwayat penyakit yang perlu diketahui sekolah, jika ada"></textarea>
                            @error('penyakit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alergi --}}
                        <div class="col-12">
                            <label for="edit-alergi" class="form-label">Alergi</label>
                            <textarea id="edit-alergi" rows="3"
                                class="form-control @error('alergi') is-invalid @enderror"
                                wire:model.defer="alergi"
                                placeholder="Tuliskan alergi makanan, obat, atau lainnya, jika ada"></textarea>
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
                                Perbarui ukuran fisik calon siswa jika sudah diketahui.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        {{-- Tinggi Badan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-tinggi-badan" class="form-label">
                                Tinggi Badan (cm)
                            </label>
                            <input type="number" id="edit-tinggi-badan"
                                class="form-control @error('tinggi_badan') is-invalid @enderror"
                                wire:model.defer="tinggi_badan"
                                min="0.01" max="300" step="0.01"
                                placeholder="Contoh: 125">
                            @error('tinggi_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Berat Badan --}}
                        <div class="col-12 col-md-6">
                            <label for="edit-berat-badan" class="form-label">
                                Berat Badan (kg)
                            </label>
                            <input type="number" id="edit-berat-badan"
                                class="form-control @error('berat_badan') is-invalid @enderror"
                                wire:model.defer="berat_badan"
                                min="0.01" max="300" step="0.01"
                                placeholder="Contoh: 25.5">
                            @error('berat_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                Pilih bantuan sosial yang diterima calon siswa, jika ada.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label d-block">Jenis Bantuan Sosial</label>

                            @php
                                $opsiBantuanSosial = [
                                    'KPS' => 'Kartu Perlindungan Sosial (KPS)',
                                    'KKS' => 'Kartu Keluarga Sejahtera (KKS)',
                                    'PKH' => 'Program Keluarga Harapan (PKH)',
                                    'KIP' => 'Kartu Indonesia Pintar (KIP)',
                                ];
                            @endphp

                            <div class="row g-2">
                                @foreach ($opsiBantuanSosial as $kode => $label)
                                    <div class="col-12 col-md-6" wire:key="edit-bantuan-{{ $kode }}">
                                        <div class="form-check border rounded-3 p-3 ps-5 h-100">
                                            <input type="checkbox"
                                                id="edit-bantuan-{{ $kode }}"
                                                class="form-check-input"
                                                wire:model.defer="penerima_bantuan_sosial"
                                                value="{{ $kode }}">
                                            <label class="form-check-label"
                                                for="edit-bantuan-{{ $kode }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('penerima_bantuan_sosial')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror

                            @foreach ($opsiBantuanSosial as $kode => $label)
                                @error('penerima_bantuan_sosial.' . $loop->index)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            @endforeach
                        </div>

                        {{-- Nomor Bantuan --}}
                        <div class="col-12">
                            <label for="edit-nomor-bantuan-sosial" class="form-label">
                                Nomor Kartu/Identitas Bantuan
                            </label>
                            <input type="text" id="edit-nomor-bantuan-sosial"
                                class="form-control @error('nomor_bantuan_sosial') is-invalid @enderror"
                                wire:model.defer="nomor_bantuan_sosial"
                                maxlength="100"
                                placeholder="Masukkan nomor kartu jika ada">
                            @error('nomor_bantuan_sosial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Kosongkan jika tidak memiliki nomor bantuan sosial.
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
                <a href="{{ request()->url() }}"
                    class="btn btn-light border rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Batal
                </a>

                <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="update"
                    class="btn btn-primary rounded-pill px-4">
                    <span wire:loading.remove wire:target="update">
                        <i class="ri-save-line me-1"></i>
                        Simpan Perubahan
                    </span>
                    <span wire:loading wire:target="update">
                        <span class="spinner-border spinner-border-sm me-1"
                            role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>
