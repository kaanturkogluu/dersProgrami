<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Öğrenci Takip Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }

        .login-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .admin-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }

        .login-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .login-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating input {
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-floating input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-floating label {
            color: #6c757d;
            font-weight: 500;
        }

        .btn-login {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.3);
            color: white;
        }

        .form-check-input:checked {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .form-check-label {
            color: #6c757d;
            font-weight: 500;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d1edff;
            color: #0c5460;
        }

        .text-muted {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 1rem;
        }

        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }

        .login-footer {
            background-color: #f8f9fc;
            padding: 1rem 2rem;
            text-align: center;
            border-top: 1px solid #e3e6f0;
        }

        .login-footer small {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="admin-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1>Admin Girişi</h1>
            <p>Yönetim paneline erişim</p>
        </div>
        
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                
                <div class="form-floating">
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           placeholder="E-posta adresiniz"
                           value="{{ old('email') }}"
                           required 
                           autofocus>
                    <label for="email">
                        <i class="fas fa-envelope me-2"></i>E-posta Adresi
                    </label>
                </div>

                <div class="form-floating">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Şifreniz"
                           required>
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>Şifre
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="remember" 
                           id="remember">
                    <label class="form-check-label" for="remember">
                        Beni hatırla
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Giriş Yap
                </button>
            </form>

            <div class="text-muted">
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    Sadece yetkili admin kullanıcıları giriş yapabilir.
                </small>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('student.login') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-user-graduate me-1"></i>
                    Öğrenci Girişi
                </a>
            </div>
        </div>
        
        <div class="login-footer">
            <small>
                <i class="fas fa-shield-alt me-1"></i>
                Güvenli Admin Paneli
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
