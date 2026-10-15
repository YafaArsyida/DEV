<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-parent-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Ubah Data Orang Tua/Wali</h5>
                <small class="text-muted">
                    Periksa dan perbarui informasi orang tua atau wali calon siswa.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">

        {{-- DATA AYAH --}}
        <div class="card border rounded-4 shadow-none mb-4">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Data Ayah</h6>
                        <small class="text-muted">
                            Informasi ayah kandung calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- NAMA AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="ayah-nama"
                            class="form-control @error('ayah_nama') is-invalid @enderror"
                            wire:model.defer="ayah_nama"
                            maxlength="150"
                            placeholder="Masukkan nama lengkap">
                        @error('ayah_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIK AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-nik">NIK</label>
                        <input
                            type="text"
                            id="ayah-nik"
                            class="form-control @error('ayah_nik') is-invalid @enderror"
                            wire:model.defer="ayah_nik"
                            inputmode="numeric"
                            maxlength="16"
                            placeholder="16 digit NIK">
                        @error('ayah_nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TEMPAT LAHIR AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-tempat-lahir">
                            Tempat Lahir
                        </label>
                        <input
                            type="text"
                            id="ayah-tempat-lahir"
                            class="form-control @error('ayah_tempat_lahir') is-invalid @enderror"
                            wire:model.defer="ayah_tempat_lahir"
                            maxlength="100"
                            placeholder="Contoh: Surakarta">
                        @error('ayah_tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TANGGAL LAHIR AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-tanggal-lahir">
                            Tanggal Lahir
                        </label>
                        <input
                            type="date"
                            id="ayah-tanggal-lahir"
                            class="form-control @error('ayah_tanggal_lahir') is-invalid @enderror"
                            wire:model.defer="ayah_tanggal_lahir"
                            max="{{ date('Y-m-d') }}">
                        @error('ayah_tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KEBUTUHAN KHUSUS AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-berkebutuhan-khusus">
                            Berkebutuhan Khusus
                        </label>
                        <select
                            id="ayah-berkebutuhan-khusus"
                            class="form-select @error('ayah_berkebutuhan_khusus') is-invalid @enderror"
                            wire:model.defer="ayah_berkebutuhan_khusus">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                        @error('ayah_berkebutuhan_khusus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PENDIDIKAN AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-pendidikan">
                            Pendidikan Terakhir
                        </label>
                        <select
                            id="ayah-pendidikan"
                            class="form-select @error('ayah_pendidikan') is-invalid @enderror"
                            wire:model.defer="ayah_pendidikan">
                            <option value="">Pilih pendidikan</option>
                            <option value="TIDAK_SEKOLAH">Tidak Sekolah</option>
                            <option value="SD">SD/Sederajat</option>
                            <option value="SMP">SMP/Sederajat</option>
                            <option value="SMA">SMA/SMK/Sederajat</option>
                            <option value="D1_D2_D3">Diploma</option>
                            <option value="S1">S1/D4</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                        @error('ayah_pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PEKERJAAN AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-pekerjaan">Pekerjaan</label>
                        <input
                            type="text"
                            id="ayah-pekerjaan"
                            class="form-control @error('ayah_pekerjaan') is-invalid @enderror"
                            wire:model.defer="ayah_pekerjaan"
                            maxlength="100"
                            placeholder="Masukkan pekerjaan">
                        @error('ayah_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PENGHASILAN AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-penghasilan">
                            Penghasilan per Bulan
                        </label>
                        <select
                            id="ayah-penghasilan"
                            class="form-select @error('ayah_penghasilan') is-invalid @enderror"
                            wire:model.defer="ayah_penghasilan">
                            <option value="">Pilih rentang penghasilan</option>
                            <option value="TIDAK_ADA">Tidak Berpenghasilan</option>
                            <option value="KURANG_1JT">Kurang dari Rp1.000.000</option>
                            <option value="1JT_3JT">Rp1.000.000–Rp3.000.000</option>
                            <option value="3JT_5JT">Rp3.000.000–Rp5.000.000</option>
                            <option value="5JT_7JT">Rp5.000.000–Rp7.000.000</option>
                            <option value="LEBIH_7JT">Lebih dari Rp7.000.000</option>
                        </select>
                        @error('ayah_penghasilan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WHATSAPP AYAH --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ayah-whatsapp">
                            Nomor WhatsApp
                        </label>
                        <input
                            type="tel"
                            id="ayah-whatsapp"
                            class="form-control @error('ayah_whatsapp') is-invalid @enderror"
                            wire:model.defer="ayah_whatsapp"
                            inputmode="tel"
                            maxlength="20"
                            placeholder="Contoh: 081234567890">
                        @error('ayah_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- DATA IBU --}}
        <div class="card border rounded-4 shadow-none mb-4">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-20">
                            <i class="ri-user-heart-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Data Ibu</h6>
                        <small class="text-muted">
                            Informasi ibu kandung calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- NAMA IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="ibu-nama"
                            class="form-control @error('ibu_nama') is-invalid @enderror"
                            wire:model.defer="ibu_nama"
                            maxlength="150"
                            placeholder="Masukkan nama lengkap">
                        @error('ibu_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIK IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-nik">NIK</label>
                        <input
                            type="text"
                            id="ibu-nik"
                            class="form-control @error('ibu_nik') is-invalid @enderror"
                            wire:model.defer="ibu_nik"
                            inputmode="numeric"
                            maxlength="16"
                            placeholder="16 digit NIK">
                        @error('ibu_nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TEMPAT LAHIR IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-tempat-lahir">
                            Tempat Lahir
                        </label>
                        <input
                            type="text"
                            id="ibu-tempat-lahir"
                            class="form-control @error('ibu_tempat_lahir') is-invalid @enderror"
                            wire:model.defer="ibu_tempat_lahir"
                            maxlength="100"
                            placeholder="Contoh: Surakarta">
                        @error('ibu_tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TANGGAL LAHIR IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-tanggal-lahir">
                            Tanggal Lahir
                        </label>
                        <input
                            type="date"
                            id="ibu-tanggal-lahir"
                            class="form-control @error('ibu_tanggal_lahir') is-invalid @enderror"
                            wire:model.defer="ibu_tanggal_lahir"
                            max="{{ date('Y-m-d') }}">
                        @error('ibu_tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KEBUTUHAN KHUSUS IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-berkebutuhan-khusus">
                            Berkebutuhan Khusus
                        </label>
                        <select
                            id="ibu-berkebutuhan-khusus"
                            class="form-select @error('ibu_berkebutuhan_khusus') is-invalid @enderror"
                            wire:model.defer="ibu_berkebutuhan_khusus">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                        @error('ibu_berkebutuhan_khusus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PENDIDIKAN IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-pendidikan">
                            Pendidikan Terakhir
                        </label>
                        <select
                            id="ibu-pendidikan"
                            class="form-select @error('ibu_pendidikan') is-invalid @enderror"
                            wire:model.defer="ibu_pendidikan">
                            <option value="">Pilih pendidikan</option>
                            <option value="TIDAK_SEKOLAH">Tidak Sekolah</option>
                            <option value="SD">SD/Sederajat</option>
                            <option value="SMP">SMP/Sederajat</option>
                            <option value="SMA">SMA/SMK/Sederajat</option>
                            <option value="D1_D2_D3">Diploma</option>
                            <option value="S1">S1/D4</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                        @error('ibu_pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PEKERJAAN IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-pekerjaan">Pekerjaan</label>
                        <input
                            type="text"
                            id="ibu-pekerjaan"
                            class="form-control @error('ibu_pekerjaan') is-invalid @enderror"
                            wire:model.defer="ibu_pekerjaan"
                            maxlength="100"
                            placeholder="Masukkan pekerjaan">
                        @error('ibu_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PENGHASILAN IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-penghasilan">
                            Penghasilan per Bulan
                        </label>
                        <select
                            id="ibu-penghasilan"
                            class="form-select @error('ibu_penghasilan') is-invalid @enderror"
                            wire:model.defer="ibu_penghasilan">
                            <option value="">Pilih rentang penghasilan</option>
                            <option value="TIDAK_ADA">Tidak Berpenghasilan</option>
                            <option value="KURANG_1JT">Kurang dari Rp1.000.000</option>
                            <option value="1JT_3JT">Rp1.000.000–Rp3.000.000</option>
                            <option value="3JT_5JT">Rp3.000.000–Rp5.000.000</option>
                            <option value="5JT_7JT">Rp5.000.000–Rp7.000.000</option>
                            <option value="LEBIH_7JT">Lebih dari Rp7.000.000</option>
                        </select>
                        @error('ibu_penghasilan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WHATSAPP IBU --}}
                    <div class="col-md-6">
                        <label class="form-label" for="ibu-whatsapp">
                            Nomor WhatsApp
                        </label>
                        <input
                            type="tel"
                            id="ibu-whatsapp"
                            class="form-control @error('ibu_whatsapp') is-invalid @enderror"
                            wire:model.defer="ibu_whatsapp"
                            inputmode="tel"
                            maxlength="20"
                            placeholder="Contoh: 081234567890">
                        @error('ibu_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- DATA WALI --}}
        <div class="card border rounded-4 shadow-none mb-4">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-settings-line"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Data Wali</h6>
                        <small class="text-muted">
                            Informasi wali yang bertanggung jawab atas calon siswa.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">

                {{-- TOGGLE WALI --}}
                <div class="form-check form-switch mb-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="gunakan-wali"
                        wire:model="gunakan_wali">

                    <label class="form-check-label fw-semibold" for="gunakan-wali">
                        Calon siswa menggunakan wali
                    </label>
                </div>

                @if ($gunakan_wali)
                    <div class="row g-3">

                        {{-- HUBUNGAN WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-hubungan">
                                Hubungan dengan Calon Siswa
                            </label>
                            <select
                                id="wali-hubungan"
                                class="form-select @error('wali_hubungan') is-invalid @enderror"
                                wire:model.defer="wali_hubungan">
                                <option value="">Pilih hubungan</option>
                                <option value="KAKEK">Kakek</option>
                                <option value="NENEK">Nenek</option>
                                <option value="PAMAN">Paman</option>
                                <option value="BIBI">Bibi</option>
                                <option value="SAUDARA">Saudara</option>
                                <option value="LAINNYA">Lainnya</option>
                            </select>
                            @error('wali_hubungan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NAMA WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-nama">
                                Nama Lengkap Wali
                            </label>
                            <input
                                type="text"
                                id="wali-nama"
                                class="form-control @error('wali_nama') is-invalid @enderror"
                                wire:model.defer="wali_nama"
                                maxlength="150"
                                placeholder="Masukkan nama lengkap">
                            @error('wali_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NIK WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-nik">NIK Wali</label>
                            <input
                                type="text"
                                id="wali-nik"
                                class="form-control @error('wali_nik') is-invalid @enderror"
                                wire:model.defer="wali_nik"
                                inputmode="numeric"
                                maxlength="16"
                                placeholder="16 digit NIK">
                            @error('wali_nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TEMPAT LAHIR WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-tempat-lahir">
                                Tempat Lahir
                            </label>
                            <input
                                type="text"
                                id="wali-tempat-lahir"
                                class="form-control @error('wali_tempat_lahir') is-invalid @enderror"
                                wire:model.defer="wali_tempat_lahir"
                                maxlength="100"
                                placeholder="Contoh: Surakarta">
                            @error('wali_tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TANGGAL LAHIR WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-tanggal-lahir">
                                Tanggal Lahir
                            </label>
                            <input
                                type="date"
                                id="wali-tanggal-lahir"
                                class="form-control @error('wali_tanggal_lahir') is-invalid @enderror"
                                wire:model.defer="wali_tanggal_lahir"
                                max="{{ date('Y-m-d') }}">
                            @error('wali_tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- KEBUTUHAN KHUSUS WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-berkebutuhan-khusus">
                                Berkebutuhan Khusus
                            </label>
                            <select
                                id="wali-berkebutuhan-khusus"
                                class="form-select @error('wali_berkebutuhan_khusus') is-invalid @enderror"
                                wire:model.defer="wali_berkebutuhan_khusus">
                                <option value="Tidak">Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>
                            @error('wali_berkebutuhan_khusus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PEKERJAAN WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-pekerjaan">Pekerjaan</label>
                            <input
                                type="text"
                                id="wali-pekerjaan"
                                class="form-control @error('wali_pekerjaan') is-invalid @enderror"
                                wire:model.defer="wali_pekerjaan"
                                maxlength="100"
                                placeholder="Masukkan pekerjaan">
                            @error('wali_pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- WHATSAPP WALI --}}
                        <div class="col-md-6">
                            <label class="form-label" for="wali-whatsapp">
                                Nomor WhatsApp
                            </label>
                            <input
                                type="tel"
                                id="wali-whatsapp"
                                class="form-control @error('wali_whatsapp') is-invalid @enderror"
                                wire:model.defer="wali_whatsapp"
                                inputmode="tel"
                                maxlength="20"
                                placeholder="Contoh: 081234567890">
                            @error('wali_whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                @else
                    <div class="alert alert-light border rounded-3 mb-0">
                        <div class="d-flex gap-2">
                            <i class="ri-information-line text-muted fs-18"></i>
                            <small class="text-muted">
                                Data wali tidak akan disertakan dalam data yang disimpan.
                                Jika perubahan disimpan dalam kondisi ini, data wali yang
                                sebelumnya tersimpan akan dikosongkan.
                            </small>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- ACTION --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 pt-3 border-top">

            <a
                href="{{ route('ppdb.pendaftaran.orang-tua') }}"
                class="btn btn-light border rounded-pill px-4">
                <i class="ri-close-line me-1"></i>
                Batal
            </a>

            <button
                type="button"
                class="btn btn-primary rounded-pill px-4"
                wire:click="update"
                wire:loading.attr="disabled"
                wire:target="update">

                <span wire:loading.remove wire:target="update">
                    <i class="ri-save-line me-1"></i>
                    Simpan Perubahan
                </span>

                <span wire:loading wire:target="update">
                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                        aria-hidden="true"></span>
                    Menyimpan...
                </span>
            </button>

        </div>

    </div>
</div>