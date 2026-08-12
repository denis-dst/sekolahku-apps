<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SekolahKu SaaS Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0b2518;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
        }

        .btn-primary:hover {
            background-color: #15803d !important;
            border-color: #15803d !important;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }

        .demo-pill {
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.8rem;
        }

        .demo-pill:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card p-4 p-sm-5">
        <div class="text-center mb-4">
            <div class="bg-primary text-white rounded-4 d-inline-flex align-items-center justify-content-center p-3 mb-3 shadow">
                <i class="bi bi-mortarboard-fill fs-2"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit';">SekolahKu SaaS</h3>
            <p class="text-muted small">SIM Akademik & Digital Finance Sekolah</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 small py-2 mb-3">
                <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" id="email" class="form-control bg-light border-start-0" placeholder="nama@sekolah.sch.id" required value="{{ old('email', 'admin@sekolahku.id') }}">
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label small fw-semibold text-dark">Kata Sandi</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" id="password" class="form-control bg-light border-start-0" placeholder="••••••••" required value="password">
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold shadow-sm mb-4">
                Masuk ke Sistem <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="border-top pt-3">
            <p class="text-xs text-muted fw-semibold mb-2 text-center" style="font-size:0.75rem;">AKUN DEMO CEPAT (KLIK UNTUK ISI):</p>
            <div class="d-flex flex-wrap gap-1 justify-content-center">
                <span class="badge bg-dark-subtle text-dark border demo-pill p-2" onclick="setDemo('admin@sekolahku.id')">Superadmin</span>
                <span class="badge bg-primary-subtle text-primary border demo-pill p-2" onclick="setDemo('headmaster@tknpembina.sch.id')">Kepala Sekolah</span>
                <span class="badge bg-success-subtle text-success border demo-pill p-2" onclick="setDemo('bendahara@tknpembina.sch.id')">Bendahara</span>
                <span class="badge bg-info-subtle text-info border demo-pill p-2" onclick="setDemo('guru@tknpembina.sch.id')">Guru Kelas</span>
                <span class="badge bg-warning-subtle text-warning border demo-pill p-2" onclick="setDemo('ortu@tknpembina.sch.id')">Orang Tua</span>
                <span class="badge bg-secondary-subtle text-secondary border demo-pill p-2" onclick="setDemo('siswa@tknpembina.sch.id')">Siswa</span>
            </div>
        </div>
    </div>

    <script>
        function setDemo(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
