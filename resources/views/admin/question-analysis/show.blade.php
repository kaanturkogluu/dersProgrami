@extends('admin.layout')

@section('title', 'Soru Analizi Detayı')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Soru Analizi Detayı
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.question-analysis.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.question-analysis.edit', $questionAnalysis) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>
            Düzenle
        </a>
        <form action="{{ route('admin.question-analysis.destroy', $questionAnalysis) }}" method="POST" 
              style="display: inline;" onsubmit="return confirm('Bu soru analizini silmek istediğinizden emin misiniz?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>
                Sil
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Soru Analizi Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Öğrenci:</label>
                            <p class="form-control-plaintext">{{ $questionAnalysis->student->full_name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Çözüm Tarihi:</label>
                            <p class="form-control-plaintext">{{ $questionAnalysis->solved_at->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konu:</label>
                            <p class="form-control-plaintext">
                                <strong>{{ $questionAnalysis->topic->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $questionAnalysis->topic->course->name }} - {{ $questionAnalysis->topic->course->category->name }}</small>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Soru Kaynağı:</label>
                            <p class="form-control-plaintext">
                                @if($questionAnalysis->question_source)
                                    {{ $questionAnalysis->question_source }}
                                    @if($questionAnalysis->question_year)
                                        <br>
                                        <small class="text-muted">{{ $questionAnalysis->question_year }} yılı</small>
                                    @endif
                                    @if($questionAnalysis->question_number)
                                        <br>
                                        <small class="text-muted">Soru {{ $questionAnalysis->question_number }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Zorluk:</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $questionAnalysis->getDifficultyColor() }} fs-6">
                                    {{ ucfirst($questionAnalysis->difficulty) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sonuç:</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $questionAnalysis->getResultColor() }} fs-6">
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
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Öğrenci Cevabı:</label>
                            <p class="form-control-plaintext">
                                @if($questionAnalysis->student_answer)
                                    <span class="badge bg-info fs-6">{{ $questionAnalysis->student_answer }}</span>
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Doğru Cevap:</label>
                            <p class="form-control-plaintext">
                                @if($questionAnalysis->correct_answer)
                                    <span class="badge bg-success fs-6">{{ $questionAnalysis->correct_answer }}</span>
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Harcanan Süre:</label>
                            <p class="form-control-plaintext">
                                @if($questionAnalysis->time_spent_seconds > 0)
                                    <i class="fas fa-clock me-2"></i>{{ $questionAnalysis->getTimeSpentFormatted() }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Performans:</label>
                            <p class="form-control-plaintext">
                                @if($questionAnalysis->isCorrect())
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-thumbs-up me-1"></i>Başarılı
                                    </span>
                                @elseif($questionAnalysis->isIncorrect())
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-thumbs-down me-1"></i>Başarısız
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-question me-1"></i>Boş
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($questionAnalysis->explanation)
                <div class="mb-3">
                    <label class="form-label fw-bold">Açıklama:</label>
                    <div class="form-control-plaintext bg-light p-3 rounded">
                        {{ $questionAnalysis->explanation }}
                    </div>
                </div>
                @endif
                
                @if($questionAnalysis->notes)
                <div class="mb-3">
                    <label class="form-label fw-bold">Notlar:</label>
                    <div class="form-control-plaintext bg-light p-3 rounded">
                        {{ $questionAnalysis->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Tarih Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Oluşturulma Tarihi:</label>
                    <p class="form-control-plaintext">{{ $questionAnalysis->created_at->format('d.m.Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Son Güncelleme:</label>
                    <p class="form-control-plaintext">{{ $questionAnalysis->updated_at->format('d.m.Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Çözüm Tarihi:</label>
                    <p class="form-control-plaintext">{{ $questionAnalysis->solved_at->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Hızlı İstatistikler
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
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-link me-2"></i>
                    İlgili Kayıtlar
                </h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.topic-tracking.create') }}?student_id={{ $questionAnalysis->student_id }}&topic_id={{ $questionAnalysis->topic_id }}&subtopic_id={{ $questionAnalysis->subtopic_id }}" 
                   class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-tasks me-2"></i>
                    Konu Takibi Ekle
                </a>
                
                <a href="{{ route('admin.question-analysis.create') }}?student_id={{ $questionAnalysis->student_id }}&topic_id={{ $questionAnalysis->topic_id }}&subtopic_id={{ $questionAnalysis->subtopic_id }}" 
                   class="btn btn-outline-success w-100">
                    <i class="fas fa-plus me-2"></i>
                    Benzer Soru Ekle
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
