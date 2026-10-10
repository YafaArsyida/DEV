{{-- Login Card --}}
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
            <h4 class="fw-bold mb-2">
                Selamat Datang Kembali
            </h4>

            <p class="text-muted mb-0">
                Masuk ke akun orang tua/wali untuk melanjutkan
                proses pendaftaran peserta didik baru.
            </p>
        </div>

        {{-- Login Error --}}
        <div id="login-error"
            class="alert alert-danger border-0 rounded-3 d-none"
            role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="ri-error-warning-line fs-4"></i>
                <div>
                    <h6 class="alert-heading mb-1">
                        Login Belum Berhasil
                    </h6>
                    <p class="mb-0" id="login-error-message">
                        Periksa kembali email dan password Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- Login Success --}}
        <div id="login-success"
            class="alert alert-success border-0 rounded-3 d-none"
            role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="ri-checkbox-circle-line fs-4"></i>
                <div>
                    <h6 class="alert-heading mb-1">
                        Login Berhasil!
                    </h6>
                    <p class="mb-1">
                        Anda berhasil masuk ke akun PPDB.
                    </p>
                    <small>
                        Anda akan dialihkan ke dashboard orang tua
                        dalam 3 detik...
                    </small>
                </div>
            </div>
        </div>

        {{-- Login Form --}}
        <form id="loginForm" wire:submit.prevent="login">

            {{-- Email --}}
            <div class="mb-3">
                <label for="loginEmail" class="form-label">
                    Email
                </label>

                <input type="text"
                    class="form-control @error('email') is-invalid @enderror"
                    id="loginEmail"
                    wire:model.defer="email"
                    placeholder="nama@email.com"
                    autocomplete="username"
                    autofocus>

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password-input" class="form-label">
                    Password
                </label>

                <div class="position-relative">
                    <input
                        type="password"
                        id="password-input"
                        wire:model.defer="password"
                        class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        autocomplete="current-password">

                    <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon"
                        type="button"
                        id="password-addon"
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
            {{-- Submit --}}
            <div class="d-grid">
                <button id="loginSubmit"
                    type="submit"
                    class="btn btn-primary"
                    wire:loading.attr="disabled"
                    wire:target="login">

                    <span wire:loading.remove wire:target="login">
                        <i class="ri-login-box-line align-bottom me-1"></i>
                        Masuk ke Akun
                    </span>

                    <span wire:loading wire:target="login">
                        <span class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"></span>

                        Memeriksa akun...
                    </span>
                </button>
            </div>

            {{-- Register Link --}}
            <div class="text-center mt-4">
                <p class="text-muted mb-0">
                    Belum memiliki akun?

                    <a href="{{ route('ppdb.daftar') }}"
                        class="fw-semibold text-primary text-decoration-underline">
                        Daftar sekarang
                    </a>
                </p>
            </div>

        </form>
    </div>

    @once
        <script>
            window.addEventListener('login-error', function (event) {
                const errorBox = document.getElementById('login-error');
                const successBox = document.getElementById('login-success');
                const errorMessage = document.getElementById('login-error-message');
                const submitButton = document.getElementById('loginSubmit');

                // Sembunyikan pesan sukses sebelumnya.
                if (successBox) {
                    successBox.classList.add('d-none');
                }

                // Tampilkan pesan kesalahan.
                if (errorBox) {
                    if (errorMessage) {
                        errorMessage.textContent =
                            event.detail.message ||
                            'Periksa kembali email dan password Anda.';
                    }

                    errorBox.classList.remove('d-none');

                    errorBox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                if (submitButton) {
                    submitButton.disabled = false;
                }
            });

            window.addEventListener('login-success', function () {
                const errorBox = document.getElementById('login-error');
                const successBox = document.getElementById('login-success');
                const submitButton = document.getElementById('loginSubmit');

                // Sembunyikan pesan kesalahan.
                if (errorBox) {
                    errorBox.classList.add('d-none');
                }

                // Tampilkan pesan berhasil.
                if (successBox) {
                    successBox.classList.remove('d-none');

                    successBox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                // Cegah pengiriman form berulang.
                if (submitButton) {
                    submitButton.disabled = true;
                }

                // Redirect setelah 3 detik.
                setTimeout(function () {
                    window.location.href = @json(route('ppdb.dashboard'));
                }, 3000);
            });
        </script>
    @endonce

</div>