@extends('admin.layout')

@section('title', 'Konu Takip Düzenle')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Konu Takip Düzenle
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topic-tracking.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.topic-tracking.show', $topicTracking) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>
            Görüntüle
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Konu Takip Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.topic-tracking.update', $topicTracking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Öğrenci <span class="text-danger">*</span></label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                        id="student_id" name="student_id" required>
                                    <option value="">Öğrenci Seçin</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                                {{ old('student_id', $topicTracking->student_id) == $student->id ? 'selected' : '' }}>
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
                                <label for="topic_id" class="form-label">Konu <span class="text-danger">*</span></label>
                                <select class="form-select @error('topic_id') is-invalid @enderror" 
                                        id="topic_id" name="topic_id" required>
                                    <option value="">Konu Seçin</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}" 
                                                {{ old('topic_id', $topicTracking->topic_id) == $topic->id ? 'selected' : '' }}>
                                            {{ $topic->name }} ({{ $topic->course->name }} - {{ $topic->course->category->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('topic_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subtopic_id" class="form-label">Alt Konu</label>
                                <select class="form-select @error('subtopic_id') is-invalid @enderror" 
                                        id="subtopic_id" name="subtopic_id">
                                    <option value="">Alt Konu Seçin (Opsiyonel)</option>
                                </select>
                                @error('subtopic_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Durum <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="not_started" {{ old('status', $topicTracking->status) == 'not_started' ? 'selected' : '' }}>Başlanmadı</option>
                                    <option value="in_progress" {{ old('status', $topicTracking->status) == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                                    <option value="completed" {{ old('status', $topicTracking->status) == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                    <option value="approved" {{ old('status', $topicTracking->status) == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="difficulty_level" class="form-label">Zorluk Seviyesi <span class="text-danger">*</span></label>
                                <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                        id="difficulty_level" name="difficulty_level" required>
                                    <option value="">Zorluk Seviyesi Seçin</option>
                                    <option value="1" {{ old('difficulty_level', $topicTracking->difficulty_level) == '1' ? 'selected' : '' }}>1 - Çok Kolay</option>
                                    <option value="2" {{ old('difficulty_level', $topicTracking->difficulty_level) == '2' ? 'selected' : '' }}>2 - Kolay</option>
                                    <option value="3" {{ old('difficulty_level', $topicTracking->difficulty_level) == '3' ? 'selected' : '' }}>3 - Orta</option>
                                    <option value="4" {{ old('difficulty_level', $topicTracking->difficulty_level) == '4' ? 'selected' : '' }}>4 - Zor</option>
                                    <option value="5" {{ old('difficulty_level', $topicTracking->difficulty_level) == '5' ? 'selected' : '' }}>5 - Çok Zor</option>
                                </select>
                                @error('difficulty_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="time_spent_minutes" class="form-label">Harcanan Süre (Dakika)</label>
                                <input type="number" class="form-control @error('time_spent_minutes') is-invalid @enderror" 
                                       id="time_spent_minutes" name="time_spent_minutes" 
                                       value="{{ old('time_spent_minutes', $topicTracking->time_spent_minutes) }}" 
                                       min="0" step="1">
                                @error('time_spent_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notlar</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4" 
                                  placeholder="Konu hakkında notlarınızı buraya yazabilirsiniz...">{{ old('notes', $topicTracking->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.topic-tracking.show', $topicTracking) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Mevcut Bilgiler
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Öğrenci:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->student->full_name }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Konu:</label>
                    <p class="form-control-plaintext">
                        <strong>{{ $topicTracking->topic->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $topicTracking->topic->course->name }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Alt Konu:</label>
                    <p class="form-control-plaintext">
                        @if($topicTracking->subtopic)
                            {{ $topicTracking->subtopic->name }}
                        @else
                            <span class="text-muted">Belirtilmemiş</span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Mevcut Durum:</label>
                    <p class="form-control-plaintext">
                        @php
                            $statusColors = [
                                'not_started' => 'secondary',
                                'in_progress' => 'warning',
                                'completed' => 'success',
                                'approved' => 'info'
                            ];
                            $statusTexts = [
                                'not_started' => 'Başlanmadı',
                                'in_progress' => 'Devam Ediyor',
                                'completed' => 'Tamamlandı',
                                'approved' => 'Onaylandı'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$topicTracking->status] }}">
                            {{ $statusTexts[$topicTracking->status] }}
                        </span>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Oluşturulma Tarihi:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Uyarı
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <p class="mb-0">Durum değişiklikleri otomatik olarak tarih bilgilerini güncelleyecektir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const topicSelect = document.getElementById('topic_id');
    const subtopicSelect = document.getElementById('subtopic_id');
    const currentSubtopicId = {{ $topicTracking->subtopic_id ?? 'null' }};
    
    topicSelect.addEventListener('change', function() {
        const topicId = this.value;
        
        // Alt konu seçeneklerini temizle
        subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin (Opsiyonel)</option>';
        
        if (topicId) {
            // AJAX ile alt konuları yükle
            fetch(`/admin/topic-tracking/subtopics/by-topic?topic_id=${topicId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subtopic => {
                        const option = document.createElement('option');
                        option.value = subtopic.id;
                        option.textContent = subtopic.name;
                        
                        // Mevcut alt konuyu seçili yap
                        if (currentSubtopicId && subtopic.id == currentSubtopicId) {
                            option.selected = true;
                        }
                        
                        subtopicSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading subtopics:', error);
                });
        }
    });
    
    // Sayfa yüklendiğinde eğer topic seçiliyse alt konuları yükle
    if (topicSelect.value) {
        topicSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
