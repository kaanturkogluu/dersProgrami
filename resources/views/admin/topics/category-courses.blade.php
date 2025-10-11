@extends('admin.layout')

@section('title', $category->name . ' - Dersler')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <div class="d-flex align-items-center">
            <div class="color-preview me-3" 
                 style="width: 40px; height: 40px; background-color: {{ $category->color }}; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <div>
                <span class="text-dark">{{ $category->name }}</span>
                <br>
                <small class="text-muted">{{ $category->description }}</small>
            </div>
        </div>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.topics.create', ['category_id' => $category->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Konu
        </a>
    </div>
</div>

<!-- Kategori Bilgileri -->
<div class="card mb-4">
    <div class="card-header" style="background: linear-gradient(135deg, {{ $category->color }}20 0%, {{ $category->color }}10 100%); border-left: 4px solid {{ $category->color }};">
        <h5 class="card-title mb-0">Kategori Bilgileri</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-bold">{{ $category->name }}</h6>
                @if($category->description)
                    <p class="text-muted mb-2">{{ $category->description }}</p>
                @endif
                <div class="d-flex align-items-center">
                    <div class="color-preview me-2" 
                         style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 4px;"></div>
                    <span class="text-muted">Kategori Rengi: {{ $category->color }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-primary">{{ $courses->count() }}</div>
                            <div class="stats-label">Toplam Ders</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-success">{{ $courses->sum('topics_count') }}</div>
                            <div class="stats-label">Toplam Konu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dersler Listesi -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Dersler</h5>
    </div>
    <div class="card-body">
        @if($courses->count() > 0)
            <div class="row">
                @foreach($courses as $course)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 course-card" style="cursor: pointer; transition: all 0.3s ease;" 
                         onclick="window.location.href='{{ route('admin.topics.course', $course) }}'">
                        <div class="card-header" style="background: linear-gradient(135deg, {{ $category->color }}20 0%, {{ $category->color }}10 100%); border-left: 4px solid {{ $category->color }};">
                            <div class="d-flex align-items-center">
                                <div class="color-preview me-3" 
                                     style="width: 32px; height: 32px; background-color: {{ $category->color }}; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                                <div>
                                    <h6 class="card-title mb-1 text-dark">{{ $course->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-list me-1"></i>{{ $course->topics_count }} konu
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            @if($course->description)
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($course->description, 80) }}
                            </p>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $course->created_at->format('d.m.Y') }}
                                    </small>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        Konuları Gör
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Bu kategoriye henüz ders eklenmemiş</h4>
                <p class="text-muted mb-4">İlk dersinizi oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Dersi Oluştur
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-item {
    padding: 10px;
}

.stats-number {
    font-size: 1.5rem;
    font-weight: bold;
    line-height: 1;
}

.stats-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 2px;
}
</style>
@endsection
