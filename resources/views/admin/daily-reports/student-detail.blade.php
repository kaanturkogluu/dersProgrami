@extends('admin.layout')

@section('title', 'Öğrenci Detay Raporu - ' . $student->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Öğrenci Detay Raporu</h1>
                    <p class="text-muted mb-0">{{ $student->full_name }} - Günlük Ders Takibi</p>
                </div>
                <div>
                    <a href="{{ route('admin.daily-reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
            </div>

            <!-- Tarih Filtresi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tarih Aralığı</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ $startDate }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ $endDate }}" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-2"></i>Filtrele
                            </button>
                            <a href="{{ route('admin.daily-reports.student', $student) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Sıfırla
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Genel İstatistikler -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $overallStats['total_lessons'] }}</h4>
                                    <p class="mb-0">Toplam Ders</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $overallStats['completed_lessons'] }}</h4>
                                    <p class="mb-0">Tamamlanan</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">%{{ $overallStats['completion_rate'] }}</h4>
                                    <p class="mb-0">Tamamlanma Oranı</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-percentage fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ number_format($overallStats['total_study_time'] / 60, 1) }}h</h4>
                                    <p class="mb-0">Toplam Çalışma</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Günlük İstatistikler -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Günlük İstatistikler</h5>
                </div>
                <div class="card-body">
                    @if(count($dailyStats) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>Gün</th>
                                        <th>Toplam Ders</th>
                                        <th>Tamamlanan</th>
                                        <th>Çalışma Süresi</th>
                                        <th>Ortalama Anlama</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyStats as $day)
                                        <tr>
                                            <td>{{ $day['date_formatted'] }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $day['day_name'] == 'Monday' ? 'Pazartesi' : 
                                                       ($day['day_name'] == 'Tuesday' ? 'Salı' : 
                                                       ($day['day_name'] == 'Wednesday' ? 'Çarşamba' : 
                                                       ($day['day_name'] == 'Thursday' ? 'Perşembe' : 
                                                       ($day['day_name'] == 'Friday' ? 'Cuma' : 
                                                       ($day['day_name'] == 'Saturday' ? 'Cumartesi' : 'Pazar'))))) }}
                                                </span>
                                            </td>
                                            <td>{{ $day['total_lessons'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $day['completed_lessons'] > 0 ? 'success' : 'secondary' }}">
                                                    {{ $day['completed_lessons'] }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($day['total_study_time'] / 60, 1) }}h</td>
                                            <td>
                                                @if($day['average_understanding'])
                                                    <span class="badge bg-{{ $day['average_understanding'] >= 7 ? 'success' : ($day['average_understanding'] >= 5 ? 'warning' : 'danger') }}">
                                                        {{ number_format($day['average_understanding'], 1) }}/10
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($day['total_lessons'] > 0)
                                                    @php
                                                        $completionRate = ($day['completed_lessons'] / $day['total_lessons']) * 100;
                                                    @endphp
                                                    @if($completionRate >= 80)
                                                        <span class="badge bg-success">Mükemmel</span>
                                                    @elseif($completionRate >= 60)
                                                        <span class="badge bg-warning">İyi</span>
                                                    @elseif($completionRate >= 40)
                                                        <span class="badge bg-info">Orta</span>
                                                    @else
                                                        <span class="badge bg-danger">Düşük</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Ders Yok</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Seçilen tarih aralığında veri bulunamadı</h5>
                            <p class="text-muted">Farklı bir tarih aralığı seçmeyi deneyin.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detaylı Takip Verileri -->
            @if($trackings->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detaylı Takip Verileri</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>Ders</th>
                                        <th>Konu</th>
                                        <th>Alt Konu</th>
                                        <th>Durum</th>
                                        <th>Çalışma Süresi</th>
                                        <th>Zorluk</th>
                                        <th>Anlama</th>
                                        <th>Notlar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trackings as $tracking)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($tracking->tracking_date)->format('d.m.Y') }}</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $tracking->scheduleItem->course->name ?? 'Bilinmiyor' }}
                                                </span>
                                            </td>
                                            <td>{{ $tracking->scheduleItem->topic->name ?? 'Bilinmiyor' }}</td>
                                            <td>{{ $tracking->scheduleItem->subtopic->name ?? 'Bilinmiyor' }}</td>
                                            <td>
                                                @if($tracking->is_completed)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Tamamlandı
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Tamamlanmadı
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->study_duration_minutes)
                                                    {{ $tracking->study_duration_minutes }} dk
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->difficulty_level)
                                                    <span class="badge bg-{{ $tracking->difficulty_level == 'easy' ? 'success' : ($tracking->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                        {{ $tracking->difficulty_level == 'easy' ? 'Kolay' : ($tracking->difficulty_level == 'medium' ? 'Orta' : 'Zor') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->understanding_score)
                                                    <span class="badge bg-{{ $tracking->understanding_score >= 7 ? 'success' : ($tracking->understanding_score >= 5 ? 'warning' : 'danger') }}">
                                                        {{ $tracking->understanding_score }}/10
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tracking->notes)
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                          title="{{ $tracking->notes }}">
                                                        {{ $tracking->notes }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Analiz Grafikleri -->
            @if($trackings->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Zorluk Dağılımı</h5>
                            </div>
                            <div class="card-body">
                                @if($overallStats['difficulty_breakdown']->count() > 0)
                                    <div class="row">
                                        @foreach($overallStats['difficulty_breakdown'] as $level => $count)
                                            <div class="col-4 text-center">
                                                <h4 class="text-{{ $level == 'easy' ? 'success' : ($level == 'medium' ? 'warning' : 'danger') }}">
                                                    {{ $count }}
                                                </h4>
                                                <p class="mb-0 small">
                                                    {{ $level == 'easy' ? 'Kolay' : ($level == 'medium' ? 'Orta' : 'Zor') }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center">Veri bulunamadı</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Anlama Seviyesi</h5>
                            </div>
                            <div class="card-body">
                                @if($overallStats['understanding_breakdown']->count() > 0)
                                    <div class="row">
                                        @foreach($overallStats['understanding_breakdown'] as $level => $count)
                                            <div class="col-6 text-center mb-2">
                                                <h5 class="text-{{ strpos($level, 'Yüksek') !== false ? 'success' : (strpos($level, 'Orta') !== false ? 'warning' : (strpos($level, 'Düşük') !== false ? 'info' : 'danger')) }}">
                                                    {{ $count }}
                                                </h5>
                                                <p class="mb-0 small">{{ $level }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center">Veri bulunamadı</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75em;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection
