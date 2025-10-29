@extends('admin.layout')

@section('title', $student->full_name . ' - Program Düzenle')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>
                {{ $student->full_name }} - Program Düzenle
            </h1>
            <p class="text-muted mb-0">{{ $student->student_number }} - Excel Formatında Düzenleme</p>
        </div>
        <div>
            <a href="{{ route('admin.programs.student.calendar', $student) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Takvime Dön
            </a>
            <button type="submit" form="calendar-edit-form" class="btn btn-success">
                <i class="fas fa-save me-2"></i>
                Değişiklikleri Kaydet
            </button>
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
                    <h5 class="card-title">Düzenleme Bilgileri</h5>
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
                                <div class="stats-label">Düzenlenebilir Ders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Excel Formatında Düzenleme Formu -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                Excel Formatında Program Düzenleme
            </h5>
            <small class="text-muted">Selectbox'lar ile mevcut yapıyı güncelleyebilirsiniz</small>
        </div>
        <div class="card-body p-0">
            @if($weeklySchedule->count() > 0)
                <form id="calendar-edit-form" action="{{ route('admin.programs.student.schedule-items.update', $student) }}" method="POST">
                    @method('PUT')
                    @csrf
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
                                            <th class="day-header text-center">{{ $dayName }}</th>
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
                                                        
                                                        <div class="excel-program-item-edit">
                                                            <!-- Hidden ID -->
                                                            <input type="hidden" name="schedule_items[{{ $program['id'] }}][id]" value="{{ $program['id'] }}">
                                                            
                                                            <!-- Ders Seçimi -->
                                                            <div class="excel-course-row-edit">
                                                                <select name="schedule_items[{{ $program['id'] }}][course_id]" class="form-select form-select-sm course-select" required>
                                                                    <option value="">Ders Seçin</option>
                                                                    @foreach($courses as $course)
                                                                        <option value="{{ $course->id }}" 
                                                                                {{ $program['course']->id == $course->id ? 'selected' : '' }}
                                                                                data-category="{{ $course->category->name }}">
                                                                                {{ $course->category->name }} - {{ $course->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="area-badge badge bg-{{ $program['area'] == 'TYT' ? 'primary' : ($program['area'] == 'AYT' ? 'success' : ($program['area'] == 'KPSS' ? 'warning' : ($program['area'] == 'DGS' ? 'info' : 'secondary'))) }}">
                                                                    {{ $program['area'] }}
                                                                </span>
                                                            </div>
                                                            
                                                            <!-- Konu Seçimi -->
                                                            <div class="excel-topic-row-edit">
                                                                <select name="schedule_items[{{ $program['id'] }}][topic_id]" class="form-select form-select-sm topic-select">
                                                                    <option value="">Konu Seçin</option>
                                                                    @foreach($topics->where('course_id', $program['course']->id) as $topic)
                                                                        <option value="{{ $topic->id }}" 
                                                                                {{ $program['topic'] && $program['topic']->id == $topic->id ? 'selected' : '' }}>
                                                                            {{ $topic->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            
                                                            <!-- Alt Konu Seçimi -->
                                                            <div class="excel-subtopic-row-edit">
                                                                <select name="schedule_items[{{ $program['id'] }}][subtopic_id]" class="form-select form-select-sm subtopic-select">
                                                                    <option value="">Alt Konu Seçin</option>
                                                                    @if($program['topic'])
                                                                        @foreach($subtopics->where('topic_id', $program['topic']->id) as $subtopic)
                                                                            <option value="{{ $subtopic->id }}" 
                                                                                    {{ $program['subtopic'] && $program['subtopic']->id == $subtopic->id ? 'selected' : '' }}>
                                                                                {{ $subtopic->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            
                                                            <!-- Notlar -->
                                                            <div class="excel-notes-row-edit">
                                                                <textarea name="schedule_items[{{ $program['id'] }}][notes]" 
                                                                          class="form-control form-control-sm notes-textarea" 
                                                                          rows="2" 
                                                                          placeholder="Notlar...">{{ $program['notes'] }}</textarea>
                                                            </div>
                                                            
                                                            <!-- Durum ve İşlemler -->
                                                            <div class="excel-status-row-edit">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" 
                                                                               name="schedule_items[{{ $program['id'] }}][is_completed]" 
                                                                               value="1" 
                                                                               id="completed_{{ $program['id'] }}"
                                                                               {{ $program['is_completed'] ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="completed_{{ $program['id'] }}">
                                                                            <small>Tamamlandı</small>
                                                                        </label>
                                                                    </div>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-lesson-btn" 
                                                                            onclick="removeLesson({{ $program['id'] }})"
                                                                            title="Bu dersi kaldır">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="excel-empty-cell">
                                                            <div class="dynamic-lesson-form" id="form-{{ $dayKey }}-{{ $row }}" style="display: none;">
                                                                <!-- Hidden fields for new lesson -->
                                                                <input type="hidden" name="new_lessons[{{ $dayKey }}][{{ $row }}][day_of_week]" value="{{ $dayKey }}">
                                                                <input type="hidden" name="new_lessons[{{ $dayKey }}][{{ $row }}][row_index]" value="{{ $row }}">
                                                                
                                                                <!-- Ders Seçimi -->
                                                                <div class="mb-2">
                                                                    <select name="new_lessons[{{ $dayKey }}][{{ $row }}][course_id]" 
                                                                            class="form-select form-select-sm course-select-new" required>
                                                                        <option value="">Ders Seçin</option>
                                                                        @foreach($courses as $course)
                                                                            <option value="{{ $course->id }}" 
                                                                                    data-category="{{ $course->category->name }}">
                                                                                {{ $course->category->name }} - {{ $course->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                                <!-- Konu Seçimi -->
                                                                <div class="mb-2">
                                                                    <select name="new_lessons[{{ $dayKey }}][{{ $row }}][topic_id]" 
                                                                            class="form-select form-select-sm topic-select-new">
                                                                        <option value="">Konu Seçin</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <!-- Alt Konu Seçimi -->
                                                                <div class="mb-2">
                                                                    <select name="new_lessons[{{ $dayKey }}][{{ $row }}][subtopic_id]" 
                                                                            class="form-select form-select-sm subtopic-select-new">
                                                                        <option value="">Alt Konu Seçin</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <!-- Notlar -->
                                                                <div class="mb-2">
                                                                    <textarea name="new_lessons[{{ $dayKey }}][{{ $row }}][notes]" 
                                                                              class="form-control form-control-sm" 
                                                                              rows="2" 
                                                                              placeholder="Notlar..."></textarea>
                                                                </div>
                                                                
                                                                <!-- Butonlar -->
                                                                <div class="d-flex gap-1">
                                                                    <button type="button" class="btn btn-success btn-sm flex-fill" 
                                                                            onclick="saveNewLesson('{{ $dayKey }}', {{ $row }})">
                                                                        <i class="fas fa-save"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-secondary btn-sm flex-fill" 
                                                                            onclick="cancelNewLesson('{{ $dayKey }}', {{ $row }})">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            
                                                            <button type="button" class="btn btn-outline-primary btn-sm add-lesson-btn" 
                                                                    onclick="showNewLessonForm('{{ $dayKey }}', {{ $row }})"
                                                                    title="Bu güne ders ekle">
                                                                <i class="fas fa-plus me-1"></i>
                                                                Ders Ekle
                                                            </button>
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
                </form>
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
    min-height: 120px;
    width: 14.28%; /* 100% / 7 gün */
}

.program-row:hover td {
    background-color: #f6f8fa;
}

.day-cell {
    position: relative;
    min-height: 120px;
}

/* Excel Program İçeriği - Düzenleme */
.excel-program-item-edit {
    padding: 0.25rem;
    background-color: #f8f9fa;
    border: 1px solid #e1e4e8;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
    line-height: 1.3;
}

.excel-program-item-edit:hover {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

/* Ders Satırı - Düzenleme */
.excel-course-row-edit {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.25rem;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid #e1e4e8;
}

.course-select {
    flex: 1;
    margin-right: 0.5rem;
    font-size: 0.75rem;
}

.area-badge {
    font-size: 0.65rem;
    padding: 0.15rem 0.4rem;
    border-radius: 0.2rem;
    font-weight: 500;
}

/* Konu Satırı - Düzenleme */
.excel-topic-row-edit {
    margin-bottom: 0.15rem;
}

.topic-select {
    font-size: 0.7rem;
    width: 100%;
}

/* Alt Konu Satırı - Düzenleme */
.excel-subtopic-row-edit {
    margin-bottom: 0.15rem;
}

.subtopic-select {
    font-size: 0.7rem;
    width: 100%;
}

/* Notlar Satırı - Düzenleme */
.excel-notes-row-edit {
    margin-top: 0.25rem;
    padding-top: 0.25rem;
    border-top: 1px solid #e1e4e8;
}

.notes-textarea {
    font-size: 0.7rem;
    width: 100%;
    resize: vertical;
    min-height: 40px;
}

/* Durum Satırı - Düzenleme */
.excel-status-row-edit {
    text-align: right;
    margin-top: 0.25rem;
}

.form-check-input {
    transform: scale(0.8);
}

.form-check-label {
    font-size: 0.7rem;
}

/* Boş Hücre */
.excel-empty-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 120px;
    color: #6a737d;
}

.empty-text {
    font-size: 0.8rem;
    font-style: italic;
}

.add-lesson-btn {
    width: 100%;
    height: 100%;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed #dee2e6;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.add-lesson-btn:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
    color: #007bff;
}

.remove-lesson-btn {
    min-width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.excel-program-item-edit {
    transition: all 0.3s ease;
}

.excel-program-item-edit.deleting {
    opacity: 0.3;
    pointer-events: none;
}

.dynamic-lesson-form {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 10px;
    margin: 5px 0;
}

.dynamic-lesson-form .form-select,
.dynamic-lesson-form .form-control {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

.dynamic-lesson-form .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.dynamic-lesson-form .flex-fill {
    flex: 1;
}

.dynamic-lesson-form .gap-1 {
    gap: 0.25rem;
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
    
    .course-select {
        font-size: 0.7rem;
    }
    
    .topic-select, .subtopic-select {
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
        min-height: 100px;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Course değiştiğinde topic'leri güncelle
    document.querySelectorAll('.course-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const courseId = this.value;
            const programId = this.name.match(/\[(\d+)\]/)[1];
            const topicSelect = document.querySelector(`select[name="schedule_items[${programId}][topic_id]"]`);
            const subtopicSelect = document.querySelector(`select[name="schedule_items[${programId}][subtopic_id]"]`);
            
            // Topic select'i temizle
            topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
            subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
            
            if (courseId) {
                // AJAX ile topic'leri getir
                fetch(`/admin/schedules/topics/by-course?course_id=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.topics.forEach(function(topic) {
                            const option = document.createElement('option');
                            option.value = topic.id;
                            option.textContent = topic.name;
                            topicSelect.appendChild(option);
                        });
                    });
            }
        });
    });
    
    // Topic değiştiğinde subtopic'leri güncelle
    document.querySelectorAll('.topic-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const topicId = this.value;
            const programId = this.name.match(/\[(\d+)\]/)[1];
            const subtopicSelect = document.querySelector(`select[name="schedule_items[${programId}][subtopic_id]"]`);
            
            // Subtopic select'i temizle
            subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
            
            if (topicId) {
                // AJAX ile subtopic'leri getir
                fetch(`/admin/schedules/subtopics/by-topic?topic_id=${topicId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.subtopics.forEach(function(subtopic) {
                            const option = document.createElement('option');
                            option.value = subtopic.id;
                            option.textContent = subtopic.name;
                            subtopicSelect.appendChild(option);
                        });
                    });
            }
        });
    });
    
    // Yeni ders formları için course değişikliği
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('course-select-new')) {
            const courseId = e.target.value;
            const form = e.target.closest('.dynamic-lesson-form');
            const topicSelect = form.querySelector('.topic-select-new');
            const subtopicSelect = form.querySelector('.subtopic-select-new');
            
            // Topic select'i temizle
            topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
            subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
            
            if (courseId) {
                // AJAX ile topic'leri getir
                fetch(`/admin/schedules/topics/by-course?course_id=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.topics.forEach(function(topic) {
                            const option = document.createElement('option');
                            option.value = topic.id;
                            option.textContent = topic.name;
                            topicSelect.appendChild(option);
                        });
                    });
            }
        }
        
        if (e.target.classList.contains('topic-select-new')) {
            const topicId = e.target.value;
            const form = e.target.closest('.dynamic-lesson-form');
            const subtopicSelect = form.querySelector('.subtopic-select-new');
            
            // Subtopic select'i temizle
            subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
            
            if (topicId) {
                // AJAX ile subtopic'leri getir
                fetch(`/admin/schedules/subtopics/by-topic?topic_id=${topicId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.subtopics.forEach(function(subtopic) {
                            const option = document.createElement('option');
                            option.value = subtopic.id;
                            option.textContent = subtopic.name;
                            subtopicSelect.appendChild(option);
                        });
                    });
            }
        }
    });
    
    // Yeni ders formu gösterme
    window.showNewLessonForm = function(dayKey, row) {
        const formId = `form-${dayKey}-${row}`;
        const form = document.getElementById(formId);
        
        if (form) {
            // Buton'u bul - form'un parent'ında arama yap
            const parent = form.parentElement;
            const button = parent.querySelector('.add-lesson-btn');
            
            if (button) {
                form.style.display = 'block';
                button.style.display = 'none';
            }
        }
    };
    
    // Yeni ders formu iptal etme
    window.cancelNewLesson = function(dayKey, row) {
        const formId = `form-${dayKey}-${row}`;
        const form = document.getElementById(formId);
        
        if (form) {
            // Buton'u bul - form'un parent'ında arama yap
            const parent = form.parentElement;
            const button = parent.querySelector('.add-lesson-btn');
            
            if (button) {
                form.style.display = 'none';
                button.style.display = 'block';
                
                // Form alanlarını temizle
                form.querySelectorAll('select, textarea').forEach(field => {
                    field.value = '';
                });
            }
        }
    };
    
    // Yeni ders kaydetme
    window.saveNewLesson = function(dayKey, row) {
        const formId = `form-${dayKey}-${row}`;
        const form = document.getElementById(formId);
        
        if (!form) return;
        
        const courseSelect = form.querySelector('.course-select-new');
        const topicSelect = form.querySelector('.topic-select-new');
        const subtopicSelect = form.querySelector('.subtopic-select-new');
        const notesTextarea = form.querySelector('textarea');
        
        // Validation
        if (!courseSelect.value) {
            alert('Lütfen bir ders seçin.');
            return;
        }
        
        // Form verilerini topla
        const formData = {
            day_of_week: dayKey,
            row_index: row,
            course_id: courseSelect.value,
            topic_id: topicSelect.value,
            subtopic_id: subtopicSelect.value,
            notes: notesTextarea.value
        };
        
        // AJAX ile kaydet
        fetch('{{ route("admin.programs.student.schedule-items.create", $student) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Sayfayı yenile
                window.location.reload();
            } else {
                alert('Hata: ' + (data.message || 'Ders eklenirken bir hata oluştu.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ders eklenirken bir hata oluştu.');
        });
    };
    
    // Ders kaldırma fonksiyonu
    window.removeLesson = function(programId) {
        if (confirm('Bu dersi programdan kaldırmak istediğinizden emin misiniz?')) {
            // Dersi gizle (silme işlemi için)
            const programElement = document.querySelector(`input[name="schedule_items[${programId}][id]"]`).closest('.excel-program-item-edit');
            if (programElement) {
                // Silme işareti ekle
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `schedule_items[${programId}][_delete]`;
                hiddenInput.value = '1';
                programElement.appendChild(hiddenInput);
                
                // Görsel olarak gizle
                programElement.style.opacity = '0.3';
                programElement.style.pointerEvents = 'none';
                
                // Kaldır butonunu değiştir
                const removeBtn = programElement.querySelector('.remove-lesson-btn');
                if (removeBtn) {
                    removeBtn.innerHTML = '<i class="fas fa-undo"></i>';
                    removeBtn.title = 'Geri al';
                    removeBtn.onclick = function() {
                        restoreLesson(programId);
                    };
                }
            }
        }
    };
    
    // Ders geri yükleme fonksiyonu
    window.restoreLesson = function(programId) {
        const programElement = document.querySelector(`input[name="schedule_items[${programId}][id]"]`).closest('.excel-program-item-edit');
        if (programElement) {
            // Silme işaretini kaldır
            const deleteInput = programElement.querySelector(`input[name="schedule_items[${programId}][_delete]"]`);
            if (deleteInput) {
                deleteInput.remove();
            }
            
            // Görsel olarak geri yükle
            programElement.style.opacity = '1';
            programElement.style.pointerEvents = 'auto';
            
            // Kaldır butonunu geri yükle
            const removeBtn = programElement.querySelector('.remove-lesson-btn');
            if (removeBtn) {
                removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
                removeBtn.title = 'Bu dersi kaldır';
                removeBtn.onclick = function() {
                    removeLesson(programId);
                };
            }
        }
    };
});
</script>
@endsection
