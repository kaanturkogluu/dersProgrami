<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Öğrenci Dashboard - {{ $student->full_name }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .lesson-card {
            border-left: 4px solid #4e73df;
            transition: all 0.3s ease;
        }

        .lesson-card:hover {
            border-left-color: #224abe;
        }

        .lesson-completed {
            border-left-color: #28a745;
            background-color: #f8fff9;
        }

        .lesson-pending {
            border-left-color: #ffc107;
            background-color: #fffdf5;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 0.35rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 0.25rem;
        }

        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('student.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Öğrenci Paneli
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="student-avatar me-2">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                        </div>
                        {{ $student->first_name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('student.daily-tracking') }}">
                            <i class="fas fa-calendar-day me-2"></i>Günlük Takip
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('student.previous-lessons') }}">
                            <i class="fas fa-history me-2"></i>Önceki Dersler
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('student.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">Hoş geldiniz, {{ $student->first_name }}!</h2>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $today->format('d.m.Y') }} - {{ $today->format('l') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('student.daily-tracking') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Bugünkü Dersleri İşaretle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number">{{ $weeklyStats['total_lessons'] }}</div>
                        <div class="stats-label">Bu Hafta Toplam Ders</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number">{{ $weeklyStats['completed_lessons'] }}</div>
                        <div class="stats-label">Tamamlanan Ders</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number">{{ round($weeklyStats['total_study_time'] / 60, 1) }}h</div>
                        <div class="stats-label">Toplam Çalışma Süresi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <div class="stats-number">{{ $weeklyStats['average_understanding'] }}/10</div>
                        <div class="stats-label">Ortalama Anlama</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-tasks me-2"></i>
                            Hızlı İşlemler
                        </h5>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('student.daily-tracking') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-calendar-day me-2"></i>
                                    Günlük Takip
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('student.previous-lessons') }}" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-history me-2"></i>
                                    Önceki Dersler
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('student.daily-tracking') }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-plus me-2"></i>
                                    Ders Ekle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Lessons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Bugünkü Dersler ({{ $today->format('d.m.Y') }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($todayLessons->count() > 0)
                            <div class="row">
                                @foreach($todayLessons as $lesson)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card lesson-card {{ $lesson['tracking'] && $lesson['tracking']->is_completed ? 'lesson-completed' : 'lesson-pending' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">{{ $lesson['course']->name }}</h6>
                                                    @if($lesson['tracking'] && $lesson['tracking']->is_completed)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Tamamlandı
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>Bekliyor
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($lesson['topic'])
                                                    <p class="text-muted mb-1">
                                                        <i class="fas fa-book me-1"></i>
                                                        {{ $lesson['topic']->name }}
                                                    </p>
                                                @endif
                                                
                                                @if($lesson['subtopic'])
                                                    <p class="text-muted mb-2">
                                                        <i class="fas fa-list-ul me-1"></i>
                                                        {{ $lesson['subtopic']->name }}
                                                    </p>
                                                @endif
                                                
                                                @if($lesson['tracking'])
                                                    <div class="mb-2">
                                                        @if($lesson['tracking']->study_duration_minutes)
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ $lesson['tracking']->study_duration_minutes }} dakika
                                                            </small>
                                                        @endif
                                                        
                                                        @if($lesson['tracking']->understanding_score)
                                                            <small class="text-muted ms-2">
                                                                <i class="fas fa-star me-1"></i>
                                                                {{ $lesson['tracking']->understanding_score }}/10
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if($lesson['tracking'] && $lesson['tracking']->is_completed)
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="quickToggleLesson({{ $lesson['id'] }}, false)"
                                                                title="Tamamlanmadı olarak işaretle">
                                                            <i class="fas fa-undo me-1"></i>
                                                            Geri Al
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="quickToggleLesson({{ $lesson['id'] }}, true)"
                                                                title="Hızlı tamamla">
                                                            <i class="fas fa-check me-1"></i>
                                                            Tamamla
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('student.daily-tracking', ['date' => $today->format('Y-m-d')]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Detaylı
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Bugün için ders bulunmuyor</h5>
                                <p class="text-muted">Programınızda bugün için planlanmış ders yok.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Hızlı ders tamamlama/geri alma
        function quickToggleLesson(lessonId, isCompleted) {
            const formData = new FormData();
            formData.append('schedule_item_id', lessonId);
            formData.append('tracking_date', '{{ $today->format("Y-m-d") }}');
            formData.append('is_completed', isCompleted ? '1' : '0');
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("student.daily-tracking.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    
                    // Sayfayı yenile
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', data.message || 'Bir hata oluştu.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Bir hata oluştu.');
            });
        }

        // Alert gösterme
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    </script>
</body>
</html>
