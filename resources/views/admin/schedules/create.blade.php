@extends('admin.layout')

@section('title', 'Yeni Haftalık Program Oluştur')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Yeni Haftalık Program Oluştur
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.programs.students') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
    @csrf
    
    <!-- Temel Bilgiler -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Program Bilgileri</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Öğrenci <span class="text-danger">*</span></label>
                        <select class="form-select @error('student_id') is-invalid @enderror" 
                                id="student_id" name="student_id" required>
                            <option value="">Öğrenci Seçin</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                        {{ old('student_id', $selectedStudentId) == $student->id ? 'selected' : '' }}>
                                    {{ $student->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Program Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="areas" class="form-label">Alanlar <span class="text-danger">*</span></label>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Alan Seçimi:</strong> Birden fazla alan seçebilirsiniz. Seçilen alanlara göre dersler filtrelenecektir.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="TYT" id="area_tyt" name="areas[]">
                                    <label class="form-check-label" for="area_tyt">
                                        <strong>TYT</strong> - Temel Yeterlilik Testi
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="EA" id="area_ea" name="areas[]">
                                    <label class="form-check-label" for="area_ea">
                                        <strong>EA</strong> - Eşit Ağırlık (TYT + AYT EA)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="SAY" id="area_say" name="areas[]">
                                    <label class="form-check-label" for="area_say">
                                        <strong>SAY</strong> - Sayısal (TYT + AYT Sayısal)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="SOZ" id="area_soz" name="areas[]">
                                    <label class="form-check-label" for="area_soz">
                                        <strong>SÖZ</strong> - Sözel (TYT + AYT Sözel)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="DIL" id="area_dil" name="areas[]">
                                    <label class="form-check-label" for="area_dil">
                                        <strong>DİL</strong> - Dil (TYT + YDT)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="KPSS" id="area_kpss" name="areas[]">
                                    <label class="form-check-label" for="area_kpss">
                                        <strong>KPSS</strong> - Kamu Personeli Seçme Sınavı
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('areas')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('areas.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Haftalık Program -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-table me-2"></i>
                    Haftalık Program Tablosu
                </h5>
                <div>
                    <button type="button" class="btn btn-success btn-sm me-2" onclick="addScheduleItem()">
                        <i class="fas fa-plus me-1"></i>
                        Satır Ekle
                    </button>
                    <button type="button" class="btn btn-warning btn-sm me-2" onclick="showQuickAddModal()">
                        <i class="fas fa-bolt me-1"></i>
                        Hızlı Ekle
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="clearAllRows()">
                        <i class="fas fa-trash me-1"></i>
                        Tümünü Temizle
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="scheduleTable">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">#</th>
                            <th style="width: 120px;">Gün</th>
                            <th style="width: 250px;">Ders</th>
                            <th style="width: 200px;">Konu</th>
                            <th style="width: 180px;">Alt Konu</th>
                            <th style="width: 250px;">Notlar</th>
                            <th style="width: 80px;" class="text-center">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleItems">
                        <tr class="empty-row">
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-table fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Henüz program satırı eklenmemiş</h5>
                                <p class="text-muted mb-3">Programınıza satır eklemek için "Satır Ekle" butonuna tıklayın.</p>
                                <button type="button" class="btn btn-primary" onclick="addScheduleItem()">
                                    <i class="fas fa-plus me-2"></i>
                                    İlk Satırı Ekle
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>
            İptal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>
            Programı Kaydet
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
            <select class="form-select form-select-sm day-select" name="schedule_items[INDEX][day_of_week]" required>
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
            <select class="form-select form-select-sm course-select" name="schedule_items[INDEX][course_id]" required>
                <option value="">Ders Seçin</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" data-area="{{ $course->category->name }}">
                        {{ $course->name }} ({{ $course->category->name }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-select form-select-sm topic-select" name="schedule_items[INDEX][topic_id]">
                <option value="">Konu Seçin</option>
            </select>
        </td>
        <td>
            <select class="form-select form-select-sm subtopic-select" name="schedule_items[INDEX][subtopic_id]">
                <option value="">Alt Konu Seçin</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="schedule_items[INDEX][notes]" placeholder="Notlar...">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleItem(this)" title="Satırı Sil">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<!-- Hızlı Ekleme Modal -->
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

<script>
let scheduleItemIndex = 0;

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
    
    // Satır numarasını güncelle
    updateRowNumbers();
    
    // Yeni eklenen ders seçimini alan filtresine göre güncelle
    const newScheduleItem = tbody.querySelector('.schedule-item:last-child');
    const newCourseSelect = newScheduleItem.querySelector('.course-select');
    const newTopicSelect = newScheduleItem.querySelector('.topic-select');
    const newSubtopicSelect = newScheduleItem.querySelector('.subtopic-select');
    const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
    
    // Konu ve alt konu seçimlerini temizle
    newTopicSelect.innerHTML = '<option value="">Konu Seçin</option>';
    newSubtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
    
    if (selectedAreas.length > 0) {
        // Seçili alanlara ait dersleri yükle
        const areasParam = selectedAreas.join(',');
        fetch(`{{ route('admin.schedules.courses.by-area') }}?areas=${areasParam}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                newCourseSelect.innerHTML = '<option value="">Ders Seçin</option>';
                if (data.courses && data.courses.length > 0) {
                    data.courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = `${course.name} (${course.category})`;
                        option.setAttribute('data-area', course.category);
                        newCourseSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Seçilen alanlar için ders bulunamadı';
                    option.disabled = true;
                    newCourseSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                newCourseSelect.innerHTML = '<option value="">Ders yüklenirken hata oluştu</option>';
            });
    } else {
        // Hiç alan seçilmediyse tüm dersleri göster
        newCourseSelect.innerHTML = '<option value="">Ders Seçin</option>';
        @foreach($courses as $course)
            const option{{ $course->id }} = document.createElement('option');
            option{{ $course->id }}.value = '{{ $course->id }}';
            option{{ $course->id }}.textContent = '{{ $course->name }} ({{ $course->category->name }})';
            option{{ $course->id }}.setAttribute('data-area', '{{ $course->category->name }}');
            newCourseSelect.appendChild(option{{ $course->id }});
        @endforeach
    }
    
    scheduleItemIndex++;
}

function removeScheduleItem(button) {
    button.closest('.schedule-item').remove();
    
    // Satır numaralarını güncelle
    updateRowNumbers();
    
    // Eğer hiç öğe kalmadıysa, boş satırı göster
    const tbody = document.getElementById('scheduleItems');
    if (tbody.querySelectorAll('.schedule-item').length === 0) {
        const emptyRow = tbody.querySelector('.empty-row');
        if (emptyRow) {
            emptyRow.style.display = 'table-row';
        }
    }
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('.schedule-item');
    rows.forEach((row, index) => {
        const rowNumber = row.querySelector('.row-number');
        if (rowNumber) {
            rowNumber.textContent = index + 1;
        }
    });
}

function clearAllRows() {
    if (confirm('Tüm satırları silmek istediğinizden emin misiniz?')) {
        const tbody = document.getElementById('scheduleItems');
        tbody.querySelectorAll('.schedule-item').forEach(row => row.remove());
        
        // Boş satırı göster
        const emptyRow = tbody.querySelector('.empty-row');
        if (emptyRow) {
            emptyRow.style.display = 'table-row';
        }
        
        scheduleItemIndex = 0;
    }
}

function showQuickAddModal() {
    const modal = new bootstrap.Modal(document.getElementById('quickAddModal'));
    modal.show();
    
    // Alan filtresini uygula
    const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
    if (selectedAreas.length > 0) {
        filterCoursesInModal(selectedAreas);
    } else {
        // Hiç alan seçilmediyse tüm dersleri göster
        showAllCoursesInModal();
    }
}

function filterCoursesInModal(areas) {
    const courseCheckboxes = document.querySelectorAll('.course-checkbox');
    courseCheckboxes.forEach(checkbox => {
        const courseCategory = checkbox.getAttribute('data-course-category');
        if (areas.includes(courseCategory)) {
            checkbox.closest('.form-check').style.display = 'block';
        } else {
            checkbox.closest('.form-check').style.display = 'none';
            checkbox.checked = false;
        }
    });
    updatePreview();
}

function showAllCoursesInModal() {
    const courseCheckboxes = document.querySelectorAll('.course-checkbox');
    courseCheckboxes.forEach(checkbox => {
        checkbox.closest('.form-check').style.display = 'block';
    });
    updatePreview();
}

function updatePreview() {
    const selectedCourses = Array.from(document.querySelectorAll('.course-checkbox:checked'));
    const selectedDays = Array.from(document.querySelectorAll('.day-checkbox:checked'));
    const previewDiv = document.getElementById('previewItems');
    
    if (selectedCourses.length === 0 || selectedDays.length === 0) {
        previewDiv.innerHTML = '<small class="text-muted">Ders ve gün seçin...</small>';
        return;
    }
    
    let previewHtml = '<div class="row">';
    selectedCourses.forEach(course => {
        const courseName = course.getAttribute('data-course-name');
        const courseCategory = course.getAttribute('data-course-category');
        
        previewHtml += `<div class="col-md-6 mb-2">
            <div class="card card-body p-2">
                <strong>${courseName}</strong> <small class="text-muted">(${courseCategory})</small>
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
    
    previewDiv.innerHTML = previewHtml;
}

function addQuickItems() {
    const selectedCourses = Array.from(document.querySelectorAll('.course-checkbox:checked'));
    const selectedDays = Array.from(document.querySelectorAll('.day-checkbox:checked'));
    
    if (selectedCourses.length === 0) {
        alert('Lütfen en az bir ders seçin.');
        return;
    }
    
    if (selectedDays.length === 0) {
        alert('Lütfen en az bir gün seçin.');
        return;
    }
    
    const tbody = document.getElementById('scheduleItems');
    const emptyRow = tbody.querySelector('.empty-row');
    if (emptyRow) {
        emptyRow.style.display = 'none';
    }
    
    // Günleri sıralı hale getir
    const dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    const sortedDays = selectedDays.sort((a, b) => {
        return dayOrder.indexOf(a.value) - dayOrder.indexOf(b.value);
    });
    
    // Gün bazında sıralama: Önce tüm Pazartesi'ler, sonra tüm Salı'lar, vb.
    sortedDays.forEach(day => {
        selectedCourses.forEach(course => {
            const template = document.getElementById('scheduleItemTemplate');
            const clone = template.content.cloneNode(true);
            
            const html = clone.querySelector('.schedule-item').outerHTML;
            const updatedHtml = html.replace(/INDEX/g, scheduleItemIndex);
            
            tbody.insertAdjacentHTML('beforeend', updatedHtml);
            
            // Yeni satırı bul ve değerleri ayarla
            const newRow = tbody.querySelector('.schedule-item:last-child');
            const daySelect = newRow.querySelector('.day-select');
            const courseSelect = newRow.querySelector('.course-select');
            
            daySelect.value = day.value;
            courseSelect.value = course.value;
            
            // Alan filtresini uygula
            const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
            if (selectedAreas.length > 0) {
                updateCourseSelectForAreas(courseSelect, selectedAreas);
            } else {
                // Hiç alan seçilmediyse tüm dersleri göster
                showAllCoursesInSelect(courseSelect);
            }
            
            // Ders seçildiğinde konuları yükle
            if (course.value) {
                loadTopicsForCourse(courseSelect, newRow);
            }
            
            scheduleItemIndex++;
        });
    });
    
    updateRowNumbers();
    
    // Modal'ı kapat
    const modal = bootstrap.Modal.getInstance(document.getElementById('quickAddModal'));
    modal.hide();
    
    // Seçimleri temizle
    document.querySelectorAll('.course-checkbox, .day-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updatePreview();
}

function updateCourseSelectForAreas(selectElement, areas) {
    const options = selectElement.querySelectorAll('option');
    options.forEach(option => {
        if (option.value === '') return;
        
        const courseCategory = option.getAttribute('data-area');
        if (areas.includes(courseCategory)) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
}

function showAllCoursesInSelect(selectElement) {
    const options = selectElement.querySelectorAll('option');
    options.forEach(option => {
        option.style.display = 'block';
    });
}

function loadTopicsForCourse(courseSelect, scheduleItem) {
    const courseId = courseSelect.value;
    const topicSelect = scheduleItem.querySelector('.topic-select');
    const subtopicSelect = scheduleItem.querySelector('.subtopic-select');
    
    // Konuları ve alt konuları temizle
    topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
    subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
    
    if (courseId) {
        // AJAX ile konuları yükle
        fetch(`{{ route('admin.schedules.topics.by-course') }}?course_id=${courseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.topics && data.topics.length > 0) {
                    data.topics.forEach(topic => {
                        const option = document.createElement('option');
                        option.value = topic.id;
                        option.textContent = topic.name;
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
}

// Alan seçildiğinde dersleri filtrele
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('area-checkbox')) {
        const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
        const courseSelects = document.querySelectorAll('.course-select');
        
        if (selectedAreas.length > 0) {
            // Seçilen alanları virgülle ayırarak gönder
            const areasParam = selectedAreas.join(',');
            fetch(`{{ route('admin.schedules.courses.by-area') }}?areas=${areasParam}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    courseSelects.forEach(select => {
                        // Mevcut seçimi temizle
                        select.innerHTML = '<option value="">Ders Seçin</option>';
                        
                        // Yeni dersleri ekle
                        if (data.courses && data.courses.length > 0) {
                            data.courses.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = `${course.name} (${course.category})`;
                                option.setAttribute('data-area', course.category);
                                select.appendChild(option);
                            });
                        } else {
                            // Ders bulunamadı mesajı
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Seçilen alanlar için ders bulunamadı';
                            option.disabled = true;
                            select.appendChild(option);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading courses:', error);
                    courseSelects.forEach(select => {
                        select.innerHTML = '<option value="">Ders yüklenirken hata oluştu</option>';
                    });
                });
        } else {
            // Hiç alan seçilmediyse tüm dersleri göster
            courseSelects.forEach(select => {
                select.innerHTML = '<option value="">Ders Seçin</option>';
                @foreach($courses as $course)
                    const option{{ $course->id }} = document.createElement('option');
                    option{{ $course->id }}.value = '{{ $course->id }}';
                    option{{ $course->id }}.textContent = '{{ $course->name }} ({{ $course->category->name }})';
                    option{{ $course->id }}.setAttribute('data-area', '{{ $course->category->name }}');
                    select.appendChild(option{{ $course->id }});
                @endforeach
            });
        }
        
        // Hızlı ekleme modal'ındaki dersleri de güncelle
        const modalCourseCheckboxes = document.querySelectorAll('#quickAddModal .course-checkbox');
        if (selectedAreas.length > 0) {
            modalCourseCheckboxes.forEach(checkbox => {
                const courseCategory = checkbox.getAttribute('data-course-category');
                if (selectedAreas.includes(courseCategory)) {
                    checkbox.closest('.form-check').style.display = 'block';
                } else {
                    checkbox.closest('.form-check').style.display = 'none';
                    checkbox.checked = false;
                }
            });
        } else {
            modalCourseCheckboxes.forEach(checkbox => {
                checkbox.closest('.form-check').style.display = 'block';
            });
        }
    }
});

// Ders seçildiğinde konuları yükle
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('course-select')) {
        const courseId = e.target.value;
        const scheduleItem = e.target.closest('.schedule-item');
        const topicSelect = scheduleItem.querySelector('.topic-select');
        const subtopicSelect = scheduleItem.querySelector('.subtopic-select');
        
        // Konuları ve alt konuları temizle
        topicSelect.innerHTML = '<option value="">Konu Seçin</option>';
        subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
        
        if (courseId) {
            // AJAX ile konuları yükle
            fetch(`{{ route('admin.schedules.topics.by-course') }}?course_id=${courseId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
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
    }
});

// Konu seçildiğinde alt konuları yükle
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('topic-select')) {
        const topicId = e.target.value;
        const scheduleItem = e.target.closest('.schedule-item');
        const subtopicSelect = scheduleItem.querySelector('.subtopic-select');
        
        // Alt konuları temizle
        subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
        
        if (topicId) {
            // AJAX ile alt konuları yükle
            fetch(`{{ route('admin.schedules.subtopics.by-topic') }}?topic_id=${topicId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.subtopics && data.subtopics.length > 0) {
                        data.subtopics.forEach(subtopic => {
                            const option = document.createElement('option');
                            option.value = subtopic.id;
                            option.textContent = `${subtopic.name} (${subtopic.duration_minutes} dk)`;
                            subtopicSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Bu konu için alt konu bulunamadı';
                        option.disabled = true;
                        subtopicSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error loading subtopics:', error);
                    subtopicSelect.innerHTML = '<option value="">Alt konu yüklenirken hata oluştu</option>';
                });
        }
    }
});

// Hızlı ekleme modal event listener'ları
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('course-checkbox') || e.target.classList.contains('day-checkbox')) {
        updatePreview();
    }
});

// Modal açıldığında alan filtresini uygula
document.getElementById('quickAddModal').addEventListener('show.bs.modal', function() {
    const selectedAreas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
    if (selectedAreas.length > 0) {
        filterCoursesInModal(selectedAreas);
    }
});
</script>

<style>
/* Excel Benzeri Tablo Stilleri */
#scheduleTable {
    font-size: 0.875rem;
    border-collapse: separate;
    border-spacing: 0;
}

#scheduleTable thead th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #495057;
    padding: 8px 4px;
    position: sticky;
    top: 0;
    z-index: 10;
}

#scheduleTable tbody td {
    padding: 4px;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    background-color: white;
}

#scheduleTable tbody tr:hover {
    background-color: #f8f9fa;
}

#scheduleTable tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

#scheduleTable tbody tr:nth-child(even):hover {
    background-color: #e9ecef;
}

/* Form Elemanları */
#scheduleTable .form-control,
#scheduleTable .form-select {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    height: auto;
    min-height: 32px;
}

#scheduleTable .form-control:focus,
#scheduleTable .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Satır Numarası */
.row-number {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.8rem;
}

/* Butonlar */
#scheduleTable .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
}

/* Boş Satır */
.empty-row td {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

/* Responsive */
@media (max-width: 1200px) {
    #scheduleTable {
        font-size: 0.8rem;
    }
    
    #scheduleTable .form-control,
    #scheduleTable .form-select {
        font-size: 0.8rem;
        padding: 0.2rem 0.4rem;
    }
}

/* Tablo Scroll */
.table-responsive {
    max-height: 600px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
}

/* Excel Benzeri Grid */
#scheduleTable tbody tr {
    height: 40px;
}

#scheduleTable tbody td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Seçili Satır */
#scheduleTable tbody tr.selected {
    background-color: #cff4fc;
    border: 2px solid #0dcaf0;
}

/* Hover Efektleri */
#scheduleTable tbody tr:hover .btn {
    opacity: 1;
}

#scheduleTable tbody tr .btn {
    opacity: 0.7;
    transition: opacity 0.2s ease;
}
</style>
@endsection
