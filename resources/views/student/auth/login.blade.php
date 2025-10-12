<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Öğrenci Girişi - Ders Programı Yönetimi</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.4);
            color: white;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .text-muted {
            text-align: center;
            margin-top: 1rem;
        }

        .text-muted a {
            color: #4e73df;
            text-decoration: none;
            font-weight: 500;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        .student-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="student-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h1>Öğrenci Girişi</h1>
            <p>Ders takip sistemine giriş yapın</p>
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

            <form method="POST" action="{{ route('student.login.post') }}">
                @csrf
                
                <div class="form-floating">
                    <input type="text" 
                           class="form-control @error('student_number') is-invalid @enderror" 
                           id="student_number" 
                           name="student_number" 
                           placeholder="Öğrenci Numarası"
                           value="{{ old('student_number') }}"
                           required>
                    <label for="student_number">
                        <i class="fas fa-id-card me-2"></i>
                        Öğrenci Numarası
                    </label>
                    @error('student_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Şifre"
                           required>
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>
                        Şifre
                    </label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Giriş Yap
                </button>
            </form>

            <div class="text-muted">
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    Öğrenci numaranız ve şifreniz ile giriş yapabilirsiniz.
                </small>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                    Admin Paneline Dön
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
