<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Günlük Ders Hatırlatması</title>
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
            border-bottom: 2px solid #28a745;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        .reminder-title {
            color: #28a745;
            font-size: 24px;
            margin: 0;
        }
        .date-info {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
        .lessons-section {
            margin: 30px 0;
        }
        .lesson-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
        }
        .lesson-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 8px;
        }
        .lesson-details {
            color: #6c757d;
            font-size: 14px;
        }
        .no-lessons {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .login-button {
            display: inline-block;
            background-color: #28a745;
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
            background-color: #218838;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .motivation {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 class="reminder-title">Günlük Ders Hatırlatması</h1>
        </div>

        <div class="date-info">
            📅 {{ $today->format('d.m.Y') }} - {{ $today->locale('tr')->dayName }}
        </div>

        <p>Merhaba <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>,</p>
        
        <p>Bugün için planlanan derslerinizi hatırlatmak istiyoruz. Başarılı bir gün geçirmeniz için derslerinizi takip etmeyi unutmayın!</p>

        <div class="lessons-section">
            @if($todayLessons->count() > 0)
                <h3 style="color: #28a745; margin-bottom: 20px;">📚 Bugünkü Dersleriniz ({{ $todayLessons->count() }} ders)</h3>
                
                @foreach($todayLessons as $lesson)
                    <div class="lesson-item">
                        <div class="lesson-title">
                            {{ $lesson->course->name }}
                            @if($lesson->topic)
                                - {{ $lesson->topic->name }}
                            @endif
                            @if($lesson->subtopic)
                                - {{ $lesson->subtopic->name }}
                            @endif
                        </div>
                        <div class="lesson-details">
                            <strong>Kategori:</strong> {{ $lesson->course->category->name ?? 'Belirtilmemiş' }}
                            @if($lesson->start_time)
                                <br><strong>Başlangıç:</strong> {{ $lesson->start_time }}
                            @endif
                            @if($lesson->end_time)
                                <br><strong>Bitiş:</strong> {{ $lesson->end_time }}
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-lessons">
                    <h3>🎉 Bugün için planlanan dersiniz bulunmuyor!</h3>
                    <p>Bu günü dinlenme veya tekrar yapma günü olarak değerlendirebilirsiniz.</p>
                </div>
            @endif
        </div>

        <div class="motivation">
            "Başarı, hazırlık ile fırsatın buluştuğu yerdir." - Seneca
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="login-button">Sisteme Giriş Yap</a>
        </div>

        <p>Sistemde şunları yapabilirsiniz:</p>
        <ul>
            <li>Derslerinizi tamamlandı olarak işaretleyin</li>
            <li>Çalışma sürenizi kaydedin</li>
            <li>Ders notlarınızı ekleyin</li>
            <li>Anlama seviyenizi değerlendirin</li>
            <li>Günlük ilerlemenizi takip edin</li>
        </ul>

        <p>Başarılı bir gün geçirmeniz dileğiyle!</p>
    </div>

    <div class="footer">
        <p>Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayın.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tüm hakları saklıdır.</p>
    </div>
</body>
</html>
