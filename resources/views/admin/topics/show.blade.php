@extends('admin.layout')

@section('title', 'Konu Detayı')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Konu Detayı
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit me-2"></i>
            Düzenle
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Konu Bilgileri</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konu Adı</label>
                            <p class="form-control-plaintext">{{ $topic->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ders</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $topic->course->name }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <p class="form-control-plaintext">
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" 
                                         style="width: 20px; height: 20px; background-color: {{ $topic->course->category->color }}; border-radius: 4px;"></div>
                                    {{ $topic->course->category->name }}
                                </div>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sıra Numarası</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $topic->order_index }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Süre</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-warning">{{ $topic->duration_minutes }} dakika</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alt Konu Sayısı</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">{{ $topic->subtopics->count() }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($topic->description)
                <div class="mb-3">
                    <label class="form-label fw-bold">Açıklama</label>
                    <p class="form-control-plaintext">{{ $topic->description }}</p>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Oluşturulma Tarihi</label>
                            <p class="form-control-plaintext">{{ $topic->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Son Güncelleme</label>
                            <p class="form-control-plaintext">{{ $topic->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Alt Konular</h5>
            </div>
            <div class="card-body">
                @if($topic->subtopics->count() > 0)
                    <div class="list-group">
                        @foreach($topic->subtopics as $subtopic)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $subtopic->name }}</h6>
                                <small>{{ $subtopic->duration_minutes }} dk</small>
                            </div>
                            @if($subtopic->description)
                                <p class="mb-1 text-muted">{{ Str::limit($subtopic->description, 50) }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-list-ul fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Henüz alt konu eklenmemiş</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">İşlemler</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Düzenle
                    </a>
                    <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" 
                          onsubmit="return confirm('Bu konuyu silmek istediğinizden emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>
                            Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
