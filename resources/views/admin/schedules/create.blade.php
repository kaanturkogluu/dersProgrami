@extends('admin.layout')

@section('title', 'Yeni Haftalık Program Oluştur')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Yeni Haftalık Program Oluştur
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">
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
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="area" class="form-label">Alan <span class="text-danger">*</span></label>
                        <select class="form-select @error('area') is-invalid @enderror" 
                                id="area" name="area" required>
                            <option value="">Alan Seçin</option>
                            <option value="TYT" {{ old('area') == 'TYT' ? 'selected' : '' }}>TYT</option>
                            <option value="AYT" {{ old('area') == 'AYT' ? 'selected' : '' }}>AYT</option>
                            <option value="KPSS" {{ old('area') == 'KPSS' ? 'selected' : '' }}>KPSS</option>
                            <option value="DGS" {{ old('area') == 'DGS' ? 'selected' : '' }}>DGS</option>
                            <option value="ALES" {{ old('area') == 'ALES' ? 'selected' : '' }}>ALES</option>
                        </select>
                        @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Bitiş Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
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
                <h5 class="card-title mb-0">Haftalık Program</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addScheduleItem()">
                    <i class="fas fa-plus me-1"></i>
                    Ders Ekle
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="scheduleItems">
                <!-- Program öğeleri buraya eklenecek -->
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">Henüz ders eklenmemiş. "Ders Ekle" butonuna tıklayarak programınızı oluşturun.</p>
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

<!-- Program Öğesi Template -->
<template id="scheduleItemTemplate">
    <div class="schedule-item border rounded p-3 mb-3">
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Gün</label>
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
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Ders</label>
                    <select class="form-select course-select" name="schedule_items[INDEX][course_id]" required>
                        <option value="">Ders Seçin</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" data-area="{{ $course->category->name }}">
                                {{ $course->name }} ({{ $course->category->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Başlangıç</label>
                    <input type="time" class="form-control start-time" name="schedule_items[INDEX][start_time]" required>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Bitiş</label>
                    <input type="time" class="form-control end-time" name="schedule_items[INDEX][end_time]" required>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">İşlemler</label>
                    <div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeScheduleItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Konu (Opsiyonel)</label>
                    <select class="form-select topic-select" name="schedule_items[INDEX][topic_id]">
                        <option value="">Konu Seçin</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Alt Konu (Opsiyonel)</label>
                    <select class="form-select subtopic-select" name="schedule_items[INDEX][subtopic_id]">
                        <option value="">Alt Konu Seçin</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Notlar</label>
                    <input type="text" class="form-control" name="schedule_items[INDEX][notes]" placeholder="Notlar...">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let scheduleItemIndex = 0;

function addScheduleItem() {
    const template = document.getElementById('scheduleItemTemplate');
    const clone = template.content.cloneNode(true);
    
    // Index'i güncelle
    const html = clone.querySelector('.schedule-item').outerHTML;
    const updatedHtml = html.replace(/INDEX/g, scheduleItemIndex);
    
    const container = document.getElementById('scheduleItems');
    container.insertAdjacentHTML('beforeend', updatedHtml);
    
    // Yeni eklenen ders seçimini alan filtresine göre güncelle
    const newScheduleItem = container.querySelector('.schedule-item:last-child');
    const newCourseSelect = newScheduleItem.querySelector('.course-select');
    const newTopicSelect = newScheduleItem.querySelector('.topic-select');
    const newSubtopicSelect = newScheduleItem.querySelector('.subtopic-select');
    const selectedArea = document.getElementById('area').value;
    
    // Konu ve alt konu seçimlerini temizle
    newTopicSelect.innerHTML = '<option value="">Konu Seçin</option>';
    newSubtopicSelect.innerHTML = '<option value="">Alt Konu Seçin</option>';
    
    if (selectedArea) {
        // Seçili alana ait dersleri yükle
        fetch(`{{ route('admin.schedules.courses.by-area') }}?area=${selectedArea}`)
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
                    option.textContent = 'Bu alan için ders bulunamadı';
                    option.disabled = true;
                    newCourseSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                newCourseSelect.innerHTML = '<option value="">Ders yüklenirken hata oluştu</option>';
            });
    }
    
    // Eğer ilk öğe ise, merkez mesajını gizle
    if (scheduleItemIndex === 0) {
        container.querySelector('.text-center').style.display = 'none';
    }
    
    scheduleItemIndex++;
}

function removeScheduleItem(button) {
    button.closest('.schedule-item').remove();
    
    // Eğer hiç öğe kalmadıysa, merkez mesajını göster
    const container = document.getElementById('scheduleItems');
    if (container.querySelectorAll('.schedule-item').length === 0) {
        container.querySelector('.text-center').style.display = 'block';
    }
}

// Alan seçildiğinde dersleri filtrele
document.addEventListener('change', function(e) {
    if (e.target.id === 'area') {
        const selectedArea = e.target.value;
        const courseSelects = document.querySelectorAll('.course-select');
        
        if (selectedArea) {
            // AJAX ile alana ait dersleri getir
            fetch(`{{ route('admin.schedules.courses.by-area') }}?area=${selectedArea}`)
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
                            option.textContent = 'Bu alan için ders bulunamadı';
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
            // Alan seçilmediyse tüm dersleri göster
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
</script>
@endsection
