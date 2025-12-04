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
                            <th width="10%">Notlar</th>
                            <th width="5%">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleItems">
                        <tr class="empty-row">
                            <td colspan="7" class="text-center text-muted py-4">
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
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleItem(this)">
                <i class="fas fa-trash"></i>
            </button>
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
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="quick-add-section">
                            <h6 class="section-title">
                                <i class="fas fa-book me-2"></i>
                                Dersleri Seçin
                            </h6>
                            <div id="courseCheckboxes" class="course-checkboxes-container">
                                @php
                                    // Dersleri alanlara göre grupla
                                    $coursesByCategory = $courses->groupBy(function($course) {
                                        return $course->category->name;
                                    });
                                @endphp
                                @foreach($coursesByCategory as $categoryName => $categoryCourses)
                                <div class="course-group mb-3" data-category="{{ $categoryName }}">
                                    <div class="course-group-header">
                                        <strong class="text-primary">
                                            <i class="fas fa-folder-open me-2"></i>
                                            {{ $categoryName }}
                                        </strong>
                                        <span class="badge bg-secondary course-count">{{ $categoryCourses->count() }} ders</span>
                                    </div>
                                    <div class="course-list">
                                        @foreach($categoryCourses as $course)
                                        <div class="form-check course-checkbox-item">
                                            <input class="form-check-input course-checkbox" type="checkbox" 
                                                   value="{{ $course->id }}" 
                                                   data-course-name="{{ $course->name }}" 
                                                   data-course-category="{{ $course->category->name }}"
                                                   data-course-areas="{{ json_encode($course->areas) }}"
                                                   id="quick_course_{{ $course->id }}">
                                            <label class="form-check-label" for="quick_course_{{ $course->id }}">
                                                {{ $course->name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="quick-add-section">
                            <h6 class="section-title">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Günleri Seçin
                            </h6>
                            <div class="days-container">
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="monday" id="day_monday">
                                    <label class="form-check-label" for="day_monday">
                                        <i class="fas fa-circle text-primary me-2"></i>Pazartesi
                                    </label>
                                </div>
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="tuesday" id="day_tuesday">
                                    <label class="form-check-label" for="day_tuesday">
                                        <i class="fas fa-circle text-success me-2"></i>Salı
                                    </label>
                                </div>
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="wednesday" id="day_wednesday">
                                    <label class="form-check-label" for="day_wednesday">
                                        <i class="fas fa-circle text-warning me-2"></i>Çarşamba
                                    </label>
                                </div>
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="thursday" id="day_thursday">
                                    <label class="form-check-label" for="day_thursday">
                                        <i class="fas fa-circle text-danger me-2"></i>Perşembe
                                    </label>
                                </div>
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="friday" id="day_friday">
                                    <label class="form-check-label" for="day_friday">
                                        <i class="fas fa-circle text-info me-2"></i>Cuma
                                    </label>
                                </div>
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="saturday" id="day_saturday">
                                    <label class="form-check-label" for="day_saturday">
                                        <i class="fas fa-circle text-secondary me-2"></i>Cumartesi
                                    </label>
                                </div>
                                <div class="form-check day-item">
                                    <input class="form-check-input day-checkbox" type="checkbox" value="sunday" id="day_sunday">
                                    <label class="form-check-label" for="day_sunday">
                                        <i class="fas fa-circle text-dark me-2"></i>Pazar
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="section-title">
                        <i class="fas fa-eye me-2"></i>
                        Önizleme
                    </h6>
                    <div id="previewItems" class="preview-container">
                        <div class="preview-placeholder">
                            <i class="fas fa-info-circle me-2"></i>
                            <small class="text-muted">Ders ve gün seçin...</small>
                        </div>
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
    
    // Dropdown select'lerdeki dersleri filtrele
    const courseSelects = document.querySelectorAll('.course-select');
    courseSelects.forEach(select => {
        const options = select.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            try {
                const courseAreas = JSON.parse(option.getAttribute('data-areas') || '[]');
                if (!Array.isArray(courseAreas) || courseAreas === null) {
                    option.style.display = selectedAreas.length === 0 ? 'block' : 'none';
                    return;
                }
                
                const hasMatchingArea = selectedAreas.length === 0 || selectedAreas.some(area => courseAreas.includes(area));
                option.style.display = hasMatchingArea ? 'block' : 'none';
            } catch (e) {
                console.error('Error parsing course areas:', e);
                option.style.display = selectedAreas.length === 0 ? 'block' : 'none';
            }
        });
    });
    
    // Hızlı ekleme modalındaki checkbox'ları filtrele
    filterQuickAddCourses(selectedAreas);
}

// Hızlı ekleme modalındaki dersleri filtrele
function filterQuickAddCourses(selectedAreas) {
    const modal = document.getElementById('quickAddModal');
    if (!modal) return;
    
    const courseGroups = modal.querySelectorAll('.course-group');
    courseGroups.forEach(group => {
        const categoryName = group.getAttribute('data-category');
        const courseCheckboxes = group.querySelectorAll('.course-checkbox');
        let visibleCount = 0;
        
        courseCheckboxes.forEach(checkbox => {
            const courseCategory = checkbox.getAttribute('data-course-category');
            const courseAreasAttr = checkbox.getAttribute('data-course-areas') || '[]';
            
            let shouldShow = false;
            
            if (selectedAreas.length === 0) {
                shouldShow = true;
            } else {
                try {
                    const courseAreas = JSON.parse(courseAreasAttr);
                    if (Array.isArray(courseAreas) && courseAreas.length > 0) {
                        shouldShow = selectedAreas.some(selectedArea => courseAreas.includes(selectedArea));
                    } else {
                        // Eğer alan bilgisi yoksa, kategori adına göre kontrol et
                        shouldShow = selectedAreas.includes(courseCategory);
                    }
                } catch (e) {
                    // JSON parse hatası durumunda kategori adına göre kontrol et
                    shouldShow = selectedAreas.includes(courseCategory);
                }
            }
            
            if (shouldShow) {
                checkbox.closest('.course-checkbox-item').style.display = 'block';
                visibleCount++;
            } else {
                checkbox.closest('.course-checkbox-item').style.display = 'none';
                checkbox.checked = false;
            }
        });
        
        // Eğer bu grupta görünen ders yoksa, grubu gizle
        if (visibleCount === 0) {
            group.style.display = 'none';
        } else {
            group.style.display = 'block';
            // Görünen ders sayısını güncelle
            const countBadge = group.querySelector('.course-count');
            if (countBadge) {
                countBadge.textContent = visibleCount + ' ders';
            }
        }
    });
    
    updateQuickAddPreview();
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
    
    // Günlere göre sırala
    sortScheduleItemsByDay();
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
    console.log('Loading topics for course:', courseId);
    fetch(`{{ route('admin.schedules.topics.by-course') }}?course_id=${courseId}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Topics loaded:', data);
            topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
            if (data.topics && data.topics.length > 0) {
                data.topics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = `${topic.name} (${topic.duration_minutes} dk)`;
                    topicSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Bu ders için konu bulunamadı';
                option.disabled = true;
                topicSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error loading topics:', error);
            topicSelect.innerHTML = '<option value="">Konu yüklenirken hata oluştu</option>';
        });
}

// Alt konuları yükle
function loadSubtopics(topicSelect) {
    console.log('loadSubtopics called');
    console.log('topicSelect:', topicSelect);
    
    const topicId = topicSelect.value;
    console.log('topicId:', topicId);
    
    const scheduleItem = topicSelect.closest('.schedule-item');
    console.log('scheduleItem:', scheduleItem);
    
    if (!scheduleItem) {
        console.error('Could not find schedule-item parent!');
        return;
    }
    
    const subtopicSelect = scheduleItem.querySelector('.subtopic-select');
    console.log('subtopicSelect:', subtopicSelect);
    
    if (!subtopicSelect) {
        console.error('Could not find subtopic-select!');
        return;
    }
    
    if (!topicId) {
        console.log('No topicId, clearing subtopics');
        subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
        return;
    }
    
    console.log('Loading subtopics for topic:', topicId);
    const url = `{{ route('admin.schedules.subtopics.by-topic') }}?topic_id=${topicId}`;
    console.log('Fetching URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Subtopics data received:', data);
            console.log('Subtopics array:', data.subtopics);
            console.log('Subtopics count:', data.subtopics ? data.subtopics.length : 0);
            
            subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
            if (data.subtopics && data.subtopics.length > 0) {
                data.subtopics.forEach(subtopic => {
                    console.log('Adding subtopic:', subtopic.name);
                    const option = document.createElement('option');
                    option.value = subtopic.id;
                    option.textContent = `${subtopic.name} (${subtopic.duration_minutes} dk)`;
                    subtopicSelect.appendChild(option);
                });
                console.log('All subtopics added successfully');
            } else {
                console.log('No subtopics found for this topic');
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Bu konu için alt konu bulunamadı';
                option.disabled = true;
                subtopicSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error loading subtopics:', error);
            console.error('Error details:', error.message, error.stack);
            subtopicSelect.innerHTML = '<option value="">Alt konu yüklenirken hata oluştu</option>';
        });
}

// Satır silme
function removeScheduleItem(button) {
    const scheduleItem = button.closest('.schedule-item');
    scheduleItem.remove();
    
    // Günlere göre sırala
    sortScheduleItemsByDay();
    
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

// Satır numaralarını güncelle (aritmetik artış)
function updateRowNumbers() {
    const scheduleItems = document.querySelectorAll('.schedule-item');
    scheduleItems.forEach((item, index) => {
        const rowNumber = item.querySelector('.row-number');
        if (rowNumber) {
            rowNumber.textContent = index + 1;
        }
    });
}

// Program öğelerini günlere göre sırala ve grupla
function sortScheduleItemsByDay() {
    const tbody = document.getElementById('scheduleItems');
    if (!tbody) return;
    
    const scheduleItems = Array.from(tbody.querySelectorAll('.schedule-item'));
    if (scheduleItems.length === 0) return;
    
    // Gün sırası
    const dayOrder = {
        'monday': 1,
        'tuesday': 2,
        'wednesday': 3,
        'thursday': 4,
        'friday': 5,
        'saturday': 6,
        'sunday': 7
    };
    
    // Önce tüm gün hücrelerini görünür yap
    scheduleItems.forEach(item => {
        const dayCell = item.querySelector('td:nth-child(2)');
        if (dayCell) {
            dayCell.style.display = '';
            dayCell.removeAttribute('rowspan');
            dayCell.classList.remove('day-header-cell', 'day-hidden-cell');
        }
    });
    
    // Satırları günlerine göre sırala
    scheduleItems.sort((a, b) => {
        const daySelectA = a.querySelector('.day-select');
        const daySelectB = b.querySelector('.day-select');
        
        const dayA = daySelectA ? daySelectA.value : '';
        const dayB = daySelectB ? daySelectB.value : '';
        
        // Eğer gün seçilmemişse en sona koy
        if (!dayA && !dayB) return 0;
        if (!dayA) return 1;
        if (!dayB) return -1;
        
        // Gün sırasına göre karşılaştır
        const orderA = dayOrder[dayA] || 999;
        const orderB = dayOrder[dayB] || 999;
        
        if (orderA !== orderB) {
            return orderA - orderB;
        }
        
        // Aynı gündeyse, satır numarasına göre sırala (eklenme sırası)
        return 0;
    });
    
    // Günlere göre grupla
    const groupedByDay = {};
    scheduleItems.forEach(item => {
        const daySelect = item.querySelector('.day-select');
        const day = daySelect ? daySelect.value : '';
        
        if (!groupedByDay[day]) {
            groupedByDay[day] = [];
        }
        groupedByDay[day].push(item);
    });
    
    // Tbody'yi temizle
    scheduleItems.forEach(item => item.remove());
    
    // Günlere göre sıralı şekilde ekle
    const sortedDays = Object.keys(groupedByDay).sort((a, b) => {
        const orderA = dayOrder[a] || 999;
        const orderB = dayOrder[b] || 999;
        return orderA - orderB;
    });
    
    sortedDays.forEach((day) => {
        const items = groupedByDay[day];
        
        items.forEach((item, itemIndex) => {
            const dayCell = item.querySelector('td:nth-child(2)');
            const daySelect = item.querySelector('.day-select');
            
            if (dayCell && daySelect) {
                // İlk satırda gün bilgisini göster ve rowspan ekle
                if (itemIndex === 0) {
                    dayCell.style.display = '';
                    dayCell.style.visibility = 'visible';
                    dayCell.setAttribute('rowspan', items.length);
                    dayCell.classList.add('day-header-cell');
                    dayCell.classList.remove('day-hidden-cell');
                    daySelect.style.display = '';
                } else {
                    // Diğer satırlar: Gün hücresini DOM'dan kaldır (rowspan kullanıldığı için)
                    // Rowspan kullanıldığında diğer satırlardaki hücreyi kaldırmak doğru
                    dayCell.remove();
                }
            }
            
            tbody.appendChild(item);
        });
    });
    
    // Satır numaralarını güncelle
    updateRowNumbers();
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
    
    // Modal açıldığında dersleri filtrele
    const quickAddModal = document.getElementById('quickAddModal');
    if (quickAddModal) {
        quickAddModal.addEventListener('show.bs.modal', function() {
            const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
            if (selectedAreas.length > 0) {
                filterQuickAddCourses(selectedAreas);
            } else {
                // Hiç alan seçilmediyse tüm dersleri göster
                const courseGroups = quickAddModal.querySelectorAll('.course-group');
                courseGroups.forEach(group => {
                    group.style.display = 'block';
                    const courseCheckboxes = group.querySelectorAll('.course-checkbox');
                    courseCheckboxes.forEach(checkbox => {
                        checkbox.closest('.course-checkbox-item').style.display = 'block';
                    });
                    const countBadge = group.querySelector('.course-count');
                    if (countBadge) {
                        countBadge.textContent = courseCheckboxes.length + ' ders';
                    }
                });
            }
        });
    }
    
    // Gün seçimi değiştiğinde sırala
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('day-select')) {
            sortScheduleItemsByDay();
        }
    });
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
    
    if (!previewContainer) return;
    
    if (selectedCourses.length === 0 || selectedDays.length === 0) {
        previewContainer.innerHTML = `
            <div class="preview-placeholder">
                <i class="fas fa-info-circle me-2"></i>
                <small class="text-muted">Ders ve gün seçin...</small>
            </div>
        `;
        return;
    }
    
    let previewHtml = '<div class="row">';
    selectedCourses.forEach(course => {
        const courseName = course.getAttribute('data-course-name');
        
        previewHtml += `<div class="col-md-6 mb-2">
            <div class="card card-body p-2">
                <strong>${courseName}</strong>
                <div class="mt-1">
                    ${selectedDays.map(day => {
                        const dayNames = {
                            'monday': 'Pzt',
                            'tuesday': 'Sal',
                            'wednesday': 'Çar',
                            'thursday': 'Per',
                            'friday': 'Cum',
                            'saturday': 'Cmt',
                            'sunday': 'Paz'
                        };
                        return `<span class="badge bg-primary me-1">${dayNames[day.value]}</span>`;
                    }).join('')}
                </div>
            </div>
        </div>`;
    });
    previewHtml += '</div>';
    
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
    
    console.log('Selected courses:', selectedCourses.length);
    console.log('Selected days:', selectedDays.length);
    
    if (selectedCourses.length === 0) {
        showAlert('warning', 'Lütfen en az bir ders seçin.');
        return;
    }
    
    if (selectedDays.length === 0) {
        showAlert('warning', 'Lütfen en az bir gün seçin.');
        return;
    }
    
    let totalAdded = 0;
    
    // Her ders için her gün için satır ekle
    selectedCourses.forEach((course, courseIndex) => {
        const courseId = course.value;
        const courseName = course.getAttribute('data-course-name');
        
        console.log(`Processing course ${courseIndex + 1}: ${courseName} (ID: ${courseId})`);
        
        selectedDays.forEach((day, dayIndex) => {
            const dayValue = day.value;
            const dayName = getDayName(dayValue);
            
            console.log(`  Adding day ${dayIndex + 1}: ${dayName}`);
            
            // Satır ekle
            addQuickScheduleItem(courseId, dayValue, courseName, dayName);
            totalAdded++;
        });
    });
    
    console.log('Total rows added:', totalAdded);
    
    // Seçimleri temizle (modal kapatılmadan ÖNCE)
    clearQuickAddSelections();
    
    // Günlere göre sırala
    sortScheduleItemsByDay();
    
    // Modal'ı kapat
    const modal = bootstrap.Modal.getInstance(document.getElementById('quickAddModal'));
    if (modal) {
        modal.hide();
    }
    
    showAlert('success', `${selectedCourses.length} ders, ${selectedDays.length} gün için toplam ${totalAdded} satır eklendi.`);
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

// Tüm dersleri seç (sadece görünen dersleri)
function selectAllCourses() {
    const modal = document.getElementById('quickAddModal');
    if (modal) {
        modal.querySelectorAll('.course-checkbox-item').forEach(item => {
            if (item.style.display !== 'none') {
                const checkbox = item.querySelector('.course-checkbox');
                if (checkbox) {
                    checkbox.checked = true;
                }
            }
        });
        updateQuickAddPreview();
    }
}

// Tüm dersleri kaldır
function deselectAllCourses() {
    const modal = document.getElementById('quickAddModal');
    if (modal) {
        modal.querySelectorAll('.course-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateQuickAddPreview();
    }
}

// Tüm günleri seç
function selectAllDays() {
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateQuickAddPreview();
}

// Tüm günleri kaldır
function deselectAllDays() {
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateQuickAddPreview();
}
</script>

<style>
/* Hızlı Ekleme Modal - Ders Grupları */
#quickAddModal .course-group {
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    width: 100%;
}

#quickAddModal .course-group:not(:last-child) {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
}

#quickAddModal .course-group .border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}

#quickAddModal .course-group .text-primary {
    font-size: 1rem;
    font-weight: 600;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

#quickAddModal .course-group .course-count {
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

#quickAddModal .course-checkbox-item {
    margin-left: 0.5rem;
    padding-left: 0.25rem;
    padding-right: 0.5rem;
    width: 100%;
    display: flex;
    align-items: flex-start;
}

#quickAddModal .course-checkbox-item .form-check-input {
    margin-top: 0.25rem;
    flex-shrink: 0;
}

#quickAddModal .course-checkbox-item label {
    font-weight: 400;
    cursor: pointer;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.5;
    width: 100%;
    margin-left: 0.5rem;
    padding-right: 0.5rem;
}

#quickAddModal .course-checkbox-item:hover {
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

#quickAddModal #courseCheckboxes {
    padding-right: 0.5rem;
}

#quickAddModal .modal-body {
    overflow-x: hidden;
    overflow-y: auto;
    max-height: calc(100vh - 200px);
    padding: 1.5rem;
}

#quickAddModal .modal-dialog {
    max-width: 900px;
    margin: 1.75rem auto;
}

#quickAddModal .row {
    margin-left: 0;
    margin-right: 0;
}

#quickAddModal .col-md-6 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

#quickAddModal .form-check {
    margin-bottom: 0.5rem;
}

#quickAddModal .form-check-label {
    width: 100%;
    display: block;
    word-break: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

/* Hızlı Ekleme Alanı Görünüm İyileştirmeleri */
#quickAddModal .quick-add-section {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.25rem;
    height: 100%;
    display: flex;
    flex-direction: column;
}

#quickAddModal .section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
}

#quickAddModal .section-title i {
    color: #0d6efd;
}

#quickAddModal .course-checkboxes-container {
    max-height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 0.5rem;
    flex: 1;
}

#quickAddModal .course-checkboxes-container::-webkit-scrollbar {
    width: 6px;
}

#quickAddModal .course-checkboxes-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#quickAddModal .course-checkboxes-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#quickAddModal .course-checkboxes-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

#quickAddModal .course-group {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

#quickAddModal .course-group:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-color: #dee2e6;
}

#quickAddModal .course-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #dee2e6;
}

#quickAddModal .course-group-header strong {
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

#quickAddModal .course-list {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

#quickAddModal .course-checkbox-item {
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.2s ease;
    margin: 0;
}

#quickAddModal .course-checkbox-item:hover {
    background-color: #e9ecef;
}

#quickAddModal .course-checkbox-item label {
    margin-left: 0.5rem;
    padding: 0;
    font-size: 0.9rem;
    color: #495057;
}

#quickAddModal .days-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 0.5rem 0;
}

#quickAddModal .day-item {
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.2s ease;
    margin: 0;
    background: #ffffff;
}

#quickAddModal .day-item:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    transform: translateX(4px);
}

#quickAddModal .day-item label {
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    margin: 0;
    width: 100%;
}

#quickAddModal .day-item input:checked + label {
    color: #0d6efd;
    font-weight: 600;
}

#quickAddModal .preview-container {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    min-height: 150px;
    max-height: 250px;
    overflow-y: auto;
    background: #f8f9fa;
}

#quickAddModal .preview-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    height: 100%;
}

#quickAddModal .preview-container .row {
    margin: 0;
}

#quickAddModal .preview-container .card {
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

#quickAddModal .preview-container .card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

/* Gün Gruplama Stilleri */
.day-header-cell {
    background-color: #e7f3ff !important;
    font-weight: 600;
    vertical-align: middle;
    text-align: center;
    border-right: 2px solid #0d6efd !important;
    position: relative;
}

.day-header-cell .day-select {
    font-weight: 600;
    background-color: #ffffff;
    border: 2px solid #0d6efd;
    width: 100%;
}

.day-hidden-cell {
    display: none !important;
}



.schedule-item:has(.day-header-cell) {
    border-top: 2px solid #0d6efd;
}

.schedule-item:has(.day-header-cell) + .schedule-item {
    border-top: 1px solid #dee2e6;
}
</style>
@endsection