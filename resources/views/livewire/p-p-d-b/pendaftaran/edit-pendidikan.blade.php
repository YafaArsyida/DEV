<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-school-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Edit Data Pendidikan</h5>
                <small class="text-muted">
                    Perbarui informasi pendidikan sebelumnya dengan benar.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        <form wire:submit.prevent="update">
            <div class="row g-3">

                {{-- JENJANG ASAL --}}
                <div class="col-12">
                    <label for="edit-jenjang-asal" class="form-label">
                        Jenjang Pendidikan Asal
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        id="edit-jenjang-asal"
                        class="form-select @error('jenjang_asal') is-invalid @enderror"
                        wire:model="jenjang_asal"
                    >
                        <option value="TK">TK</option>
                        <option value="RA">RA</option>
                        <option value="PAUD">PAUD</option>
                        <option value="TIDAK_ADA">
                            Belum Pernah Bersekolah
                        </option>
                    </select>

                    @error('jenjang_asal')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if ($jenjang_asal !== 'TIDAK_ADA')

                    {{-- ASAL SEKOLAH --}}
                    <div class="col-md-6">
                        <label for="edit-asal-sekolah" class="form-label">
                            Nama TK/RA/PAUD
                        </label>

                        <input
                            type="text"
                            id="edit-asal-sekolah"
                            class="form-control @error('asal_sekolah') is-invalid @enderror"
                            wire:model.defer="asal_sekolah"
                            maxlength="255"
                            placeholder="Masukkan nama sekolah"
                        >

                        @error('asal_sekolah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- NPSN --}}
                    <div class="col-md-6">
                        <label for="edit-npsn-asal-sekolah" class="form-label">
                            NPSN
                            <span class="text-muted fw-normal">(Opsional)</span>
                        </label>

                        <input
                            type="text"
                            id="edit-npsn-asal-sekolah"
                            class="form-control @error('npsn_asal_sekolah') is-invalid @enderror"
                            wire:model.defer="npsn_asal_sekolah"
                            maxlength="8"
                            inputmode="numeric"
                            placeholder="Masukkan 8 digit NPSN"
                        >

                        @error('npsn_asal_sekolah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ALAMAT TK --}}
                    <div class="col-md-12">
                        <label for="alamat-asal-sekolah" class="form-label">
                            Alamat Sekolah Asal
                            <span class="text-muted fw-normal">
                                (Opsional)
                            </span>
                        </label>

                        <textarea
                            id="alamat-asal-sekolah"
                            class="form-control @error('alamat_asal_sekolah') is-invalid @enderror"
                            wire:model.defer="alamat_asal_sekolah"
                            rows="3"
                            maxlength="1000"
                            placeholder="Masukkan alamat lengkap TK asal"
                        ></textarea>

                        @error('alamat_asal_sekolah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- TAHUN LULUS --}}
                    <div class="col-12 col-md-6">
                        <label for="edit-tahun-lulus" class="form-label">
                            Tahun Lulus
                            <span class="text-muted fw-normal">(Opsional)</span>
                        </label>

                        <select
                            id="edit-tahun-lulus"
                            class="form-select @error('tahun_lulus') is-invalid @enderror"
                            wire:model.defer="tahun_lulus"
                        >
                            <option value="">Pilih tahun lulus</option>

                            @for ($tahun = date('Y'); $tahun >= date('Y') - 3; $tahun--)
                                <option value="{{ $tahun }}">
                                    {{ $tahun }}
                                </option>
                            @endfor
                        </select>

                        @error('tahun_lulus')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- NOMOR PESERTA / BUKTI KELULUSAN --}}
                    <div class="col-12 col-md-6">
                        <label for="edit-nomor-ijazah" class="form-label">
                            Nomor Peserta / Bukti Kelulusan
                            <span class="text-muted fw-normal">(Opsional)</span>
                        </label>

                        <input
                            type="text"
                            id="edit-nomor-ijazah"
                            class="form-control @error('nomor_ijazah') is-invalid @enderror"
                            wire:model.defer="nomor_ijazah"
                            maxlength="100"
                            placeholder="Masukkan nomor jika tersedia"
                        >

                        @error('nomor_ijazah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="alert alert-light border rounded-3 mb-0">
                            <div class="d-flex gap-2">
                                <i class="ri-information-line fs-18 text-primary"></i>
                                <small class="text-muted">
                                    NPSN, tahun lulus, dan nomor bukti kelulusan
                                    boleh dikosongkan jika tidak tersedia.
                                </small>
                            </div>
                        </div>
                    </div>

                @else

                    <div class="col-12">
                        <div class="alert alert-light border rounded-3 mb-0">
                            <div class="d-flex gap-2">
                                <i class="ri-information-line fs-18 text-primary"></i>
                                <small class="text-muted">
                                    Pilihan ini akan mengosongkan data sekolah,
                                    NPSN, tahun lulus, dan nomor bukti kelulusan
                                    ketika perubahan disimpan.
                                </small>
                            </div>
                        </div>
                    </div>

                @endif

            </div>

            {{-- ACTIONS --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">

                <a href="{{ request()->url() }}" class="btn btn-light border rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary rounded-pill px-4"
                    wire:loading.attr="disabled"
                    wire:target="update"
                >
                    <span wire:loading.remove wire:target="update">
                        <i class="ri-save-line me-1"></i>
                        Simpan Perubahan
                    </span>

                    <span wire:loading wire:target="update">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Menyimpan...
                    </span>
                </button>

            </div>

        </form>

    </div>
</div>