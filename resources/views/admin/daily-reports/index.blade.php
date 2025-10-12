@extends('admin.layout')

@section('title', 'Günlük Raporlar')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line me-2"></i>
                Günlük Raporlar
            </h1>
            <p class="text-muted mb-0">Öğrencilerin günlük ders takip durumlarını inceleyin</p>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.daily-reports.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Tarih</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ $selectedDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="student_id" class="form-label">Öğrenci</label>
                            <select class="form-select" id="student_id" name="student_id">
                                <option value="">Tüm Öğrenciler</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            {{ $studentFilter == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }} ({{ $student->student_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>Filtrele
                            </button>
                            <a href="{{ route('admin.daily-reports.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Temizle
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Ders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_lessons'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tamamlanan Ders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['completed_lessons'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Toplam Çalışma Süresi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ round($stats['total_study_time'] / 60, 1) }}h
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ortalama Anlama
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ round($stats['average_understanding'] ?? 0, 1) }}/10
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Öğrenci Özeti -->
    @if($studentSummary->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>
                            Öğrenci Özeti - {{ $selectedDate->format('d.m.Y') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Öğrenci</th>
                                        <th>Toplam Ders</th>
                                        <th>Tamamlanan</th>
                                        <th>Tamamlanma Oranı</th>
                                        <th>Çalışma Süresi</th>
                                        <th>Ortalama Anlama</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentSummary as $summary)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="student-avatar me-3">
                                                        {{ strtoupper(substr($summary['student']->first_name, 0, 1)) }}{{ strtoupper(substr($summary['student']->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $summary['student']->full_name }}</strong>
                                                        <br><small class="text-muted">{{ $summary['student']->student_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $summary['total_lessons'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $summary['completed_lessons'] }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $completionRate = $summary['total_lessons'] > 0 ? round(($summary['completed_lessons'] / $summary['total_lessons']) * 100, 1) : 0;
                                                @endphp
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar {{ $completionRate >= 80 ? 'bg-success' : ($completionRate >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                         style="width: {{ $completionRate }}%">
                                                        {{ $completionRate }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ round($summary['total_study_time'] / 60, 1) }}h</span>
                                            </td>
                                            <td>
                                                @if($summary['average_understanding'])
                                                    <span class="badge bg-{{ $summary['average_understanding'] >= 8 ? 'success' : ($summary['average_understanding'] >= 6 ? 'warning' : 'danger') }}">
                                                        {{ round($summary['average_understanding'], 1) }}/10
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.daily-reports.student', $summary['student']) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>Detay
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Detaylı Takip Verileri -->
    @if($trackings->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Detaylı Takip Verileri - {{ $selectedDate->format('d.m.Y') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Öğrenci</th>
                                        <th>Ders</th>
                                        <th>Konu</th>
                                        <th>Durum</th>
                                        <th>Çalışma Süresi</th>
                                        <th>Zorluk</th>
                                        <th>Anlama</th>
                                        <th>Notlar</th>
                                        <th>Kayıt Zamanı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trackings as $tracking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="student-avatar me-2">
                                                        {{ strtoupper(substr($tracking->student->first_name, 0, 1)) }}{{ strtoupper(substr($tracking->student->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $tracking->student->full_name }}</strong>
                                                        <br><small class="text-muted">{{ $tracking->student->student_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $tracking->scheduleItem->course->name }}</span>
                                            </td>
                                            <td>
                                                @if($tracking->scheduleItem->topic)
                                                    <span class="badge bg-primary">{{ $tracking->scheduleItem->topic->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->is_completed)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Tamamlandı
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Bekliyor
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->study_duration_minutes)
                                                    <span class="badge bg-info">{{ $tracking->study_duration_minutes }} dk</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->difficulty_level)
                                                    <span class="badge bg-{{ $tracking->difficulty_color }}">
                                                        {{ ucfirst($tracking->difficulty_level) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->understanding_score)
                                                    <span class="badge bg-{{ $tracking->understanding_color }}">
                                                        {{ $tracking->understanding_score }}/10
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->notes)
                                                    <span class="text-muted" title="{{ $tracking->notes }}">
                                                        {{ Str::limit($tracking->notes, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $tracking->created_at->format('H:i') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-chart-line fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Bu tarih için takip verisi bulunmuyor</h4>
                        <p class="text-muted">Seçilen tarihte öğrenci takip verisi bulunamadı.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.student-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
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

.card {
    border: none;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 0.35rem 0.35rem 0 0 !important;
}
</style>
@endsection
