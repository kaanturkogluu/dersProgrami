@extends('admin.layout')

@section('title', 'Soru Analizi Düzenle')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Soru Analizi Düzenle
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.question-analysis.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.question-analysis.show', $questionAnalysis) }}" class="btn btn-outline-primary">
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
                    Soru Analizi Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.question-analysis.update', $questionAnalysis) }}" method="POST">
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
                                                {{ old('student_id', $questionAnalysis->student_id) == $student->id ? 'selected' : '' }}>
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
                                                {{ old('topic_id', $questionAnalysis->topic_id) == $topic->id ? 'selected' : '' }}>
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
                                <label for="solved_at" class="form-label">Çözüm Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('solved_at') is-invalid @enderror" 
                                       id="solved_at" name="solved_at" 
                                       value="{{ old('solved_at', $questionAnalysis->solved_at->format('Y-m-d')) }}" required>
                                @error('solved_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="question_source" class="form-label">Soru Kaynağı</label>
                                <select class="form-select @error('question_source') is-invalid @enderror" 
                                        id="question_source" name="question_source">
                                    <option value="">Kaynak Seçin</option>
                                    <option value="ÖSYM" {{ old('question_source', $questionAnalysis->question_source) == 'ÖSYM' ? 'selected' : '' }}>ÖSYM</option>
                                    <option value="YKS" {{ old('question_source', $questionAnalysis->question_source) == 'YKS' ? 'selected' : '' }}>YKS</option>
                                    <option value="TYT" {{ old('question_source', $questionAnalysis->question_source) == 'TYT' ? 'selected' : '' }}>TYT</option>
                                    <option value="AYT" {{ old('question_source', $questionAnalysis->question_source) == 'AYT' ? 'selected' : '' }}>AYT</option>
                                    <option value="Deneme" {{ old('question_source', $questionAnalysis->question_source) == 'Deneme' ? 'selected' : '' }}>Deneme</option>
                                    <option value="Diğer" {{ old('question_source', $questionAnalysis->question_source) == 'Diğer' ? 'selected' : '' }}>Diğer</option>
                                </select>
                                @error('question_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="question_year" class="form-label">Soru Yılı</label>
                                <select class="form-select @error('question_year') is-invalid @enderror" 
                                        id="question_year" name="question_year">
                                    <option value="">Yıl Seçin</option>
                                    @for($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}" {{ old('question_year', $questionAnalysis->question_year) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('question_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="difficulty" class="form-label">Zorluk <span class="text-danger">*</span></label>
                                <select class="form-select @error('difficulty') is-invalid @enderror" 
                                        id="difficulty" name="difficulty" required>
                                    <option value="">Zorluk Seçin</option>
                                    <option value="kolay" {{ old('difficulty', $questionAnalysis->difficulty) == 'kolay' ? 'selected' : '' }}>Kolay</option>
                                    <option value="orta" {{ old('difficulty', $questionAnalysis->difficulty) == 'orta' ? 'selected' : '' }}>Orta</option>
                                    <option value="zor" {{ old('difficulty', $questionAnalysis->difficulty) == 'zor' ? 'selected' : '' }}>Zor</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="result" class="form-label">Sonuç <span class="text-danger">*</span></label>
                                <select class="form-select @error('result') is-invalid @enderror" 
                                        id="result" name="result" required>
                                    <option value="">Sonuç Seçin</option>
                                    <option value="correct" {{ old('result', $questionAnalysis->result) == 'correct' ? 'selected' : '' }}>Doğru</option>
                                    <option value="incorrect" {{ old('result', $questionAnalysis->result) == 'incorrect' ? 'selected' : '' }}>Yanlış</option>
                                    <option value="empty" {{ old('result', $questionAnalysis->result) == 'empty' ? 'selected' : '' }}>Boş</option>
                                </select>
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.question-analysis.show', $questionAnalysis) }}" class="btn btn-secondary me-2">
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
                    <p class="form-control-plaintext">{{ $questionAnalysis->student->full_name }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Konu:</label>
                    <p class="form-control-plaintext">
                        <strong>{{ $questionAnalysis->topic->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $questionAnalysis->topic->course->name }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Alt Konu:</label>
                    <p class="form-control-plaintext">
                        @if($questionAnalysis->subtopic)
                            {{ $questionAnalysis->subtopic->name }}
                        @else
                            <span class="text-muted">Belirtilmemiş</span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Mevcut Sonuç:</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-{{ $questionAnalysis->getResultColor() }}">
                            @if($questionAnalysis->result === 'correct')
                                <i class="fas fa-check me-1"></i>Doğru
                            @elseif($questionAnalysis->result === 'incorrect')
                                <i class="fas fa-times me-1"></i>Yanlış
                            @else
                                <i class="fas fa-circle me-1"></i>Boş
                            @endif
                        </span>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Oluşturulma Tarihi:</label>
                    <p class="form-control-plaintext">{{ $questionAnalysis->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Performans Özeti
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-{{ $questionAnalysis->getResultColor() }}">
                                @if($questionAnalysis->isCorrect())
                                    <i class="fas fa-check-circle"></i>
                                @elseif($questionAnalysis->isIncorrect())
                                    <i class="fas fa-times-circle"></i>
                                @else
                                    <i class="fas fa-circle"></i>
                                @endif
                            </h4>
                            <small class="text-muted">Sonuç</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-{{ $questionAnalysis->getDifficultyColor() }}">
                            @if($questionAnalysis->difficulty === 'kolay')
                                <i class="fas fa-smile"></i>
                            @elseif($questionAnalysis->difficulty === 'orta')
                                <i class="fas fa-meh"></i>
                            @else
                                <i class="fas fa-frown"></i>
                            @endif
                        </h4>
                        <small class="text-muted">Zorluk</small>
                    </div>
                </div>
                
                @if($questionAnalysis->time_spent_seconds > 0)
                <hr>
                <div class="text-center">
                    <h5 class="text-info">
                        <i class="fas fa-clock me-2"></i>
                        {{ $questionAnalysis->getTimeSpentFormatted() }}
                    </h5>
                    <small class="text-muted">Harcanan Süre</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const topicSelect = document.getElementById('topic_id');
    const subtopicSelect = document.getElementById('subtopic_id');
    const currentSubtopicId = {{ $questionAnalysis->subtopic_id ?? 'null' }};
    
    topicSelect.addEventListener('change', function() {
        const topicId = this.value;
        
        // Alt konu seçeneklerini temizle
        subtopicSelect.innerHTML = '<option value="">Alt Konu Seçin (Opsiyonel)</option>';
        
        if (topicId) {
            // AJAX ile alt konuları yükle
            fetch(`/admin/question-analysis/subtopics/by-topic?topic_id=${topicId}`)
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
