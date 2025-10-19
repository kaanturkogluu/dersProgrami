@extends('admin.layout')

@section('title', $student->full_name . ' - Soru Analizi Detayı')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-graduate me-2"></i>
        {{ $student->full_name }} - Soru Analizi Detayı
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.question-analysis.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.question-analysis.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Soru Analizi
        </a>
    </div>
</div>

<!-- Öğrenci İstatistikleri -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $stats['total_questions'] }}</h3>
                <p class="card-text mb-0">Toplam Soru</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $stats['correct_answers'] }}</h3>
                <p class="card-text mb-0">Doğru</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $stats['incorrect_answers'] }}</h3>
                <p class="card-text mb-0">Yanlış</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $stats['empty_answers'] }}</h3>
                <p class="card-text mb-0">Boş</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="card-title">{{ $stats['net'] }}</h3>
                <p class="card-text mb-0">Net</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <h3 class="card-title">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $stats['star_rating'] ? $stats['star_color'] : 'text-muted' }}"></i>
                    @endfor
                </h3>
                <p class="card-text mb-0">Performans</p>
            </div>
        </div>
    </div>
</div>

<!-- Başarı Oranı ve Ortalama Süre -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>
                    Başarı Oranı
                </h5>
                <div class="progress mb-2" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $stats['success_rate'] }}%" 
                         aria-valuenow="{{ $stats['success_rate'] }}" 
                         aria-valuemin="0" aria-valuemax="100">
                        {{ $stats['success_rate'] }}%
                    </div>
                </div>
                <small class="text-muted">
                    {{ $stats['correct_answers'] }} doğru / {{ $stats['total_questions'] }} toplam
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-clock me-2"></i>
                    Ortalama Süre
                </h5>
                <h3 class="text-primary">
                    @if($stats['average_time'])
                        @php
                            $avgMinutes = floor($stats['average_time'] / 60);
                            $avgSeconds = $stats['average_time'] % 60;
                        @endphp
                        @if($avgMinutes > 0)
                            {{ $avgMinutes }}dk {{ $avgSeconds }}sn
                        @else
                            {{ $avgSeconds }}sn
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </h3>
                <small class="text-muted">Soru başına ortalama süre</small>
            </div>
        </div>
    </div>
</div>

<!-- Soru Analizi Listesi -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            {{ $student->full_name }} - Soru Analizi Listesi
        </h5>
    </div>
    <div class="card-body">
        @if($questionAnalyses->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
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
                    {{ $student->full_name }} - Toplam {{ $questionAnalyses->total() }} kayıt, {{ $questionAnalyses->currentPage() }}. sayfa
                </div>
                {{ $questionAnalyses->links() }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-question-circle fa-3x mb-3"></i>
                <h5>Henüz soru analizi bulunmuyor</h5>
                <p>Bu öğrenci için henüz soru analizi kaydı bulunmuyor.</p>
                <a href="{{ route('admin.question-analysis.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    İlk Soru Analizini Ekle
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
