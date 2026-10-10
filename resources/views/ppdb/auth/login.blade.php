<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Masuk PPDB | {{ config('app.name') }}</title>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    @livewireStyles
</head>

<body>
    <div id="auth-page-wrapper"
        class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">

        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7 col-xxl-6">
                        {{-- Login Card --}}
                        @livewire('p-p-d-b.auth.login')    
                        
                        {{-- Footer --}}
                        <div class="text-center mt-4 mb-4">
                            <p class="text-muted mb-0">
                                <i class="ri-shield-check-line me-1"></i>
                                Portal resmi pendaftaran peserta didik baru.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password-input');
            const passwordAddon = document.getElementById('password-addon');

            if (!passwordInput || !passwordAddon) {
                return;
            }

            passwordAddon.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';

                passwordAddon.setAttribute('aria-pressed', isPassword);
                passwordAddon.setAttribute(
                    'aria-label',
                    isPassword ? 'Sembunyikan password' : 'Tampilkan password'
                );

                const icon = passwordAddon.querySelector('i');

                if (icon) {
                    icon.classList.toggle('ri-eye-fill', !isPassword);
                    icon.classList.toggle('ri-eye-off-fill', isPassword);
                }
            });
        });
    </script>
</body>

</html>