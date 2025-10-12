<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş Geldiniz</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .welcome-title {
            color: #28a745;
            font-size: 28px;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .student-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .login-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 class="welcome-title">Hoş Geldiniz!</h1>
        </div>

        <div class="content">
            <p>Merhaba <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>,</p>
            
            <p>{{ config('app.name') }} sistemine hoş geldiniz! Hesabınız başarıyla oluşturuldu ve artık sisteme giriş yapabilirsiniz.</p>

            <div class="student-info">
                <h3 style="margin-top: 0; color: #007bff;">Hesap Bilgileriniz</h3>
                <div class="info-item">
                    <span class="info-label">Öğrenci Numarası:</span> {{ $student->student_number }}
                </div>
                <div class="info-item">
                    <span class="info-label">E-posta:</span> {{ $student->email }}
                </div>
                <div class="info-item">
                    <span class="info-label">Telefon:</span> {{ $student->phone ?? 'Belirtilmemiş' }}
                </div>
            </div>

            <div class="highlight">
                <strong>Önemli:</strong> Sisteme giriş yapmak için öğrenci numaranızı ve size verilen şifreyi kullanın.
            </div>

            <p>Sistemde şunları yapabilirsiniz:</p>
            <ul>
                <li>Günlük ders programınızı görüntüleyebilirsiniz</li>
                <li>Derslerinizi tamamlandı olarak işaretleyebilirsiniz</li>
                <li>Çalışma sürenizi ve notlarınızı kaydedebilirsiniz</li>
                <li>Geçmiş derslerinizi inceleyebilirsiniz</li>
                <li>Günlük ilerleme raporlarınızı takip edebilirsiniz</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="login-button">Sisteme Giriş Yap</a>
            </div>

            <p>Herhangi bir sorunuz olursa, lütfen admininizle iletişime geçin.</p>
        </div>

        <div class="footer">
            <p>Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayın.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tüm hakları saklıdır.</p>
        </div>
    </div>
</body>
</html>
