@extends('admin.layout')

@section('title', 'Soru Analizi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-question-circle me-2"></i>
        Soru Analizi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.question-analysis.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Soru Analizi
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@php
    $totalQuestions = $questionAnalyses->sum(function($q) { return $q->getTotalQuestions(); });
    $correctAnswers = $questionAnalyses->sum(function($q) { return $q->getCorrectCount(); });
    $incorrectAnswers = $questionAnalyses->sum(function($q) { return $q->getIncorrectCount(); });
    $emptyAnswers = $questionAnalyses->sum(function($q) { return $q->getEmptyCount(); });
    $net = \App\Models\QuestionAnalysis::calculateNet($correctAnswers, $incorrectAnswers);
    $starRating = \App\Models\QuestionAnalysis::getStarRating($correctAnswers, $totalQuestions);
    $starColor = \App\Models\QuestionAnalysis::getStarColor($starRating);
@endphp

<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalQuestions }}</h4>
                        <p class="card-text">Toplam Soru</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-question fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $correctAnswers }}</h4>
                        <p class="card-text">Doğru</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $incorrectAnswers }}</h4>
                        <p class="card-text">Yanlış</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $emptyAnswers }}</h4>
                        <p class="card-text">Boş</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($net, 1) }}</h4>
                        <p class="card-text">Net</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $starRating ? $starColor : 'text-muted' }}"></i>
                            @endfor
                        </h4>
                        <p class="card-text">Performans</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x {{ $starColor }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Soru Analizi Listesi
        </h5>
    </div>
    <div class="card-body">
        @if($questionAnalyses->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Öğrenci</th>
                            <th>Konu</th>
                            <th>Alt Konu</th>
                            <th>Kaynak</th>
                            <th>Zorluk</th>
                            <th>Soru Sayısı</th>
                            <th>Doğru/Yanlış/Boş</th>
                            <th>Net</th>
                            <th>Performans</th>
                            <th>Tarih</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questionAnalyses as $analysis)
                        <tr>
                            <td>
                                <a href="{{ route('admin.question-analysis.student.detailed', $analysis->student) }}" 
                                   class="text-decoration-none">
                                    <strong>{{ $analysis->student->full_name }}</strong>
                                    <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.8rem;"></i>
                                </a>
                            </td>
                            <td>
                                <strong>{{ $analysis->topic->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $analysis->topic->course->name }}</small>
                            </td>
                            <td>
                                @if($analysis->subtopic)
                                    {{ $analysis->subtopic->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($analysis->question_source)
                                    {{ $analysis->question_source }}
                                    @if($analysis->question_year)
                                        <br>
                                        <small class="text-muted">{{ $analysis->question_year }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $analysis->getDifficultyColor() }}">
                                    {{ ucfirst($analysis->difficulty) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $analysis->getTotalQuestions() }}</strong>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="badge bg-success mb-1">{{ $analysis->getCorrectCount() }} Doğru</span>
                                    <span class="badge bg-danger mb-1">{{ $analysis->getIncorrectCount() }} Yanlış</span>
                                    <span class="badge bg-warning">{{ $analysis->getEmptyCount() }} Boş</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $analysis->getNet() > 0 ? 'success' : ($analysis->getNet() < 0 ? 'danger' : 'secondary') }}">
                                    {{ $analysis->getNet() > 0 ? '+' : '' }}{{ number_format($analysis->getNet(), 1) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $analysis->getInstanceStarRating() ? $analysis->getStarColorClass() : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                {{ $analysis->solved_at->format('d.m.Y') }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.question-analysis.show', $analysis) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.question-analysis.edit', $analysis) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.question-analysis.destroy', $analysis) }}" method="POST" 
                                          style="display: inline;" onsubmit="return confirm('Bu soru analizini silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Toplam {{ $questionAnalyses->total() }} kayıt, {{ $questionAnalyses->currentPage() }}. sayfa
                </div>
                {{ $questionAnalyses->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-question-circle fa-3x mb-3"></i>
                <h5>Henüz soru analizi bulunmuyor</h5>
                <p>Öğrencilerin soru çözüm performanslarını takip etmek için soru analizi ekleyin.</p>
                <a href="{{ route('admin.question-analysis.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Yeni Soru Analizi Ekle
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
