<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Önceki Dersler - Öğrenci Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        .student-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: #f8f9fc;
            border-color: #e3e6f0;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('student.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Öğrenci Paneli
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.daily-tracking') }}">
                            <i class="fas fa-calendar-day me-1"></i>Günlük Takip
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('student.previous-lessons') }}">
                            <i class="fas fa-history me-1"></i>Önceki Dersler
                        </a>
                    </li>
                </ul>
                
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="student-avatar me-2">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                        </div>
                        {{ $student->first_name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('student.daily-tracking') }}">
                            <i class="fas fa-calendar-day me-2"></i>Günlük Takip
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

    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-history me-2"></i>
                Önceki Dersler
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Dashboard'a Dön
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtreler -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('student.previous-lessons') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="days" class="form-label">Gün Sayısı</label>
                        <select class="form-select" id="days" name="days" onchange="this.form.submit()">
                            <option value="7" {{ $days == 7 ? 'selected' : '' }}>Son 7 Gün</option>
                            <option value="15" {{ $days == 15 ? 'selected' : '' }}>Son 15 Gün</option>
                            <option value="30" {{ $days == 30 ? 'selected' : '' }}>Son 30 Gün</option>
                            <option value="60" {{ $days == 60 ? 'selected' : '' }}>Son 60 Gün</option>
                            <option value="90" {{ $days == 90 ? 'selected' : '' }}>Son 90 Gün</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Tarih Aralığı</label>
                        <div class="text-muted">
                            {{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- İstatistikler -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Toplam Gün
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_days'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Toplam Ders
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_lessons'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Tamamlanan Ders
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_lessons'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Çalışma Süresi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ floor($stats['total_study_time'] / 60) }}s {{ $stats['total_study_time'] % 60 }}dk
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dersler Listesi -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Ders Geçmişi
                </h5>
            </div>
            <div class="card-body p-0">
                @if($previousLessons->count() > 0)
                    <div class="accordion" id="lessonsAccordion">
                        @foreach($previousLessons as $index => $dayData)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $index }}" 
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                            aria-controls="collapse{{ $index }}">
                                        <div class="d-flex justify-content-between w-100 me-3">
                                            <div>
                                                <i class="fas fa-calendar-day me-2"></i>
                                                <strong>{{ $dayData['day_name'] }}</strong>
                                                <span class="text-muted ms-2">{{ $dayData['date']->format('d.m.Y') }}</span>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-primary me-2">{{ $dayData['lessons']->count() }} Ders</span>
                                                @php
                                                    $completedCount = $dayData['lessons']->where('tracking.is_completed', true)->count();
                                                    $totalCount = $dayData['lessons']->count();
                                                    $completionRate = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                                                @endphp
                                                <span class="badge bg-{{ $completionRate >= 80 ? 'success' : ($completionRate >= 50 ? 'warning' : 'danger') }}">
                                                    %{{ $completionRate }} Tamamlandı
                                                </span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" 
                                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                     aria-labelledby="heading{{ $index }}" 
                                     data-bs-parent="#lessonsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @foreach($dayData['lessons'] as $lesson)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card h-100 {{ $lesson['tracking'] && $lesson['tracking']->is_completed ? 'border-success' : 'border-secondary' }}">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h6 class="card-title mb-0">
                                                                    <i class="fas fa-book me-1"></i>
                                                                    {{ $lesson['course']->name }}
                                                                </h6>
                                                                @if($lesson['tracking'] && $lesson['tracking']->is_completed)
                                                                    <span class="badge bg-success">
                                                                        <i class="fas fa-check me-1"></i>Tamamlandı
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary">
                                                                        <i class="fas fa-clock me-1"></i>Bekliyor
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            
                                                            @if($lesson['topic'])
                                                                <p class="card-text mb-1">
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-tag me-1"></i>
                                                                        <strong>Konu:</strong> {{ $lesson['topic']->name }}
                                                                    </small>
                                                                </p>
                                                            @endif
                                                            
                                                            @if($lesson['subtopic'])
                                                                <p class="card-text mb-1">
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-list me-1"></i>
                                                                        <strong>Alt Konu:</strong> {{ $lesson['subtopic']->name }}
                                                                    </small>
                                                                </p>
                                                            @endif
                                                            
                                                            @if($lesson['notes'])
                                                                <p class="card-text mb-2">
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-sticky-note me-1"></i>
                                                                        <strong>Notlar:</strong> {{ $lesson['notes'] }}
                                                                    </small>
                                                                </p>
                                                            @endif
                                                            
                                                            @if($lesson['tracking'])
                                                                <div class="mt-2">
                                                                    @if($lesson['tracking']->study_duration_minutes)
                                                                        <small class="text-info me-3">
                                                                            <i class="fas fa-clock me-1"></i>
                                                                            {{ $lesson['tracking']->study_duration_minutes }} dk
                                                                        </small>
                                                                    @endif
                                                                    
                                                                    @if($lesson['tracking']->difficulty_level)
                                                                        <small class="text-warning me-3">
                                                                            <i class="fas fa-signal me-1"></i>
                                                                            {{ ucfirst($lesson['tracking']->difficulty_level) }}
                                                                        </small>
                                                                    @endif
                                                                    
                                                                    @if($lesson['tracking']->understanding_score)
                                                                        <small class="text-primary">
                                                                            <i class="fas fa-star me-1"></i>
                                                                            {{ $lesson['tracking']->understanding_score }}/10
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                                
                                                                @if($lesson['tracking']->notes)
                                                                    <div class="mt-2">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-comment me-1"></i>
                                                                            <strong>Öğrenci Notu:</strong> {{ $lesson['tracking']->notes }}
                                                                        </small>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Bu dönemde ders bulunamadı</h5>
                        <p class="text-muted">Seçilen tarih aralığında herhangi bir ders programı bulunmuyor.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>