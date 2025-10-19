@extends('admin.layout')

@section('title', 'Yeni Soru Analizi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Yeni Soru Analizi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.question-analysis.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
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
                <form action="{{ route('admin.question-analysis.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Öğrenci <span class="text-danger">*</span></label>
                                <select class="form-select @error('student_id') is-invalid @enderror" 
                                        id="student_id" name="student_id" required>
                                    <option value="">Öğrenci Seçin</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                                {{ old('student_id', $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
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
                                                {{ old('topic_id', $selectedTopicId ?? '') == $topic->id ? 'selected' : '' }}>
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
                                       value="{{ old('solved_at', date('Y-m-d')) }}" required>
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
                                    <option value="ÖSYM" {{ old('question_source') == 'ÖSYM' ? 'selected' : '' }}>ÖSYM</option>
                                    <option value="YKS" {{ old('question_source') == 'YKS' ? 'selected' : '' }}>YKS</option>
                                    <option value="TYT" {{ old('question_source') == 'TYT' ? 'selected' : '' }}>TYT</option>
                                    <option value="AYT" {{ old('question_source') == 'AYT' ? 'selected' : '' }}>AYT</option>
                                    <option value="Deneme" {{ old('question_source') == 'Deneme' ? 'selected' : '' }}>Deneme</option>
                                    <option value="Diğer" {{ old('question_source') == 'Diğer' ? 'selected' : '' }}>Diğer</option>
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
                                        <option value="{{ $year }}" {{ old('question_year', date('Y')) == $year ? 'selected' : '' }}>
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
                                    <option value="kolay" {{ old('difficulty') == 'kolay' ? 'selected' : '' }}>Kolay</option>
                                    <option value="orta" {{ old('difficulty') == 'orta' ? 'selected' : '' }}>Orta</option>
                                    <option value="zor" {{ old('difficulty') == 'zor' ? 'selected' : '' }}>Zor</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="solved_at" class="form-label">Çözüm Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('solved_at') is-invalid @enderror" 
                                       id="solved_at" name="solved_at" 
                                       value="{{ old('solved_at', date('Y-m-d')) }}" required>
                                @error('solved_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Soru Sayıları -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-calculator me-2"></i>
                                Soru Sayıları
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="total_questions" class="form-label">Toplam Soru <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('total_questions') is-invalid @enderror" 
                                               id="total_questions" name="total_questions" 
                                               value="{{ old('total_questions', 1) }}" 
                                               min="1" max="100" required>
                                        @error('total_questions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="correct_count" class="form-label">Doğru Sayısı <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('correct_count') is-invalid @enderror" 
                                               id="correct_count" name="correct_count" 
                                               value="{{ old('correct_count', 0) }}" 
                                               min="0" max="100" required>
                                        @error('correct_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="incorrect_count" class="form-label">Yanlış Sayısı <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('incorrect_count') is-invalid @enderror" 
                                               id="incorrect_count" name="incorrect_count" 
                                               value="{{ old('incorrect_count', 0) }}" 
                                               min="0" max="100" required>
                                        @error('incorrect_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="empty_count" class="form-label">Boş Sayısı <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('empty_count') is-invalid @enderror" 
                                               id="empty_count" name="empty_count" 
                                               value="{{ old('empty_count', 0) }}" 
                                               min="0" max="100" required>
                                        @error('empty_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Otomatik Hesaplama Sonuçları -->
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="alert alert-info">
                                        <strong>Net:</strong> <span id="net_result">0.0</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-warning">
                                        <strong>Başarı Oranı:</strong> <span id="success_rate">0%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alert alert-success">
                                        <strong>Performans:</strong> <span id="performance_stars">⭐⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.question-analysis.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Kaydet
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
                    Bilgi
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Soru Analizi Sistemi</h6>
                    <ul class="mb-0">
                        <li>Öğrencilerin soru çözüm performanslarını takip edin</li>
                        <li>Zorluk seviyesi ve sonuçları analiz edin</li>
                        <li>Harcanan süreleri ölçün</li>
                        <li>Detaylı açıklamalar ve notlar ekleyin</li>
                    </ul>
                </div>
                
                <div class="alert alert-success">
                    <h6><i class="fas fa-chart-bar me-2"></i>İstatistikler</h6>
                    <p class="mb-0">Soru analizleri sayesinde öğrencilerin güçlü ve zayıf yönlerini tespit edebilirsiniz.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const topicSelect = document.getElementById('topic_id');
    const subtopicSelect = document.getElementById('subtopic_id');
    
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
            
            // Eğer seçili alt konu varsa onu seç
            const selectedSubtopicId = {{ $selectedSubtopicId ?? 'null' }};
            if (selectedSubtopicId) {
                setTimeout(() => {
                    subtopicSelect.value = selectedSubtopicId;
                }, 500);
            }
        }

        // Soru sayıları hesaplama fonksiyonu
        function calculateResults() {
            const total = parseInt(document.getElementById('total_questions').value) || 0;
            const correct = parseInt(document.getElementById('correct_count').value) || 0;
            const incorrect = parseInt(document.getElementById('incorrect_count').value) || 0;
            const empty = parseInt(document.getElementById('empty_count').value) || 0;
            
            // Toplam kontrolü
            if (correct + incorrect + empty > total) {
                document.getElementById('empty_count').value = Math.max(0, total - correct - incorrect);
            }
            
            const finalEmpty = parseInt(document.getElementById('empty_count').value) || 0;
            
            // Net hesaplama (3 yanlış 1 doğruyu götürür)
            const net = Math.max(0, correct - (incorrect / 3));
            
            // Başarı oranı
            const successRate = total > 0 ? ((correct / total) * 100).toFixed(1) : 0;
            
            // Yıldızlama sistemi
            let stars = 0;
            if (total > 0) {
                const percentage = (correct / total) * 100;
                if (percentage >= 90) stars = 5;
                else if (percentage >= 80) stars = 4;
                else if (percentage >= 70) stars = 3;
                else if (percentage >= 60) stars = 2;
                else if (percentage >= 50) stars = 1;
                else stars = 0;
            }
            
            // Sonuçları göster
            document.getElementById('net_result').textContent = net.toFixed(1);
            document.getElementById('success_rate').textContent = successRate + '%';
            
            // Yıldızları göster
            let starHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= stars) {
                    starHtml += '⭐';
                } else {
                    starHtml += '☆';
                }
            }
            document.getElementById('performance_stars').textContent = starHtml;
        }

        // Event listener'ları ekle
        document.getElementById('total_questions').addEventListener('input', calculateResults);
        document.getElementById('correct_count').addEventListener('input', calculateResults);
        document.getElementById('incorrect_count').addEventListener('input', calculateResults);
        document.getElementById('empty_count').addEventListener('input', calculateResults);

        // Sayfa yüklendiğinde hesapla
        calculateResults();
});
</script>
@endsection
