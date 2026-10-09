<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun PPDB | {{ config('app.name') }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            background: linear-gradient(135deg, #f4f8f6 0%, #eef4ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
            font-family: 'Segoe UI', sans-serif;
        }
        .ppdb-auth-card {
            width: min(100%, 520px);
            background: rgba(255,255,255,0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 28px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.10);
            overflow: hidden;
        }
        .ppdb-auth-header {
            padding: 28px 28px 18px;
            background: linear-gradient(180deg, rgba(10, 92, 102, 0.06), rgba(255,255,255,0));
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }
        .ppdb-auth-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .ppdb-auth-header img {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            object-fit: cover;
        }
        .ppdb-auth-header small {
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .ppdb-auth-header h3 {
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.04em;
        }
        .ppdb-auth-body {
            padding: 28px;
        }
        .form-label {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: none;
            background: #fff;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: rgba(12, 91, 94, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(12, 91, 94, 0.12);
        }
        .ppdb-btn {
            min-height: 48px;
            border-radius: 14px;
            font-weight: 600;
        }
        .ppdb-hint {
            font-size: 0.83rem;
            color: #6b7280;
            margin-top: 0.35rem;
        }
        .form-check-input:checked {
            background-color: var(--vz-primary, #0c5b5e);
            border-color: var(--vz-primary, #0c5b5e);
        }
        @media (max-width: 575.98px) {
            body {
                padding: 18px;
            }
            .ppdb-auth-header,
            .ppdb-auth-body {
                padding-left: 18px;
                padding-right: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="ppdb-auth-card">
        <div class="ppdb-auth-header">
            <div class="brand">
                <img src="{{ asset('assets/logo/logo.jpg') }}" alt="Logo sekolah">
                <div>
                    <div class="text-muted fw-semibold">PPDB SD Islam</div>
                    <h3>{{ config('app.name') }}</h3>
                </div>
            </div>
            <small class="text-primary fw-bold d-block">Portal Pendaftaran</small>
        </div>

        <div class="ppdb-auth-body">
            <div class="mb-4">
                <h4 class="fw-bold mb-1">Buat Akun</h4>
                <p class="text-muted mb-0">Mulai proses pendaftaran putra-putri Anda dengan data yang benar.</p>
            </div>

            <form>
                <div class="mb-3">
                    <label class="form-label" for="namaOrangTua">Nama Orang Tua/Wali</label>
                    <input type="text" class="form-control" id="namaOrangTua" value="Budi Santoso" placeholder="Masukkan nama orang tua/wali">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="budi.santoso@gmail.com" placeholder="nama@email.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="whatsapp">Nomor WhatsApp</label>
                        <input type="tel" class="form-control" id="whatsapp" value="081234567890" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" value="password123" placeholder="Minimal 8 karakter">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="confirmPassword">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirmPassword" value="password123" placeholder="Ulangi password">
                    </div>
                </div>

                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" id="agreement" checked>
                    <label class="form-check-label text-muted" for="agreement">
                        Saya setuju dengan syarat dan ketentuan PPDB.
                    </label>
                </div>

                <p class="ppdb-hint mt-3">
                    Gunakan nomor WhatsApp yang aktif karena akan digunakan untuk informasi pendaftaran.
                </p>

                <a href="{{ route('ppdb.dashboard') }}" class="btn btn-primary ppdb-btn w-100 mt-3">Buat Akun</a>

                <div class="text-center mt-4 mb-0 text-muted">
                    Sudah memiliki akun? <a href="{{ route('ppdb.login') }}" class="fw-semibold text-primary text-decoration-none">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
