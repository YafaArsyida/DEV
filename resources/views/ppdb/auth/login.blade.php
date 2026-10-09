<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk PPDB | {{ config('app.name') }}</title>
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
            width: min(100%, 480px);
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
            width: 50px;
            height: 50px;
            border-radius: 14px;
            object-fit: cover;
        }
        .ppdb-auth-header h3 {
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.04em;
        }
        .ppdb-auth-header p {
            margin: 0;
            color: #6b7280;
        }
        .ppdb-auth-body {
            padding: 28px;
        }
        .form-label {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .form-control {
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: none;
        }
        .form-control:focus {
            border-color: rgba(12, 91, 94, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(12, 91, 94, 0.12);
        }
        .ppdb-btn {
            min-height: 48px;
            border-radius: 14px;
            font-weight: 600;
        }
        .helper-links {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 1rem 0 1.2rem;
            font-size: 0.92rem;
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
            <p>Portal Pendaftaran Peserta Didik Baru</p>
        </div>

        <div class="ppdb-auth-body">
            <div class="mb-4">
                <h4 class="fw-bold mb-1">Masuk</h4>
                <p class="text-muted mb-0">Silakan masuk untuk melanjutkan pendaftaran.</p>
            </div>

            <form>
                <div class="mb-3">
                    <label class="form-label" for="loginIdentity">Email / Nomor WhatsApp</label>
                    <input type="text" class="form-control" id="loginIdentity" value="budi.santoso@gmail.com">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="loginPassword">Password</label>
                    <input type="password" class="form-control" id="loginPassword" value="password123">
                </div>

                <div class="helper-links">
                    <a href="#" class="text-decoration-none text-muted">Lupa password?</a>
                    <a href="{{ route('ppdb.daftar') }}" class="text-decoration-none text-primary fw-semibold">Belum memiliki akun? Daftar</a>
                </div>

                <a href="{{ route('ppdb.dashboard') }}" class="btn btn-primary ppdb-btn w-100">Masuk</a>
            </form>
        </div>
    </div>
</body>
</html>
