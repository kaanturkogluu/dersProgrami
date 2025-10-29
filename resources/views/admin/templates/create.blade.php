@extends('admin.layout')

@section('title', 'Yeni Program Şablonu')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-copy me-2"></i>
                Yeni Program Şablonu
            </h1>
            <p class="text-muted mb-0">Program şablonu oluşturun ve daha sonra yeni programlarda kullanın</p>
        </div>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<form action="{{ route('admin.templates.store') }}" method="POST" id="templateForm">
    @csrf
    
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
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alanlar <span class="text-danger">*</span></label>
                <div class="row">
                    @foreach($categories as $category)
                    <div class="col-md-4 col-lg-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input area-checkbox" type="checkbox" name="areas[]" value="{{ $category->name }}" id="area_{{ $category->id }}" {{ in_array($category->name, old('areas', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_{{ $category->id }}">
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <br><small class="text-muted">{{ $category->description }}</small>
                                @endif
                            </label>
                        </div>
                    </div>
                    @endforeach
                    @if($categories->count() == 0)
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Henüz kategori oluşturulmamış. Lütfen önce kategori oluşturun.
                        </div>
                    </div>
                    @endif
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
                            <th width="15%">Notlar</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleItems">
                        <tr class="empty-row">
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-plus-circle me-2"></i>
                                Henüz ders eklenmemiş. "Ders Ekle" butonuna tıklayarak başlayın.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>
            İptal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>
            Şablonu Kaydet
        </button>
    </div>
</form>

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
                        {{ $course->category->name }} - {{ $course->name }}
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
    </tr>
</template>

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
                                    <strong>{{ $course->category->name }}</strong> - {{ $course->name }}
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

<!-- Ders Seçimi Modal -->
<div class="modal fade" id="courseSelectionModal" tabindex="-1" aria-labelledby="courseSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseSelectionModalLabel">
                    <i class="fas fa-book me-2"></i>
                    Ders Seçimi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Ders Seçimi:</strong> Seçili alanlara uygun dersleri görebilir ve toplu olarak ekleyebilirsiniz.
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Seçili Alanlar:</label>
                    <div id="selectedAreasDisplay" class="text-muted">-</div>
                </div>
                
                <div class="mb-3">
                    <label for="courseFilter" class="form-label">Ders Filtrele:</label>
                    <input type="text" class="form-control" id="courseFilter" placeholder="Ders adında ara...">
                </div>
                
                <div class="row" id="courseSelectionGrid">
                    <!-- Dersler buraya yüklenecek -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    İptal
                </button>
                <button type="button" class="btn btn-primary" onclick="addSelectedCourses()">
                    <i class="fas fa-plus me-2"></i>
                    Seçilenleri Ekle
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let scheduleItemIndex = 0;

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