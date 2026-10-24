<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-transparent">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-home-4-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Alamat Tempat Tinggal
                </h5>
                <small class="text-muted">
                    Informasi domisili calon siswa saat ini.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">

        {{-- ===================================================== --}}
        {{-- INFORMASI ALAMAT TEMPAT TINGGAL --}}
        {{-- ===================================================== --}}
        <div class="card border rounded-4 shadow-none mb-4">

            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-home-4-line"></i>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-semibold mb-1">Informasi Alamat Tempat Tinggal</h6>
                        <small class="text-muted">
                            Alamat domisili calon siswa saat ini.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- Alamat Lengkap --}}
                    <div class="col-12">
                        <label class="form-label" for="bantu-alamat-lengkap">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>

                        <textarea
                            id="bantu-alamat-lengkap"
                            class="form-control @error('alamat_lengkap') is-invalid @enderror"
                            wire:model.defer="alamat_lengkap"
                            rows="3"
                            placeholder="Nama jalan, nomor rumah, dusun, atau patokan alamat"></textarea>

                        @error('alamat_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Cantumkan nama jalan, nomor rumah, dusun, atau patokan
                            untuk memudahkan identifikasi alamat.
                        </small>
                    </div>

                    {{-- Provinsi --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="bantu-provinsi">
                            Provinsi <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="bantu-provinsi"
                            class="form-control @error('provinsi') is-invalid @enderror"
                            wire:model.defer="provinsi"
                            maxlength="100"
                            placeholder="Contoh: Jawa Tengah">

                        @error('provinsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kabupaten/Kota --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="bantu-kabupaten">
                            Kabupaten/Kota <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="bantu-kabupaten"
                            class="form-control @error('kabupaten') is-invalid @enderror"
                            wire:model.defer="kabupaten"
                            maxlength="100"
                            placeholder="Contoh: Kabupaten Sragen">

                        @error('kabupaten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kecamatan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="bantu-kecamatan">
                            Kecamatan <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="bantu-kecamatan"
                            class="form-control @error('kecamatan') is-invalid @enderror"
                            wire:model.defer="kecamatan"
                            maxlength="100"
                            placeholder="Nama kecamatan">

                        @error('kecamatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="bantu-kelurahan-desa">
                            Desa/Kelurahan <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="bantu-kelurahan-desa"
                            class="form-control @error('kelurahan_desa') is-invalid @enderror"
                            wire:model.defer="kelurahan_desa"
                            maxlength="100"
                            placeholder="Nama desa/kelurahan">

                        @error('kelurahan_desa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- RT --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="bantu-rt">RT</label>

                        <input
                            type="text"
                            id="bantu-rt"
                            class="form-control @error('rt') is-invalid @enderror"
                            wire:model.defer="rt"
                            maxlength="3"
                            inputmode="numeric"
                            placeholder="Contoh: 003">

                        @error('rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- RW --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="bantu-rw">RW</label>

                        <input
                            type="text"
                            id="bantu-rw"
                            class="form-control @error('rw') is-invalid @enderror"
                            wire:model.defer="rw"
                            maxlength="3"
                            inputmode="numeric"
                            placeholder="Contoh: 005">

                        @error('rw')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kode Pos --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="bantu-kode-pos">Kode Pos</label>

                        <input
                            type="text"
                            id="bantu-kode-pos"
                            class="form-control @error('kode_pos') is-invalid @enderror"
                            wire:model.defer="kode_pos"
                            maxlength="5"
                            inputmode="numeric"
                            placeholder="5 digit kode pos">

                        @error('kode_pos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Tempat Tinggal --}}
                    <div class="col-12">
                        <label class="form-label" for="bantu-jenis-tempat-tinggal">
                            Jenis Tempat Tinggal <span class="text-danger">*</span>
                        </label>

                        <select
                            id="bantu-jenis-tempat-tinggal"
                            class="form-select @error('jenis_tempat_tinggal') is-invalid @enderror"
                            wire:model.defer="jenis_tempat_tinggal">

                            <option value="">Pilih jenis tempat tinggal</option>
                            <option value="rumah_orang_tua">Rumah orang tua</option>
                            <option value="rumah_saudara">Rumah saudara/keluarga</option>
                            <option value="kos_kontrak">Kos/kontrak</option>
                            <option value="lainnya">Lainnya</option>
                        </select>

                        @error('jenis_tempat_tinggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- INFORMASI PERJALANAN KE SEKOLAH --}}
        {{-- ===================================================== --}}
        <div class="card border rounded-4 shadow-none mb-0">

            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                            <i class="ri-map-pin-line"></i>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-semibold mb-1">Informasi Perjalanan ke Sekolah</h6>
                        <small class="text-muted">
                            Informasi jarak, waktu tempuh, dan transportasi calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- Jarak ke Sekolah --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="bantu-jarak-ke-sekolah">
                            Jarak ke Sekolah <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <input
                                type="number"
                                id="bantu-jarak-ke-sekolah"
                                class="form-control @error('jarak_ke_sekolah') is-invalid @enderror"
                                wire:model.defer="jarak_ke_sekolah"
                                min="0"
                                max="999999.99"
                                step="0.01"
                                placeholder="Contoh: 2.5">

                            <span class="input-group-text">km</span>

                            @error('jarak_ke_sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <small class="text-muted">
                            Perkiraan jarak satu arah dari tempat tinggal ke sekolah.
                        </small>
                    </div>

                    {{-- Waktu Tempuh --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="bantu-waktu-tempuh-ke-sekolah">
                            Waktu Tempuh <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <input
                                type="number"
                                id="bantu-waktu-tempuh-ke-sekolah"
                                class="form-control @error('waktu_tempuh_ke_sekolah') is-invalid @enderror"
                                wire:model.defer="waktu_tempuh_ke_sekolah"
                                min="1"
                                max="65535"
                                step="1"
                                inputmode="numeric"
                                placeholder="Contoh: 15">

                            <span class="input-group-text">menit</span>

                            @error('waktu_tempuh_ke_sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <small class="text-muted">
                            Perkiraan waktu perjalanan normal dalam satu arah.
                        </small>
                    </div>

                    {{-- Moda Transportasi --}}
                    <div class="col-12">
                        <label class="form-label" for="bantu-moda-transportasi">
                            Moda Transportasi <span class="text-danger">*</span>
                        </label>

                        <select
                            id="bantu-moda-transportasi"
                            class="form-select @error('moda_transportasi') is-invalid @enderror"
                            wire:model.defer="moda_transportasi">

                            <option value="">Pilih moda transportasi</option>
                            <option value="jalan_kaki">Jalan kaki</option>
                            <option value="sepeda">Sepeda</option>
                            <option value="sepeda_motor">Sepeda motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="angkutan_umum">Angkutan umum</option>
                            <option value="antar_jemput">Antar-jemput sekolah</option>
                            <option value="lainnya">Lainnya</option>
                        </select>

                        @error('moda_transportasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Pilih transportasi yang paling sering digunakan calon siswa.
                        </small>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>