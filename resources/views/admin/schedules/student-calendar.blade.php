@extends('admin.layout')

@section('title', $student->full_name . ' - Program Takvimi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ $student->full_name }} - Program Takvimi
            </h1>
            <p class="text-muted mb-0">{{ $student->student_number }} - Haftalık Program Görünümü</p>
        </div>
        <div>
            <a href="{{ route('admin.programs.students') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Öğrenci Listesi
            </a>
            <a href="{{ route('admin.schedules.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Program Ekle
            </a>
        </div>
    </div>

    <!-- Öğrenci Bilgileri -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="student-avatar-large">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $student->full_name }}</h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card me-2"></i>
                                {{ $student->student_number }}
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($schedules->pluck('area')->unique() as $area)
                                    <span class="badge bg-{{ $area == 'TYT' ? 'primary' : ($area == 'AYT' ? 'success' : ($area == 'KPSS' ? 'warning' : ($area == 'DGS' ? 'info' : 'secondary'))) }} fs-6">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Program İstatistikleri</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="stats-item">
                                <div class="stats-number text-primary">{{ $schedules->count() }}</div>
                                <div class="stats-label">Toplam Program</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-item">
                                <div class="stats-number text-success">{{ $weeklySchedule->flatten()->count() }}</div>
                                <div class="stats-label">Toplam Ders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Haftalık Takvim -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-week me-2"></i>
                Haftalık Program Takvimi
            </h5>
        </div>
        <div class="card-body p-0">
            @if($weeklySchedule->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 12%;" class="text-center">Gün</th>
                                <th style="width: 88%;">Program İçeriği</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = [
                                    'monday' => 'Pazartesi',
                                    'tuesday' => 'Salı', 
                                    'wednesday' => 'Çarşamba',
                                    'thursday' => 'Perşembe',
                                    'friday' => 'Cuma',
                                    'saturday' => 'Cumartesi',
                                    'sunday' => 'Pazar'
                                ];
                            @endphp
                            
                            @foreach($days as $dayKey => $dayName)
                                <tr>
                                    <td class="text-center align-middle">
                                        <strong class="day-name">{{ $dayName }}</strong>
                                    </td>
                                    <td>
                                        @if(isset($weeklySchedule[$dayKey]) && $weeklySchedule[$dayKey]->count() > 0)
                                            <div class="day-programs">
                                                @foreach($weeklySchedule[$dayKey] as $program)
                                                    <div class="program-card mb-2">
                                                        <div class="program-header">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="badge bg-{{ $program['area'] == 'TYT' ? 'primary' : ($program['area'] == 'AYT' ? 'success' : ($program['area'] == 'KPSS' ? 'warning' : ($program['area'] == 'DGS' ? 'info' : 'secondary'))) }} me-2">
                                                                        {{ $program['area'] }}
                                                                    </span>
                                                                    <strong>{{ $program['schedule_name'] }}</strong>
                                                                </div>
                                                                <div>
                                                                    @if($program['is_completed'])
                                                                        <span class="badge bg-success">Tamamlandı</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Bekliyor</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="program-content">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="program-item">
                                                                        <i class="fas fa-book text-primary me-2"></i>
                                                                        <strong>Ders:</strong> {{ $program['course']->name }}
                                                                    </div>
                                                                </div>
                                                                @if($program['topic'])
                                                                <div class="col-md-4">
                                                                    <div class="program-item">
                                                                        <i class="fas fa-list text-info me-2"></i>
                                                                        <strong>Konu:</strong> {{ $program['topic']->name }}
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @if($program['subtopic'])
                                                                <div class="col-md-4">
                                                                    <div class="program-item">
                                                                        <i class="fas fa-list-ul text-warning me-2"></i>
                                                                        <strong>Alt Konu:</strong> {{ $program['subtopic']->name }}
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            
                                                            @if($program['notes'])
                                                            <div class="program-notes mt-2">
                                                                <i class="fas fa-sticky-note text-muted me-2"></i>
                                                                <em>{{ $program['notes'] }}</em>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-3">
                                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                                <p class="mb-0">Bu gün için program bulunmuyor</p>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Boş Durum -->
                <div class="text-center py-5">
                    <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Henüz program bulunmuyor</h4>
                    <p class="text-muted mb-4">Bu öğrenci için program oluşturun.</p>
                    <a href="{{ route('admin.schedules.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        Program Oluştur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.student-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.5rem;
}

.stats-item {
    text-align: center;
}

.stats-number {
    font-size: 1.8rem;
    font-weight: bold;
    display: block;
}

.stats-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.day-name {
    color: #4e73df;
    font-size: 1.1rem;
}

.program-card {
    background-color: #f8f9fc;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.program-card:hover {
    background-color: #ffffff;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transform: translateY(-2px);
}

.program-header {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 0.5rem;
    margin-bottom: 0.75rem;
}

.program-content {
    font-size: 0.9rem;
}

.program-item {
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    background-color: #ffffff;
    border-radius: 0.25rem;
    border-left: 3px solid #4e73df;
}

.program-notes {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 0.25rem;
    padding: 0.5rem;
    font-size: 0.85rem;
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

.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #495057;
}

.table td {
    vertical-align: top;
    border: 1px solid #dee2e6;
}

.day-programs {
    min-height: 60px;
}
</style>
@endsection
