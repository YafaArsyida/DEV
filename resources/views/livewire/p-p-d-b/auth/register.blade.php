
<div class="card shadow-lg border-0 rounded-4 overflow-hidden">
    <div class="card-body p-4 p-sm-5">

        {{-- Brand --}}
        <div class="text-center mb-4">
            <a href="{{ route('ppdb.landing') }}"
                class="d-inline-flex align-items-center gap-3 text-decoration-none">

                <img src="{{ asset('assets/logo/logo.jpg') }}"
                    alt="Logo sekolah"
                    class="rounded-3"
                    width="48"
                    height="48">

                <div class="text-start">
                    <h5 class="mb-1 fw-bold text-body">
                        {{ config('app.name') }}
                    </h5>
                    <p class="text-muted mb-0">
                        Portal Pendaftaran PPDB
                    </p>
                </div>
            </a>
        </div>

        <hr class="border-light-subtle mb-4">

        {{-- Heading --}}
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-2">Buat Akun Pendaftaran</h4>
            <p class="text-muted mb-0">
                Daftarkan akun orang tua/wali untuk memulai
                proses penerimaan peserta didik baru.
            </p>
        </div>

        <div
            id="registration-success"
            class="alert alert-success border-0 rounded-3 d-none"
            role="alert"
        >
            <div class="d-flex align-items-start gap-2">
                <i class="ri-checkbox-circle-line fs-4"></i>

                <div>
                    <h6 class="alert-heading mb-1">Registrasi Berhasil!</h6>
                    <p class="mb-0">
                        Berhasil, halaman dialihkan ke dashboard orang tua.
                    </p>
                    <small>Anda akan dialihkan dalam 3 detik...</small>
                </div>
            </div>
        </div>

        @once
            <script>
                window.addEventListener('registration-success', function () {
                    const alertBox = document.getElementById(
                        'registration-success'
                    );

                    const submitButton = document.getElementById(
                        'registrationSubmit'
                    );

                    if (alertBox) {
                        alertBox.classList.remove('d-none');

                        alertBox.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    if (submitButton) {
                        submitButton.disabled = true;
                    }

                    setTimeout(function () {
                        window.location.href = @json(route('ppdb.dashboard'));
                    }, 3000);
                });
            </script>
        @endonce

        <form id="registrationForm" wire:submit.prevent="register">

            {{-- Nama Orang Tua --}}
            <div class="mb-3">
                <label for="namaOrangTua" class="form-label">
                    Nama Orang Tua/Wali
                </label>

                <input type="text"
                    class="form-control @error('nama') is-invalid @enderror"
                    id="namaOrangTua"
                    wire:model.defer="nama"
                    placeholder="Masukkan nama orang tua/wali"
                    autocomplete="name">

                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email dan WhatsApp --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email/Username</label>

                    <input type="text"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        wire:model.defer="email"
                        placeholder="nama@email.com"
                        autocomplete="email">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>

                        @if ($message === 'Email ini sudah terdaftar. Silakan login menggunakan akun yang sudah ada.')
                            <div class="mt-2 small">
                                <a href="{{ route('ppdb.login') }}"
                                    class="fw-semibold text-primary">
                                    Login ke akun yang sudah ada
                                    <i class="ri-arrow-right-line align-middle"></i>
                                </a>
                            </div>
                        @endif
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="whatsapp" class="form-label">
                        Nomor WhatsApp
                    </label>

                    <input type="tel"
                        class="form-control @error('telepon') is-invalid @enderror"
                        id="whatsapp"
                        wire:model.defer="telepon"
                        placeholder="08xxxxxxxxxx"
                        autocomplete="tel">

                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="row g-3 mt-1">
                {{-- Password --}}
                <div class="col-md-6">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="position-relative">
                        <input
                            type="password"
                            class="form-control pe-5 @error('password') is-invalid @enderror"
                            id="password"
                            wire:model.defer="password"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password">

                        <button
                            type="button"
                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon"
                            data-password-target="password"
                            aria-label="Tampilkan password"
                            aria-pressed="false">

                            <i class="ri-eye-fill align-middle"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="col-md-6">
                    <label for="confirmPassword" class="form-label">
                        Konfirmasi Password
                    </label>

                    <div class="position-relative">
                        <input
                            type="password"
                            class="form-control pe-5 @error('password_confirmation') is-invalid @enderror"
                            id="confirmPassword"
                            wire:model.defer="password_confirmation"
                            placeholder="Ulangi password"
                            autocomplete="new-password">

                        <button
                            type="button"
                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon"
                            data-password-target="confirmPassword"
                            aria-label="Tampilkan konfirmasi password"
                            aria-pressed="false">

                            <i class="ri-eye-fill align-middle"></i>
                        </button>
                    </div>

                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Agreement --}}
            <div class="mt-4">
                <div class="form-check">
                    <input class="form-check-input @error('agreement') is-invalid @enderror"
                        type="checkbox"
                        id="agreement"
                        wire:model.defer="agreement">

                    <label class="form-check-label text-muted"
                        for="agreement">
                        Saya menyetujui syarat dan ketentuan
                        pendaftaran PPDB.
                    </label>

                    @error('agreement')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-4">
                <button
                    id="registrationSubmit"
                    type="submit"
                    class="btn btn-primary w-100"
                    wire:loading.attr="disabled"
                    wire:target="register"
                >
                    <span wire:loading.remove wire:target="register">
                        <i class="ri-user-add-line align-bottom me-1"></i>
                        Buat Akun
                    </span>

                    <span wire:loading wire:target="register">
                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Memproses registrasi...
                    </span>
                </button>
            </div>

            {{-- Login Link --}}
            <div class="text-center mt-4">
                <p class="text-muted mb-0">
                    Sudah memiliki akun?

                    <a href="{{ route('ppdb.login') }}"
                        class="fw-semibold text-primary text-decoration-underline">
                        Masuk di sini
                    </a>
                </p>
            </div>

        </form>
    </div>
</div>
