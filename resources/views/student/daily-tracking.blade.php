<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Günlük Ders Takibi - {{ $student->full_name }}</title>
    
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

        .lesson-card {
            border-left: 4px solid #4e73df;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .lesson-card.completed {
            border-left-color: #28a745;
            background-color: #f8fff9;
        }

        .lesson-card.pending {
            border-left-color: #ffc107;
            background-color: #fffdf5;
        }
        
        .lesson-actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .lesson-actions .btn {
            min-width: 100px;
        }
        
        .lesson-card {
            transition: all 0.3s ease;
        }
        
        .lesson-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .tracking-form {
            display: none;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }
        
        .tracking-form.show {
            display: block;
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

        .form-control, .form-select {
            border-radius: 0.35rem;
            border: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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

        .tracking-form {
            display: none;
        }

        .tracking-form.show {
            display: block;
        }

        .difficulty-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
            border-radius: 0.25rem;
        }

        .understanding-stars {
            color: #ffc107;
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
                        <li><a class="dropdown-item" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('student.question-tracking') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Soru Takibi
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">Günlük Ders Takibi</h2>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $selectedDate->format('d.m.Y') }} - {{ $selectedDate->format('l') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <input type="date" 
                                           class="form-control" 
                                           id="datePicker" 
                                           value="{{ $selectedDate->format('Y-m-d') }}">
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('student.previous-lessons') }}" class="btn btn-info btn-lg w-100">
                                    <i class="fas fa-history me-2"></i>
                                    Önceki Dersler
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-success btn-lg w-100" onclick="scrollToLessons()">
                                    <i class="fas fa-arrow-down me-2"></i>
                                    Derslere Git
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lessons -->
        <div class="row">
            <div class="col-12">
                @if($dayLessons->count() > 0)
                    @foreach($dayLessons as $lesson)
                        <div class="card lesson-card {{ $lesson['tracking'] && $lesson['tracking']->is_completed ? 'completed' : 'pending' }}" 
                             data-lesson-id="{{ $lesson['id'] }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">{{ $lesson['course']->name }}</h5>
                                            <div class="lesson-status">
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
                                        </div>
                                        
                                        @if($lesson['topic'])
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-book me-1"></i>
                                                <strong>Konu:</strong> {{ $lesson['topic']->name }}
                                            </p>
                                        @endif
                                        
                                        @if($lesson['subtopic'])
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-list-ul me-1"></i>
                                                <strong>Alt Konu:</strong> {{ $lesson['subtopic']->name }}
                                            </p>
                                        @endif
                                        
                                        @if($lesson['tracking'])
                                            <div class="row">
                                                @if($lesson['tracking']->study_duration_minutes)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <strong>Süre:</strong> {{ $lesson['tracking']->study_duration_minutes }} dk
                                                        </small>
                                                    </div>
                                                @endif
                                                
                                                @if($lesson['tracking']->difficulty_level)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-signal me-1"></i>
                                                            <strong>Zorluk:</strong> 
                                                            <span class="badge difficulty-badge bg-{{ $lesson['tracking']->difficulty_color }}">
                                                                {{ ucfirst($lesson['tracking']->difficulty_level) }}
                                                            </span>
                                                        </small>
                                                    </div>
                                                @endif
                                                
                                                @if($lesson['tracking']->understanding_score)
                                                    <div class="col-md-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-star me-1"></i>
                                                            <strong>Anlama:</strong> {{ $lesson['tracking']->understanding_score }}/10
                                                        </small>
                                                    </div>
                                                @endif
                                                
                                                @if($lesson['tracking']->notes)
                                                    <div class="col-md-12 mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-sticky-note me-1"></i>
                                                            <strong>Notlar:</strong> {{ $lesson['tracking']->notes }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-4 text-end">
                                        <div class="lesson-actions">
                                            @if($lesson['tracking'] && $lesson['tracking']->is_completed)
                                                <button class="btn btn-success btn-sm me-2" 
                                                        onclick="quickToggleLesson({{ $lesson['id'] }}, false)"
                                                        title="Tamamlanmadı olarak işaretle">
                                                    <i class="fas fa-undo me-1"></i>
                                                    Geri Al
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm" 
                                                        onclick="toggleTrackingForm({{ $lesson['id'] }})"
                                                        title="Detayları düzenle">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Düzenle
                                                </button>
                                            @else
                                                <button class="btn btn-success btn-sm me-2" 
                                                        onclick="quickToggleLesson({{ $lesson['id'] }}, true)"
                                                        title="Hızlı tamamla">
                                                    <i class="fas fa-check me-1"></i>
                                                    Tamamla
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm" 
                                                        onclick="toggleTrackingForm({{ $lesson['id'] }})"
                                                        title="Detaylı işaretle">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Detaylı
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tracking Form -->
                                <div class="tracking-form mt-3" id="form-{{ $lesson['id'] }}">
                                    <hr>
                                    <form class="tracking-form-content" data-lesson-id="{{ $lesson['id'] }}">
                                        @csrf
                                        <input type="hidden" name="schedule_item_id" value="{{ $lesson['id'] }}">
                                        <input type="hidden" name="tracking_date" value="{{ $selectedDate->format('Y-m-d') }}">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" id="completed-{{ $lesson['id'] }}" 
                                                           name="is_completed" value="1" 
                                                           {{ $lesson['tracking'] && $lesson['tracking']->is_completed ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="completed-{{ $lesson['id'] }}">
                                                        <strong>Dersi tamamladım</strong>
                                                    </label>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="duration-{{ $lesson['id'] }}" class="form-label">Çalışma Süresi (dakika)</label>
                                                    <input type="number" class="form-control" id="duration-{{ $lesson['id'] }}" 
                                                           name="study_duration_minutes" min="0" max="480"
                                                           value="{{ $lesson['tracking']->study_duration_minutes ?? '' }}">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="difficulty-{{ $lesson['id'] }}" class="form-label">Zorluk Seviyesi</label>
                                                    <select class="form-select" id="difficulty-{{ $lesson['id'] }}" name="difficulty_level">
                                                        <option value="">Seçiniz</option>
                                                        <option value="kolay" {{ $lesson['tracking'] && $lesson['tracking']->difficulty_level == 'kolay' ? 'selected' : '' }}>Kolay</option>
                                                        <option value="orta" {{ $lesson['tracking'] && $lesson['tracking']->difficulty_level == 'orta' ? 'selected' : '' }}>Orta</option>
                                                        <option value="zor" {{ $lesson['tracking'] && $lesson['tracking']->difficulty_level == 'zor' ? 'selected' : '' }}>Zor</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="understanding-{{ $lesson['id'] }}" class="form-label">Anlama Puanı (1-10)</label>
                                                    <input type="number" class="form-control" id="understanding-{{ $lesson['id'] }}" 
                                                           name="understanding_score" min="1" max="10"
                                                           value="{{ $lesson['tracking']->understanding_score ?? '' }}">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="notes-{{ $lesson['id'] }}" class="form-label">Notlar</label>
                                                    <textarea class="form-control" id="notes-{{ $lesson['id'] }}" 
                                                              name="notes" rows="3" maxlength="1000">{{ $lesson['tracking']->notes ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="button" class="btn btn-outline-secondary me-2" 
                                                    onclick="toggleTrackingForm({{ $lesson['id'] }})">
                                                <i class="fas fa-times me-1"></i>İptal
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-1"></i>Kaydet
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-calendar-day fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">Bu gün için ders bulunmuyor</h4>
                            <p class="text-muted">Seçilen tarihte programınızda ders bulunmuyor.</p>
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Dashboard'a Dön
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tarih değiştirme
        document.getElementById('datePicker').addEventListener('change', function() {
            const selectedDate = this.value;
            window.location.href = `{{ route('student.daily-tracking') }}?date=${selectedDate}`;
        });

        // Form göster/gizle
        function toggleTrackingForm(lessonId) {
            const form = document.getElementById(`form-${lessonId}`);
            form.classList.toggle('show');
        }

        // Form gönderme
        document.querySelectorAll('.tracking-form-content').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const lessonId = this.dataset.lessonId;
                
                fetch('{{ route("student.daily-tracking.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                       document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Başarı mesajı göster
                        showAlert('success', data.message);
                        
                        // Sayfayı yenile
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', data.message || 'Bir hata oluştu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Bir hata oluştu.');
                });
            });
        });

        // Derslere scroll
        function scrollToLessons() {
            const lessonsSection = document.querySelector('.lesson-card');
            if (lessonsSection) {
                lessonsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Hızlı ders tamamlama/geri alma
        function quickToggleLesson(lessonId, isCompleted) {
            const formData = new FormData();
            formData.append('schedule_item_id', lessonId);
            formData.append('tracking_date', '{{ $selectedDate->format("Y-m-d") }}');
            formData.append('is_completed', isCompleted ? '1' : '0');
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            fetch('{{ route("student.daily-tracking.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    
                    // UI'yi güncelle
                    const lessonCard = document.querySelector(`[data-lesson-id="${lessonId}"]`);
                    const statusBadge = lessonCard.querySelector('.lesson-status');
                    const actionButtons = lessonCard.querySelector('.lesson-actions');
                    
                    if (isCompleted) {
                        // Tamamlandı olarak işaretle
                        lessonCard.classList.remove('pending');
                        lessonCard.classList.add('completed');
                        statusBadge.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Tamamlandı</span>';
                        actionButtons.innerHTML = `
                            <button class="btn btn-success btn-sm me-2" 
                                    onclick="quickToggleLesson(${lessonId}, false)"
                                    title="Tamamlanmadı olarak işaretle">
                                <i class="fas fa-undo me-1"></i>
                                Geri Al
                            </button>
                            <button class="btn btn-outline-primary btn-sm" 
                                    onclick="toggleTrackingForm(${lessonId})"
                                    title="Detayları düzenle">
                                <i class="fas fa-edit me-1"></i>
                                Düzenle
                            </button>
                        `;
                    } else {
                        // Geri al
                        lessonCard.classList.remove('completed');
                        lessonCard.classList.add('pending');
                        statusBadge.innerHTML = '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Bekliyor</span>';
                        actionButtons.innerHTML = `
                            <button class="btn btn-success btn-sm me-2" 
                                    onclick="quickToggleLesson(${lessonId}, true)"
                                    title="Hızlı tamamla">
                                <i class="fas fa-check me-1"></i>
                                Tamamla
                            </button>
                            <button class="btn btn-outline-primary btn-sm" 
                                    onclick="toggleTrackingForm(${lessonId})"
                                    title="Detaylı işaretle">
                                <i class="fas fa-edit me-1"></i>
                                Detaylı
                            </button>
                        `;
                    }
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
            
            // 3 saniye sonra otomatik kapat
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }
    </script>
</body>
</html>
