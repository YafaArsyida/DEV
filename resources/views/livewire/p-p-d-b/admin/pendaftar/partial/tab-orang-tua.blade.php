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
                <h5 class="fw-bold mb-1">
                    Data Orang Tua/Wali
                </h5>
                <small class="text-muted">
                    Lengkapi informasi orang tua atau wali calon siswa.
                    Informasi domisili calon siswa saat ini.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">

        {{-- ===================================================== --}}
        {{-- DATA AYAH --}}
        {{-- ===================================================== --}}
        <div class="card border shadow-none rounded-4 mb-4">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
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

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-nama">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="bantu-ayah-nama"
                            class="form-control @error('ayah_nama') is-invalid @enderror"
                            wire:model.defer="ayah_nama"
                            placeholder="Masukkan nama lengkap">
                        @error('ayah_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-nik">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="bantu-ayah-nik"
                            class="form-control @error('ayah_nik') is-invalid @enderror"
                            wire:model.defer="ayah_nik"
                            inputmode="numeric" maxlength="16"
                            placeholder="16 digit NIK">
                        @error('ayah_nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-tempat-lahir">Tempat Lahir</label>
                        <input type="text" id="bantu-ayah-tempat-lahir"
                            class="form-control @error('ayah_tempat_lahir') is-invalid @enderror"
                            wire:model.defer="ayah_tempat_lahir"
                            maxlength="100" placeholder="Contoh: Surakarta">
                        @error('ayah_tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-tanggal-lahir">Tanggal Lahir</label>
                        <input type="date" id="bantu-ayah-tanggal-lahir"
                            class="form-control @error('ayah_tanggal_lahir') is-invalid @enderror"
                            wire:model.defer="ayah_tanggal_lahir"
                            max="{{ date('Y-m-d') }}">
                        @error('ayah_tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Berkebutuhan Khusus --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-kebutuhan-khusus">Berkebutuhan Khusus</label>
                        <select id="bantu-ayah-kebutuhan-khusus"
                            class="form-select @error('ayah_berkebutuhan_khusus') is-invalid @enderror"
                            wire:model.defer="ayah_berkebutuhan_khusus">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                        @error('ayah_berkebutuhan_khusus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pendidikan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-pendidikan">Pendidikan Terakhir</label>
                        <select id="bantu-ayah-pendidikan"
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

                    {{-- Pekerjaan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-pekerjaan">Pekerjaan</label>
                        <input type="text" id="bantu-ayah-pekerjaan"
                            class="form-control @error('ayah_pekerjaan') is-invalid @enderror"
                            wire:model.defer="ayah_pekerjaan"
                            placeholder="Masukkan pekerjaan">
                        @error('ayah_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Penghasilan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-penghasilan">Penghasilan per Bulan</label>
                        <select id="bantu-ayah-penghasilan"
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

                    {{-- WhatsApp --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ayah-whatsapp">
                            Nomor WhatsApp <span class="text-danger">*</span>
                        </label>
                        <input type="tel" id="bantu-ayah-whatsapp"
                            class="form-control @error('ayah_whatsapp') is-invalid @enderror"
                            wire:model.defer="ayah_whatsapp"
                            inputmode="tel" placeholder="Contoh: 081234567890">
                        @error('ayah_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- DATA IBU --}}
        {{-- ===================================================== --}}
        <div class="card border shadow-none rounded-4 mb-4">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
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

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-nama">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="bantu-ibu-nama"
                            class="form-control @error('ibu_nama') is-invalid @enderror"
                            wire:model.defer="ibu_nama"
                            placeholder="Masukkan nama lengkap">
                        @error('ibu_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-nik">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="bantu-ibu-nik"
                            class="form-control @error('ibu_nik') is-invalid @enderror"
                            wire:model.defer="ibu_nik"
                            inputmode="numeric" maxlength="16"
                            placeholder="16 digit NIK">
                        @error('ibu_nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-tempat-lahir">Tempat Lahir</label>
                        <input type="text" id="bantu-ibu-tempat-lahir"
                            class="form-control @error('ibu_tempat_lahir') is-invalid @enderror"
                            wire:model.defer="ibu_tempat_lahir"
                            maxlength="100" placeholder="Contoh: Surakarta">
                        @error('ibu_tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-tanggal-lahir">Tanggal Lahir</label>
                        <input type="date" id="bantu-ibu-tanggal-lahir"
                            class="form-control @error('ibu_tanggal_lahir') is-invalid @enderror"
                            wire:model.defer="ibu_tanggal_lahir"
                            max="{{ date('Y-m-d') }}">
                        @error('ibu_tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Berkebutuhan Khusus --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-kebutuhan-khusus">Berkebutuhan Khusus</label>
                        <select id="bantu-ibu-kebutuhan-khusus"
                            class="form-select @error('ibu_berkebutuhan_khusus') is-invalid @enderror"
                            wire:model.defer="ibu_berkebutuhan_khusus">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya</option>
                        </select>
                        @error('ibu_berkebutuhan_khusus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pendidikan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-pendidikan">Pendidikan Terakhir</label>
                        <select id="bantu-ibu-pendidikan"
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

                    {{-- Pekerjaan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-pekerjaan">Pekerjaan</label>
                        <input type="text" id="bantu-ibu-pekerjaan"
                            class="form-control @error('ibu_pekerjaan') is-invalid @enderror"
                            wire:model.defer="ibu_pekerjaan"
                            placeholder="Masukkan pekerjaan">
                        @error('ibu_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Penghasilan --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-penghasilan">Penghasilan per Bulan</label>
                        <select id="bantu-ibu-penghasilan"
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

                    {{-- WhatsApp --}}
                    <div class="col-md-6">
                        <label class="form-label" for="bantu-ibu-whatsapp">
                            Nomor WhatsApp <span class="text-danger">*</span>
                        </label>
                        <input type="tel" id="bantu-ibu-whatsapp"
                            class="form-control @error('ibu_whatsapp') is-invalid @enderror"
                            wire:model.defer="ibu_whatsapp"
                            inputmode="tel" placeholder="Contoh: 081234567890">
                        @error('ibu_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- DATA WALI --}}
        {{-- ===================================================== --}}
        <div class="card border border-primary-subtle rounded-4 shadow-none mb-0">

            <div class="card-header bg-primary-subtle bg-opacity-25">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary text-white rounded-circle fs-20">
                            <i class="ri-user-settings-line"></i>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-semibold mb-1">Data Wali</h6>
                        <small class="text-muted">
                            Opsional. Isi jika calon siswa memiliki wali yang perlu dicantumkan.
                            Jika tidak ada, bagian ini dapat dikosongkan.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input"
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
                        {{-- Hubungan --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-hubungan">
                                Hubungan dengan Calon Siswa <span class="text-danger">*</span>
                            </label>

                            <select
                                id="bantu-wali-hubungan"
                                class="form-select @error('wali_hubungan') is-invalid @enderror"
                                wire:model.defer="wali_hubungan"
                            >
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

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-nama">
                                Nama Lengkap Wali <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="bantu-wali-nama"
                                class="form-control @error('wali_nama') is-invalid @enderror"
                                wire:model.defer="wali_nama"
                                maxlength="150"
                                placeholder="Masukkan nama lengkap"
                            >

                            @error('wali_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NIK --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-nik">
                                NIK Wali <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="bantu-wali-nik"
                                class="form-control @error('wali_nik') is-invalid @enderror"
                                wire:model.defer="wali_nik"
                                inputmode="numeric"
                                maxlength="16"
                                placeholder="16 digit NIK"
                            >

                            @error('wali_nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-tempat-lahir">
                                Tempat Lahir
                            </label>

                            <input
                                type="text"
                                id="bantu-wali-tempat-lahir"
                                class="form-control @error('wali_tempat_lahir') is-invalid @enderror"
                                wire:model.defer="wali_tempat_lahir"
                                maxlength="100"
                                placeholder="Contoh: Surakarta"
                            >

                            @error('wali_tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-tanggal-lahir">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                id="bantu-wali-tanggal-lahir"
                                class="form-control @error('wali_tanggal_lahir') is-invalid @enderror"
                                wire:model.defer="wali_tanggal_lahir"
                                max="{{ date('Y-m-d') }}"
                            >

                            @error('wali_tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Berkebutuhan Khusus --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-kebutuhan-khusus">
                                Berkebutuhan Khusus
                            </label>

                            <select
                                id="bantu-wali-kebutuhan-khusus"
                                class="form-select @error('wali_berkebutuhan_khusus') is-invalid @enderror"
                                wire:model.defer="wali_berkebutuhan_khusus"
                            >
                                <option value="">Pilih status</option>
                                <option value="Tidak">Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>

                            @error('wali_berkebutuhan_khusus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pekerjaan --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-pekerjaan">
                                Pekerjaan
                            </label>

                            <input
                                type="text"
                                id="bantu-wali-pekerjaan"
                                class="form-control @error('wali_pekerjaan') is-invalid @enderror"
                                wire:model.defer="wali_pekerjaan"
                                maxlength="100"
                                placeholder="Masukkan pekerjaan"
                            >

                            @error('wali_pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- WhatsApp --}}
                        <div class="col-md-6">
                            <label class="form-label" for="bantu-wali-whatsapp">
                                Nomor WhatsApp <span class="text-danger">*</span>
                            </label>

                            <input
                                type="tel"
                                id="bantu-wali-whatsapp"
                                class="form-control @error('wali_whatsapp') is-invalid @enderror"
                                wire:model.defer="wali_whatsapp"
                                inputmode="tel"
                                maxlength="20"
                                placeholder="Contoh: 081234567890"
                            >

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
                                Jika data ayah dan ibu sudah mewakili penanggung
                                jawab calon siswa, bagian wali dapat dibiarkan
                                kosong. Aktifkan pilihan penggunaan wali jika
                                calon siswa memiliki wali tambahan.
                            </small>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>