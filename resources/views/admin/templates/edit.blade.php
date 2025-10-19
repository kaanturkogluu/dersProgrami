@extends('admin.layout')

@section('title', 'Şablon Düzenle')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>
                Şablon Düzenle: {{ $template->name }}
            </h1>
            <p class="text-muted mb-0">Program şablonunu düzenleyin</p>
        </div>
        <a href="{{ route('admin.templates.show', $template) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<form action="{{ route('admin.templates.update', $template) }}" method="POST" id="templateForm">
    @csrf
    @method('PUT')
    
    <!-- Temel Bilgiler -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Şablon Bilgileri</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Şablon Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $template->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $template->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alanlar <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="TYT" id="area_tyt" {{ in_array('TYT', old('areas', $template->areas)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_tyt">TYT</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="EA" id="area_ea" {{ in_array('EA', old('areas', $template->areas)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_ea">EA</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="SAY" id="area_say" {{ in_array('SAY', old('areas', $template->areas)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_say">SAY</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="SOZ" id="area_soz" {{ in_array('SOZ', old('areas', $template->areas)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_soz">SOZ</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="DIL" id="area_dil" {{ in_array('DIL', old('areas', $template->areas)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_dil">DIL</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="KPSS" id="area_kpss" {{ in_array('KPSS', old('areas', $template->areas)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_kpss">KPSS</label>
                        </div>
                    </div>
                </div>
                @error('areas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Program Öğeleri -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Program Öğeleri</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                    <i class="fas fa-bolt me-2"></i>
                    Hızlı Ekle
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="addScheduleItem()">
                    <i class="fas fa-plus me-2"></i>
                    Ders Ekle
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="15%">Gün</th>
                            <th width="25%">Ders</th>
                            <th width="20%">Konu</th>
                            <th width="20%">Alt Konu</th>
                            <th width="10%">Notlar</th>
                            <th width="5%">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleItems">
                        @if($template->schedule_items->count() > 0)
                            @foreach($template->schedule_items as $index => $item)
                            <tr class="schedule-item">
                                <td class="text-center">
                                    <span class="row-number">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <select class="form-select day-select" name="schedule_items[{{ $index }}][day_of_week]" required>
                                        <option value="">Gün Seçin</option>
                                        <option value="monday" {{ $item['day_of_week'] == 'monday' ? 'selected' : '' }}>Pazartesi</option>
                                        <option value="tuesday" {{ $item['day_of_week'] == 'tuesday' ? 'selected' : '' }}>Salı</option>
                                        <option value="wednesday" {{ $item['day_of_week'] == 'wednesday' ? 'selected' : '' }}>Çarşamba</option>
                                        <option value="thursday" {{ $item['day_of_week'] == 'thursday' ? 'selected' : '' }}>Perşembe</option>
                                        <option value="friday" {{ $item['day_of_week'] == 'friday' ? 'selected' : '' }}>Cuma</option>
                                        <option value="saturday" {{ $item['day_of_week'] == 'saturday' ? 'selected' : '' }}>Cumartesi</option>
                                        <option value="sunday" {{ $item['day_of_week'] == 'sunday' ? 'selected' : '' }}>Pazar</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select course-select" name="schedule_items[{{ $index }}][course_id]" required onchange="loadTopics(this)">
                                        <option value="">Ders Seçin</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" data-areas="{{ json_encode($course->areas) }}" {{ $item['course_id'] == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select topic-select" name="schedule_items[{{ $index }}][topic_id]" onchange="loadSubtopics(this)">
                                        <option value="">Konu Seçin</option>
                                        @if(isset($item['topic_id']) && $item['topic_id'])
                                            @php
                                                $topic = \App\Models\Topic::find($item['topic_id']);
                                            @endphp
                                            @if($topic)
                                                <option value="{{ $topic->id }}" selected>{{ $topic->name }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select subtopic-select" name="schedule_items[{{ $index }}][subtopic_id]">
                                        <option value="">Alt Konu Seçin</option>
                                        @if(isset($item['subtopic_id']) && $item['subtopic_id'])
                                            @php
                                                $subtopic = \App\Models\Subtopic::find($item['subtopic_id']);
                                            @endphp
                                            @if($subtopic)
                                                <option value="{{ $subtopic->id }}" selected>{{ $subtopic->name }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="schedule_items[{{ $index }}][notes]" value="{{ $item['notes'] ?? '' }}" placeholder="Notlar...">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleItem(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="empty-row">
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Henüz ders eklenmemiş. "Ders Ekle" butonuna tıklayarak başlayın.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.templates.show', $template) }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>
            İptal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>
            Değişiklikleri Kaydet
        </button>
    </div>
</form>

<!-- Hızlı Ders Ekleme Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickAddModalLabel">
                    <i class="fas fa-bolt me-2"></i>
                    Hızlı Ders Ekleme
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nasıl Kullanılır:</strong> Önce dersleri seçin, sonra hangi günlerde çalışılacağını belirleyin. 
                    Excel'de olduğu gibi önce tüm dersleri ekleyip sonra düzenleyebilirsiniz.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Dersleri Seçin:</h6>
                        <div id="courseCheckboxes" style="max-height: 300px; overflow-y: auto;">
                            @foreach($courses as $course)
                            <div class="form-check">
                                <input class="form-check-input course-checkbox" type="checkbox" 
                                       value="{{ $course->id }}" 
                                       data-course-name="{{ $course->name }}" 
                                       data-course-category="{{ $course->category->name }}"
                                       id="course_{{ $course->id }}">
                                <label class="form-check-label" for="course_{{ $course->id }}">
                                    {{ $course->name }} <small class="text-muted">({{ $course->category->name }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Günleri Seçin:</h6>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="monday" id="day_monday">
                            <label class="form-check-label" for="day_monday">Pazartesi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="tuesday" id="day_tuesday">
                            <label class="form-check-label" for="day_tuesday">Salı</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="wednesday" id="day_wednesday">
                            <label class="form-check-label" for="day_wednesday">Çarşamba</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="thursday" id="day_thursday">
                            <label class="form-check-label" for="day_thursday">Perşembe</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="friday" id="day_friday">
                            <label class="form-check-label" for="day_friday">Cuma</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="saturday" id="day_saturday">
                            <label class="form-check-label" for="day_saturday">Cumartesi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" value="sunday" id="day_sunday">
                            <label class="form-check-label" for="day_sunday">Pazar</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Önizleme:</h6>
                    <div id="previewItems" class="border rounded p-2" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                        <small class="text-muted">Ders ve gün seçin...</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="addQuickItems()">
                    <i class="fas fa-plus me-2"></i>
                    Seçilenleri Ekle
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Program Öğesi Template - Excel Satırı -->
<template id="scheduleItemTemplate">
    <tr class="schedule-item">
        <td class="text-center">
            <span class="row-number">1</span>
        </td>
        <td>
            <select class="form-select day-select" name="schedule_items[INDEX][day_of_week]" required>
                <option value="">Gün Seçin</option>
                <option value="monday">Pazartesi</option>
                <option value="tuesday">Salı</option>
                <option value="wednesday">Çarşamba</option>
                <option value="thursday">Perşembe</option>
                <option value="friday">Cuma</option>
                <option value="saturday">Cumartesi</option>
                <option value="sunday">Pazar</option>
            </select>
        </td>
        <td>
            <select class="form-select course-select" name="schedule_items[INDEX][course_id]" required onchange="loadTopics(this)">
                <option value="">Ders Seçin</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" data-areas="{{ json_encode($course->areas) }}">
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-select topic-select" name="schedule_items[INDEX][topic_id]" onchange="loadSubtopics(this)">
                <option value="">Konu Seçin</option>
            </select>
        </td>
        <td>
            <select class="form-select subtopic-select" name="schedule_items[INDEX][subtopic_id]">
                <option value="">Alt Konu Seçin</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="schedule_items[INDEX][notes]" placeholder="Notlar...">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let scheduleItemIndex = {{ $template->schedule_items->count() }};

// Alan değişikliğinde dersleri filtrele
document.querySelectorAll('.area-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        filterCoursesByArea();
    });
});

// Dersleri alana göre filtrele
function filterCoursesByArea() {
    const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
    const courseSelects = document.querySelectorAll('.course-select');
    
    courseSelects.forEach(select => {
        const options = select.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const courseAreas = JSON.parse(option.getAttribute('data-areas') || '[]');
            const hasMatchingArea = selectedAreas.some(area => courseAreas.includes(area));
            
            option.style.display = hasMatchingArea ? 'block' : 'none';
        });
    });
}

// Alert gösterme fonksiyonu
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Sayfanın üstüne ekle
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // 5 saniye sonra otomatik kapat
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function addScheduleItem() {
    const template = document.getElementById('scheduleItemTemplate');
    const clone = template.content.cloneNode(true);
    
    // Index'i güncelle
    const html = clone.querySelector('.schedule-item').outerHTML;
    const updatedHtml = html.replace(/INDEX/g, scheduleItemIndex);
    
    const tbody = document.getElementById('scheduleItems');
    
    // Boş satırı gizle
    const emptyRow = tbody.querySelector('.empty-row');
    if (emptyRow) {
        emptyRow.style.display = 'none';
    }
    
    tbody.insertAdjacentHTML('beforeend', updatedHtml);
    
    // Yeni eklenen satırı bul ve satır numarasını güncelle
    const newRow = tbody.querySelector('.schedule-item:last-child');
    const rowNumber = newRow.querySelector('.row-number');
    rowNumber.textContent = scheduleItemIndex + 1;
    
    // Dersleri filtrele
    filterCoursesByArea();
    
    scheduleItemIndex++;
}

// Konuları yükle
function loadTopics(courseSelect) {
    const courseId = courseSelect.value;
    const scheduleItem = courseSelect.closest('.schedule-item');
    const topicSelect = scheduleItem.querySelector('.topic-select');
    const subtopicSelect = scheduleItem.querySelector('.subtopic-select');
    
    // Alt konu seçimini sıfırla
    subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
    
    if (!courseId) {
        topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
        return;
    }
    
    // Konuları yükle
    fetch(`{{ route('admin.schedules.topics.by-course') }}?course_id=${courseId}`)
        .then(response => response.json())
        .then(data => {
            topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
            if (data.topics && data.topics.length > 0) {
                data.topics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = `${topic.name} (${topic.duration_minutes} dk)`;
                    topicSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading topics:', error);
        });
}

// Alt konuları yükle
function loadSubtopics(topicSelect) {
    const topicId = topicSelect.value;
    const scheduleItem = topicSelect.closest('.schedule-item');
    const subtopicSelect = scheduleItem.querySelector('.subtopic-select');
    
    if (!topicId) {
        subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
        return;
    }
    
    fetch(`{{ route('admin.schedules.subtopics.by-topic') }}?topic_id=${topicId}`)
        .then(response => response.json())
        .then(data => {
            subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
            if (data.subtopics && data.subtopics.length > 0) {
                data.subtopics.forEach(subtopic => {
                    const option = document.createElement('option');
                    option.value = subtopic.id;
                    option.textContent = `${subtopic.name} (${subtopic.duration_minutes} dk)`;
                    subtopicSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading subtopics:', error);
        });
}

// Satır silme
function removeScheduleItem(button) {
    const scheduleItem = button.closest('.schedule-item');
    scheduleItem.remove();
    
    // Satır numaralarını güncelle
    updateRowNumbers();
    
    // Eğer hiç satır kalmadıysa boş satırı göster
    const tbody = document.getElementById('scheduleItems');
    const remainingItems = tbody.querySelectorAll('.schedule-item');
    if (remainingItems.length === 0) {
        const emptyRow = tbody.querySelector('.empty-row');
        if (emptyRow) {
            emptyRow.style.display = 'table-row';
        }
    }
}

// Satır numaralarını güncelle
function updateRowNumbers() {
    const scheduleItems = document.querySelectorAll('.schedule-item');
    scheduleItems.forEach((item, index) => {
        const rowNumber = item.querySelector('.row-number');
        rowNumber.textContent = index + 1;
    });
}

// Form gönderilmeden önce validasyon
document.getElementById('templateForm').addEventListener('submit', function(e) {
    const selectedAreas = document.querySelectorAll('.area-checkbox:checked');
    const scheduleItems = document.querySelectorAll('.schedule-item');
    
    if (selectedAreas.length === 0) {
        e.preventDefault();
        showAlert('danger', 'Lütfen en az bir alan seçin.');
        return;
    }
    
    if (scheduleItems.length === 0) {
        e.preventDefault();
        showAlert('danger', 'Lütfen en az bir ders ekleyin.');
        return;
    }
    
    // Her satırda gün ve ders seçili mi kontrol et
    for (let i = 0; i < scheduleItems.length; i++) {
        const row = scheduleItems[i];
        const daySelect = row.querySelector('.day-select');
        const courseSelect = row.querySelector('.course-select');
        
        if (!daySelect.value) {
            e.preventDefault();
            showAlert('danger', `${i + 1}. satırda gün seçimi yapılmamış.`);
            return;
        }
        
        if (!courseSelect.value) {
            e.preventDefault();
            showAlert('danger', `${i + 1}. satırda ders seçimi yapılmamış.`);
            return;
        }
    }
});

// Sayfa yüklendiğinde dersleri filtrele
document.addEventListener('DOMContentLoaded', function() {
    filterCoursesByArea();
    
    // Hızlı ekleme modalı için event listener'lar
    setupQuickAddModal();
});

// Hızlı ekleme modalı için event listener'ları ayarla
function setupQuickAddModal() {
    // Ders seçimi değiştiğinde önizlemeyi güncelle
    document.querySelectorAll('.course-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateQuickAddPreview);
    });
    
    // Gün seçimi değiştiğinde önizlemeyi güncelle
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateQuickAddPreview);
    });
}

// Hızlı ekleme önizlemesini güncelle
function updateQuickAddPreview() {
    const selectedCourses = Array.from(document.querySelectorAll('.course-checkbox:checked'));
    const selectedDays = Array.from(document.querySelectorAll('.day-checkbox:checked'));
    const previewContainer = document.getElementById('previewItems');
    
    if (selectedCourses.length === 0 || selectedDays.length === 0) {
        previewContainer.innerHTML = '<small class="text-muted">Ders ve gün seçin...</small>';
        return;
    }
    
    let previewHtml = '';
    selectedCourses.forEach(course => {
        const courseName = course.getAttribute('data-course-name');
        const courseCategory = course.getAttribute('data-course-category');
        
        selectedDays.forEach(day => {
            const dayName = getDayName(day.value);
            previewHtml += `
                <div class="d-flex justify-content-between align-items-center mb-1 p-1 border-bottom">
                    <span><strong>${dayName}</strong> - ${courseName} <small class="text-muted">(${courseCategory})</small></span>
                </div>
            `;
        });
    });
    
    previewContainer.innerHTML = previewHtml;
}

// Gün adını Türkçe'ye çevir
function getDayName(dayValue) {
    const dayNames = {
        'monday': 'Pazartesi',
        'tuesday': 'Salı',
        'wednesday': 'Çarşamba',
        'thursday': 'Perşembe',
        'friday': 'Cuma',
        'saturday': 'Cumartesi',
        'sunday': 'Pazar'
    };
    return dayNames[dayValue] || dayValue;
}

// Hızlı ekleme ile seçilen dersleri ekle
function addQuickItems() {
    const selectedCourses = Array.from(document.querySelectorAll('.course-checkbox:checked'));
    const selectedDays = Array.from(document.querySelectorAll('.day-checkbox:checked'));
    
    if (selectedCourses.length === 0) {
        showAlert('warning', 'Lütfen en az bir ders seçin.');
        return;
    }
    
    if (selectedDays.length === 0) {
        showAlert('warning', 'Lütfen en az bir gün seçin.');
        return;
    }
    
    // Her ders için her gün için satır ekle
    selectedCourses.forEach(course => {
        const courseId = course.value;
        const courseName = course.getAttribute('data-course-name');
        
        selectedDays.forEach(day => {
            const dayValue = day.value;
            const dayName = getDayName(dayValue);
            
            // Satır ekle
            addQuickScheduleItem(courseId, dayValue, courseName, dayName);
        });
    });
    
    // Modal'ı kapat
    const modal = bootstrap.Modal.getInstance(document.getElementById('quickAddModal'));
    modal.hide();
    
    // Seçimleri temizle
    clearQuickAddSelections();
    
    showAlert('success', `${selectedCourses.length} ders, ${selectedDays.length} gün için toplam ${selectedCourses.length * selectedDays.length} satır eklendi.`);
}

// Hızlı ekleme için satır ekle
function addQuickScheduleItem(courseId, dayValue, courseName, dayName) {
    const template = document.getElementById('scheduleItemTemplate');
    const clone = template.content.cloneNode(true);
    
    // Index'i güncelle
    const html = clone.querySelector('.schedule-item').outerHTML;
    const updatedHtml = html.replace(/INDEX/g, scheduleItemIndex);
    
    const tbody = document.getElementById('scheduleItems');
    
    // Boş satırı gizle
    const emptyRow = tbody.querySelector('.empty-row');
    if (emptyRow) {
        emptyRow.style.display = 'none';
    }
    
    tbody.insertAdjacentHTML('beforeend', updatedHtml);
    
    // Yeni eklenen satırı bul ve değerleri ayarla
    const newRow = tbody.querySelector('.schedule-item:last-child');
    const daySelect = newRow.querySelector('.day-select');
    const courseSelect = newRow.querySelector('.course-select');
    
    // Değerleri ayarla
    daySelect.value = dayValue;
    courseSelect.value = courseId;
    
    // Satır numarasını güncelle
    const rowNumber = newRow.querySelector('.row-number');
    rowNumber.textContent = scheduleItemIndex + 1;
    
    // Dersleri filtrele
    filterCoursesByArea();
    
    scheduleItemIndex++;
}

// Hızlı ekleme seçimlerini temizle
function clearQuickAddSelections() {
    document.querySelectorAll('.course-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    updateQuickAddPreview();
}
</script>
@endsection