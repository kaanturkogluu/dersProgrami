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
        <div class="d-flex gap-2">
            <a href="{{ route('admin.programs.students') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Öğrenci Listesi
            </a>
            @if($weeklySchedule->count() > 0)
                <a href="{{ route('admin.programs.student.calendar.edit', $student) }}" class="btn btn-info">
                    <i class="fas fa-edit me-2"></i>
                    Detaylı Düzenle
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-week me-2"></i>
                Haftalık Program Takvimi
            </h5>
            @if($weeklySchedule->count() > 0)
            <div class="alert alert-info mb-0 py-2 px-3">
                <i class="fas fa-calendar-alt me-2"></i>
                <small><strong>Hızlı Düzenleme:</strong> Her dersin altındaki gün butonlarına tıklayarak dersi o güne taşıyın.</small>
            </div>
            @endif
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
                                            <td class="day-cell" data-day="{{ $dayKey }}">
                                                @if(isset($weeklySchedule[$dayKey]) && isset($weeklySchedule[$dayKey][$row]))
                                                    @php 
                                                        $program = $weeklySchedule[$dayKey][$row];
                                                        $bgColor = $program['area'] == 'TYT' ? 'primary' : ($program['area'] == 'AYT' ? 'success' : ($program['area'] == 'KPSS' ? 'warning' : ($program['area'] == 'DGS' ? 'info' : 'secondary')));
                                                    @endphp
                                                    
                                                    <div class="excel-program-item-quick" 
                                                         data-schedule-item-id="{{ $program['schedule_item_id'] }}"
                                                         data-day="{{ $dayKey }}"
                                                         data-area="{{ $program['area'] }}">
                                                        
                                                        <!-- Üst Bar -->
                                                        <div class="program-header bg-{{ $bgColor }}">
                                                            <span class="area-badge-modern">{{ $program['area'] }}</span>
                                                        </div>
                                                        
                                                        <!-- Program İçeriği -->
                                                        <div class="program-content">
                                                            <!-- Ders -->
                                                            <div class="course-title">
                                                                <i class="fas fa-book me-2 text-{{ $bgColor }}"></i>
                                                                <strong>{{ $program['course']->name }}</strong>
                                                            </div>
                                                            
                                                            <!-- Konu -->
                                                            @if($program['topic'])
                                                                <div class="topic-title">
                                                                    <i class="fas fa-bookmark me-2 text-muted"></i>
                                                                    {{ $program['topic']->name }}
                                                                </div>
                                                                
                                                                <!-- Alt Konu -->
                                                                @if($program['subtopic'])
                                                                    <div class="subtopic-title">
                                                                        <i class="fas fa-caret-right me-2 text-muted"></i>
                                                                        <small>{{ $program['subtopic']->name }}</small>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            
                                                            <!-- Notlar -->
                                                            @if($program['notes'])
                                                                <div class="program-notes">
                                                                    <i class="fas fa-sticky-note me-2 text-info"></i>
                                                                    <small class="text-muted">{{ $program['notes'] }}</small>
                                                                </div>
                                                            @endif
                                                            
                                                            <!-- Alt Bar - Durum -->
                                                            <div class="program-footer">
                                                                @if($program['is_completed'])
                                                                    <span class="badge bg-success">
                                                                        <i class="fas fa-check-circle me-1"></i>
                                                                        Tamamlandı
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning text-dark">
                                                                        <i class="fas fa-clock me-1"></i>
                                                                        Bekliyor
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Hızlı Gün Değiştirme Butonları -->
                                                        <div class="day-change-buttons">
                                                            @foreach(['monday' => 'Pzt', 'tuesday' => 'Sal', 'wednesday' => 'Çar', 'thursday' => 'Per', 'friday' => 'Cum', 'saturday' => 'Cmt', 'sunday' => 'Paz'] as $dKey => $dName)
                                                                <button type="button" 
                                                                        class="day-btn {{ $dKey == $dayKey ? 'active' : '' }}"
                                                                        onclick="moveToDay('{{ $program['schedule_item_id'] }}', '{{ $dKey }}', '{{ $dName }}', this)"
                                                                        {{ $dKey == $dayKey ? 'disabled' : '' }}>
                                                                    {{ $dName }}
                                                                </button>
                                                            @endforeach
                                                        </div>
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

/* Modern Program Card Stilleri */
.excel-program-item-quick {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
    margin-bottom: 8px;
    border: 2px solid transparent;
}

.excel-program-item-quick:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

/* Gün Değiştirme Butonları */
.day-change-buttons {
    padding: 8px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 4px;
    justify-content: space-between;
}

.day-btn {
    flex: 1;
    padding: 6px 4px;
    font-size: 11px;
    font-weight: 600;
    border: 2px solid #dee2e6;
    background: white;
    color: #6c757d;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
}

.day-btn:hover:not(:disabled) {
    background: #4e73df;
    border-color: #4e73df;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
}

.day-btn.active {
    background: #28a745;
    border-color: #28a745;
    color: white;
    cursor: default;
}

.day-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.day-btn.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.6;
}

.day-btn.loading::after {
    content: '⏳';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Program Header */
.program-header {
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
    position: relative;
}


.area-badge-modern {
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.5px;
    flex: 1;
    text-align: center;
}


/* Program Content */
.program-content {
    padding: 12px;
}

.course-title {
    font-size: 14px;
    margin-bottom: 8px;
    color: #2c3e50;
}

.topic-title {
    font-size: 13px;
    margin-bottom: 6px;
    color: #5a6c7d;
    padding-left: 8px;
}

.subtopic-title {
    font-size: 12px;
    margin-bottom: 6px;
    color: #7f8c8d;
    padding-left: 16px;
}

.program-notes {
    font-size: 11px;
    padding: 6px 8px;
    background: #f8f9fa;
    border-radius: 4px;
    margin-top: 8px;
}

.program-footer {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e9ecef;
}

/* Day Cell */
.day-cell {
    min-height: 120px;
    position: relative;
    transition: background-color 0.2s;
    vertical-align: top;
}

/* Loading State */
.excel-program-item-quick.updating {
    pointer-events: none;
    opacity: 0.6;
    position: relative;
}

.excel-program-item-quick.updating::after {
    content: '🔄';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Bildirimlerdeki animasyon */
.drag-mode-alert {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 1050;
    animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

@keyframes slideInRight {
    from {
        transform: translateX(120%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Dropdown iyileştirmeleri */
.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    padding: 4px;
}

.dropdown-item {
    border-radius: 4px;
    padding: 8px 12px;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: #e3f2fd;
    transform: translateX(4px);
}

.dropdown-item.active {
    background-color: #2196f3;
    color: white;
}


/* Responsive */
@media (max-width: 768px) {
    .excel-program-item-quick {
        font-size: 12px;
    }
    
    .course-title {
        font-size: 13px;
    }
    
    .program-header {
        padding: 6px 10px;
    }
    
    .quick-day-select {
        max-width: 70px;
        font-size: 10px;
    }
}
</style>

<script>
const studentId = {{ $student->id }};

// Dersi belirtilen güne taşı
function moveToDay(scheduleItemId, newDay, dayName, buttonElement) {
    console.log('📅 Ders taşınıyor:', scheduleItemId, '->', newDay);
    
    // Butonu loading durumuna al
    buttonElement.classList.add('loading');
    buttonElement.disabled = true;
    
    // Tüm butonları devre dışı bırak
    const allButtons = buttonElement.closest('.day-change-buttons').querySelectorAll('.day-btn');
    allButtons.forEach(btn => btn.disabled = true);
    
    // Backend'e güncelleme gönder
    fetch(`/admin/programs/student/${studentId}/schedule-items/update-day`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            schedule_item_id: scheduleItemId,
            day_of_week: newDay
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`✅ ${dayName} gününe taşındı!`, 'success');
            
            // Hızlı reload
            setTimeout(() => {
                window.location.reload();
            }, 600);
        } else {
            showToast('❌ Hata: ' + (data.message || 'Taşınamadı'), 'danger');
            // Butonları tekrar aktif et
            allButtons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('loading');
            });
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        showToast('❌ Bağlantı hatası!', 'danger');
        // Butonları tekrar aktif et
        allButtons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('loading');
        });
    });
}

// Küçük Toast Bildirimi (Minimal & Fast)
function showToast(message, type = 'info') {
    // Eski toast'ları kaldır
    const oldToasts = document.querySelectorAll('.quick-toast');
    oldToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} quick-toast`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 200px;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: slideInRight 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Auto close
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// Animasyon stilleri
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection
