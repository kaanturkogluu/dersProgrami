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
            @if($weeklySchedule->count() > 0)
                <a href="{{ route('admin.programs.student.calendar.edit', $student) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    Programı Düzenle
                </a>
            @endif
            <a href="{{ route('admin.schedules.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Program Ekle
            </a>
            @if($weeklySchedule->count() > 0)
                <a href="{{ route('admin.programs.student.calendar.pdf', $student) }}" class="btn btn-success">
                    <i class="fas fa-file-pdf me-2"></i>
                    PDF İndir
                </a>
            @endif
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
                                @foreach($schedules->pluck('areas')->flatten()->unique() as $area)
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

    <!-- Program Tarihleri -->
    @if($schedules->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Program Tarihleri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($schedules as $schedule)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-{{ $schedule->status == 'active' ? 'success' : ($schedule->status == 'upcoming' ? 'warning' : 'secondary') }}">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        {{ $schedule->name }}
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Başlangıç:</small>
                                        <strong>{{ $schedule->start_date->format('d.m.Y') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Bitiş:</small>
                                        <strong>{{ $schedule->end_date->format('d.m.Y') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Durum:</small>
                                        <span class="badge bg-{{ $schedule->status == 'active' ? 'success' : ($schedule->status == 'upcoming' ? 'warning' : 'secondary') }}">
                                            @if($schedule->status == 'active')
                                                <i class="fas fa-play me-1"></i>Aktif
                                            @elseif($schedule->status == 'upcoming')
                                                <i class="fas fa-clock me-1"></i>Yaklaşan
                                            @else
                                                <i class="fas fa-check me-1"></i>Tamamlandı
                                            @endif
                                        </span>
                                    </div>
                                    @if($schedule->description)
                                        <div class="mt-2">
                                            <small class="text-muted">{{ $schedule->description }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    </div>

    <!-- Haftalık Takvim - Excel Formatı -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-week me-2"></i>
                Haftalık Program Takvimi
            </h5>
        </div>
        <div class="card-body p-0">
            @if($weeklySchedule->count() > 0)
                <div class="excel-calendar-container">
                    <div class="table-responsive">
                        <table class="table table-bordered excel-calendar-table mb-0">
                            <thead class="table-dark">
                                <tr>
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
                                        <th class="day-header text-center">
                                            <div>{{ $dayName }}</div>
                                            @if($schedules->count() > 0)
                                                @php
                                                    $firstSchedule = $schedules->first();
                                                    $startDate = $firstSchedule->start_date;
                                                    $dayIndex = array_search($dayKey, array_keys($days));
                                                    $dayDate = $startDate->copy()->addDays($dayIndex);
                                                @endphp
                                                <small class="text-muted">{{ $dayDate->format('d.m') }}</small>
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // En fazla program olan günü bul
                                    $maxPrograms = 0;
                                    foreach($days as $dayKey => $dayName) {
                                        if(isset($weeklySchedule[$dayKey])) {
                                            $maxPrograms = max($maxPrograms, $weeklySchedule[$dayKey]->count());
                                        }
                                    }
                                @endphp
                                
                                @for($row = 0; $row < $maxPrograms; $row++)
                                    <tr class="program-row">
                                        @foreach($days as $dayKey => $dayName)
                                            <td class="day-cell">
                                                @if(isset($weeklySchedule[$dayKey]) && isset($weeklySchedule[$dayKey][$row]))
                                                    @php $program = $weeklySchedule[$dayKey][$row]; @endphp
                                                    
                                                    <div class="excel-program-item">
                                                        <!-- Ders -->
                                                        <div class="excel-course-row">
                                                            <span class="course-name">{{ $program['course']->name }}</span>
                                                            <span class="area-badge badge bg-{{ $program['area'] == 'TYT' ? 'primary' : ($program['area'] == 'AYT' ? 'success' : ($program['area'] == 'KPSS' ? 'warning' : ($program['area'] == 'DGS' ? 'info' : 'secondary'))) }}">
                                                                {{ $program['area'] }}
                                                            </span>
                                                        </div>
                                                        
                                                        <!-- Konu -->
                                                        @if($program['topic'])
                                                            <div class="excel-topic-row">
                                                                <span class="topic-name">{{ $program['topic']->name }}</span>
                                                            </div>
                                                            
                                                            <!-- Alt Konu -->
                                                            @if($program['subtopic'])
                                                                <div class="excel-subtopic-row">
                                                                    <span class="subtopic-name">{{ $program['subtopic']->name }}</span>
                                                                </div>
                                                            @else
                                                                <!-- Alt konu yoksa boş satır -->
                                                                <div class="excel-empty-row"></div>
                                                            @endif
                                                        @else
                                                            <!-- Konu yoksa boş satır -->
                                                            <div class="excel-empty-row"></div>
                                                        @endif
                                                        
                                                        <!-- Notlar -->
                                                        @if($program['notes'])
                                                            <div class="excel-notes-row">
                                                                <span class="notes-text">{{ $program['notes'] }}</span>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Durum -->
                                                        <div class="excel-status-row">
                                                            @if($program['is_completed'])
                                                                <span class="status-completed">✓</span>
                                                            @else
                                                                <span class="status-pending">⏳</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="excel-empty-cell">
                                                        <span class="empty-text">-</span>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
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

/* Excel Benzeri Takvim Stilleri */
.excel-calendar-container {
    background-color: #ffffff;
    overflow-x: auto;
}

.excel-calendar-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 0.85rem;
}

.excel-calendar-table th {
    background-color: #4472c4;
    color: white;
    font-weight: 600;
    text-align: center;
    padding: 0.75rem 0.5rem;
    border: 1px solid #2f5597;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.excel-calendar-table td {
    border: 1px solid #d0d7de;
    padding: 0.5rem;
    vertical-align: top;
    background-color: #ffffff;
    min-height: 80px;
    width: 14.28%; /* 100% / 7 gün */
}

.program-row:hover td {
    background-color: #f6f8fa;
}

.day-cell {
    position: relative;
    min-height: 100px;
}

/* Excel Program İçeriği */
.excel-program-item {
    padding: 0.25rem;
    background-color: #f8f9fa;
    border: 1px solid #e1e4e8;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
    line-height: 1.3;
}

.excel-program-item:hover {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

/* Ders Satırı */
.excel-course-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.25rem;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid #e1e4e8;
}

.course-name {
    font-weight: 600;
    color: #24292e;
    font-size: 0.85rem;
    flex: 1;
}

.area-badge {
    font-size: 0.65rem;
    padding: 0.15rem 0.4rem;
    border-radius: 0.2rem;
    font-weight: 500;
}

/* Konu Satırı */
.excel-topic-row {
    margin-left: 0.5rem;
    margin-bottom: 0.15rem;
    padding-left: 0.5rem;
    border-left: 2px solid #0366d6;
}

.topic-name {
    font-size: 0.75rem;
    color: #586069;
    font-weight: 500;
}

/* Alt Konu Satırı */
.excel-subtopic-row {
    margin-left: 1rem;
    margin-bottom: 0.15rem;
    padding-left: 0.5rem;
    border-left: 2px solid #28a745;
}

.subtopic-name {
    font-size: 0.7rem;
    color: #6a737d;
    font-style: italic;
}

/* Boş Satır */
.excel-empty-row {
    height: 0.4rem;
    margin: 0.1rem 0;
}

/* Notlar Satırı */
.excel-notes-row {
    margin-top: 0.25rem;
    padding-top: 0.25rem;
    border-top: 1px solid #e1e4e8;
}

.notes-text {
    font-size: 0.7rem;
    color: #6a737d;
    font-style: italic;
    line-height: 1.2;
}

/* Durum Satırı */
.excel-status-row {
    text-align: right;
    margin-top: 0.25rem;
}

.status-completed {
    color: #28a745;
    font-weight: bold;
    font-size: 0.8rem;
}

.status-pending {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Boş Hücre */
.excel-empty-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100px;
    color: #6a737d;
}

.empty-text {
    font-size: 0.8rem;
    font-style: italic;
}

/* Excel Benzeri Alternating Rows */
.program-row:nth-child(even) td {
    background-color: #fafbfc;
}

.program-row:nth-child(even):hover td {
    background-color: #f1f8ff;
}

/* Responsive Tasarım */
@media (max-width: 1200px) {
    .excel-calendar-table {
        font-size: 0.8rem;
    }
    
    .excel-calendar-table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .excel-calendar-table td {
        padding: 0.25rem;
    }
}

@media (max-width: 768px) {
    .excel-calendar-table {
        font-size: 0.75rem;
    }
    
    .course-name {
        font-size: 0.8rem;
    }
    
    .topic-name {
        font-size: 0.7rem;
    }
    
    .subtopic-name {
        font-size: 0.65rem;
    }
}

@media (max-width: 576px) {
    .excel-calendar-container {
        font-size: 0.7rem;
    }
    
    .excel-calendar-table th {
        padding: 0.4rem 0.15rem;
        font-size: 0.7rem;
    }
    
    .excel-calendar-table td {
        padding: 0.15rem;
    }
    
    .day-cell {
        min-height: 80px;
    }
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
