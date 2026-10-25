
<div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-user-settings-line"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1">Edit Profil Akun</h5>
                    <small class="text-muted">
                        Perbarui informasi kontak dan keamanan akun Anda.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="simpan">
                {{-- INFORMASI AKUN --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="avatar-xs">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-0">Informasi Akun</h6>
                </div>

                <div class="row g-3">
                    {{-- Nama --}}
                    <div class="col-12">
                        <label for="nama" class="form-label fw-medium">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama"
                            class="form-control rounded-pill @error('nama') is-invalid @enderror"
                            wire:model.defer="nama"
                            placeholder="Masukkan nama lengkap"
                            autocomplete="name"
                        >

                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12">
                        <label for="email" class="form-label fw-medium">
                            Email/Username <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="email"
                            class="form-control rounded-pill @error('email') is-invalid @enderror"
                            wire:model.defer="email"
                            placeholder="nama@email.com"
                            autocomplete="email"
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted d-block mt-2">
                            <i class="ri-information-line me-1"></i>
                            Gunakan email aktif yang dapat Anda akses.
                        </small>
                    </div>

                    {{-- Telepon --}}
                    <div class="col-12">
                        <label for="telepon" class="form-label fw-medium">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            id="telepon"
                            class="form-control rounded-pill @error('telepon') is-invalid @enderror"
                            wire:model.defer="telepon"
                            placeholder="Contoh: 081234567890"
                            autocomplete="tel"
                        >

                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- PEMISAH --}}
                <div class="border-top border-dashed my-4"></div>

                {{-- KEAMANAN AKUN --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="avatar-xs">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="ri-lock-password-line"></i>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-bold mb-1">Keamanan Akun</h6>
                        <small class="text-muted">
                            Ganti password jika diperlukan.
                        </small>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Password Baru --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-medium">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password"
                            class="form-control rounded-pill @error('password') is-invalid @enderror"
                            wire:model.defer="password"
                            placeholder="Minimal 6 karakter"
                            autocomplete="new-password"
                        >

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            class="form-control rounded-pill"
                            wire:model.defer="password_confirmation"
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="col-12">
                        <div class="alert alert-light border rounded-3 mb-0">
                            <i class="ri-information-line text-primary me-1"></i>
                            <small class="text-muted">
                                Kosongkan kedua kolom password jika tidak ingin menggantinya.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                        wire:loading.attr="disabled"
                        wire:target="simpan"
                    >
                        <span wire:loading.remove wire:target="simpan">
                            <i class="ri-save-line me-1"></i>
                            Simpan Perubahan
                        </span>

                        <span wire:loading wire:target="simpan">
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true">
                            </span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
