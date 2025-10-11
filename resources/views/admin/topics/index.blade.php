@extends('admin.layout')

@section('title', 'Konular')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-list me-2"></i>
        Konular
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topics.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Konu
        </a>
    </div>
</div>

@if($categories->count() > 0)
    <div class="row">
        @foreach($categories as $category)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 category-card" style="cursor: pointer; transition: all 0.3s ease;" 
                 onclick="window.location.href='{{ route('admin.topics.category', $category) }}'">
                <div class="card-header" style="background: linear-gradient(135deg, {{ $category->color }}20 0%, {{ $category->color }}10 100%); border-left: 4px solid {{ $category->color }};">
                    <div class="d-flex align-items-center">
                        <div class="color-preview me-3" 
                             style="width: 40px; height: 40px; background-color: {{ $category->color }}; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div>
                            <h4 class="card-title mb-1 text-dark">{{ $category->name }}</h4>
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i>{{ $category->courses->count() }} ders
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="stats-item">
                                <div class="stats-number text-primary">{{ $category->courses->sum('topics_count') }}</div>
                                <div class="stats-label">Toplam Konu</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-item">
                                <div class="stats-number text-success">{{ $category->courses->count() }}</div>
                                <div class="stats-label">Ders Sayısı</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($category->description)
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($category->description, 100) }}
                    </p>
                    @endif
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $category->created_at->format('d.m.Y') }}
                            </small>
                            <span class="badge bg-primary">
                                <i class="fas fa-arrow-right me-1"></i>
                                Dersleri Gör
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body">
            <div class="text-center py-5">
                <i class="fas fa-graduation-cap fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz kategori eklenmemiş</h4>
                <p class="text-muted mb-4">İlk kategorinizi oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Kategoriyi Oluştur
                </a>
            </div>
        </div>
    </div>
@endif

<style>
.category-card:hover {
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
