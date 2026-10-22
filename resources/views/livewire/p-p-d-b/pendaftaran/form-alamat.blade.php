<div class="card border shadow-none rounded-4 mb-4">
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
        <form wire:submit.prevent="simpan">
            <div class="row g-3">
                {{-- INFORMASI ALAMAT TEMPAT TINGGAL --}}
                <div class="col-12">
                    <div class="card border rounded-4 shadow-none mb-0">

                        {{-- HEADER --}}
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
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

                        {{-- BODY --}}
                        <div class="card-body">
                            <div class="row g-3">

                                {{-- ALAMAT LENGKAP --}}
                                <div class="col-12">
                                    <label class="form-label" for="alamat-lengkap">
                                        Alamat Lengkap <span class="text-danger">*</span>
                                    </label>

                                    <textarea
                                        class="form-control @error('alamat_lengkap') is-invalid @enderror"
                                        id="alamat-lengkap"
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

                                {{-- PROVINSI --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="provinsi">
                                        Provinsi <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control @error('provinsi') is-invalid @enderror"
                                        id="provinsi"
                                        wire:model.defer="provinsi"
                                        placeholder="Contoh: Jawa Tengah">

                                    @error('provinsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KABUPATEN/KOTA --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="kabupaten">
                                        Kabupaten/Kota <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control @error('kabupaten') is-invalid @enderror"
                                        id="kabupaten"
                                        wire:model.defer="kabupaten"
                                        placeholder="Contoh: Kabupaten Sragen">

                                    @error('kabupaten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KECAMATAN --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="kecamatan">
                                        Kecamatan <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control @error('kecamatan') is-invalid @enderror"
                                        id="kecamatan"
                                        wire:model.defer="kecamatan"
                                        placeholder="Nama kecamatan">

                                    @error('kecamatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- DESA/KELURAHAN --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="kelurahan-desa">
                                        Desa/Kelurahan <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control @error('kelurahan_desa') is-invalid @enderror"
                                        id="kelurahan-desa"
                                        wire:model.defer="kelurahan_desa"
                                        placeholder="Nama desa/kelurahan">

                                    @error('kelurahan_desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- RT --}}
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="rt">RT</label>

                                    <input
                                        type="text"
                                        class="form-control @error('rt') is-invalid @enderror"
                                        id="rt"
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
                                    <label class="form-label" for="rw">RW</label>

                                    <input
                                        type="text"
                                        class="form-control @error('rw') is-invalid @enderror"
                                        id="rw"
                                        wire:model.defer="rw"
                                        maxlength="3"
                                        inputmode="numeric"
                                        placeholder="Contoh: 005">

                                    @error('rw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KODE POS --}}
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="kode-pos">Kode Pos</label>

                                    <input
                                        type="text"
                                        class="form-control @error('kode_pos') is-invalid @enderror"
                                        id="kode-pos"
                                        wire:model.defer="kode_pos"
                                        maxlength="5"
                                        inputmode="numeric"
                                        placeholder="5 digit kode pos">

                                    @error('kode_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- JENIS TEMPAT TINGGAL --}}
                                <div class="col-12">
                                    <label class="form-label" for="jenis-tempat-tinggal">
                                        Jenis Tempat Tinggal <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        class="form-select @error('jenis_tempat_tinggal') is-invalid @enderror"
                                        id="jenis-tempat-tinggal"
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
                </div>

                {{-- INFORMASI PERJALANAN KE SEKOLAH --}}
                <div class="col-12">
                    <div class="card border rounded-4 shadow-none mb-0">

                        {{-- HEADER --}}
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
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

                        {{-- BODY --}}
                        <div class="card-body">
                            <div class="row g-3">

                                {{-- JARAK KE SEKOLAH --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="jarak-ke-sekolah">
                                        Jarak ke Sekolah <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input
                                            type="number"
                                            class="form-control @error('jarak_ke_sekolah') is-invalid @enderror"
                                            id="jarak-ke-sekolah"
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

                                {{-- WAKTU TEMPUH --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="waktu-tempuh-ke-sekolah">
                                        Waktu Tempuh <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input
                                            type="number"
                                            class="form-control @error('waktu_tempuh_ke_sekolah') is-invalid @enderror"
                                            id="waktu-tempuh-ke-sekolah"
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

                                {{-- MODA TRANSPORTASI --}}
                                <div class="col-12">
                                    <label class="form-label" for="moda-transportasi">
                                        Moda Transportasi <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        class="form-select @error('moda_transportasi') is-invalid @enderror"
                                        id="moda-transportasi"
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
            {{-- ACTION --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('ppdb.pendaftaran.orang-tua', [
                        'jenjang' => $pendaftaran->ppdb_periode->ms_jenjang->slug,
                    ]) }}"
                    class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Kembali
                </a>

                <button
                    type="submit"
                    class="btn btn-primary rounded-pill px-4"
                    wire:loading.attr="disabled"
                    wire:target="simpan">

                    <span wire:loading.remove wire:target="simpan">
                        Simpan & Lanjutkan
                        <i class="ri-arrow-right-line ms-1"></i>
                    </span>

                    <span wire:loading wire:target="simpan">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>