@extends('admin.layout')

@section('title', 'Alt Konu Detayı')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Alt Konu Detayı
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.subtopics.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.subtopics.edit', $subtopic) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit me-2"></i>
            Düzenle
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Alt Konu Bilgileri</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alt Konu Adı</label>
                            <p class="form-control-plaintext">{{ $subtopic->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konu</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $subtopic->topic->name }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ders</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $subtopic->topic->course->name }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <p class="form-control-plaintext">
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" 
                                         style="width: 20px; height: 20px; background-color: {{ $subtopic->topic->course->category->color }}; border-radius: 4px;"></div>
                                    {{ $subtopic->topic->course->category->name }}
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sıra Numarası</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $subtopic->order_index }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Süre</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-warning">{{ $subtopic->duration_minutes }} dakika</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($subtopic->description)
                <div class="mb-3">
                    <label class="form-label fw-bold">Açıklama</label>
                    <p class="form-control-plaintext">{{ $subtopic->description }}</p>
                </div>
                @endif
                
                @if($subtopic->content)
                <div class="mb-3">
                    <label class="form-label fw-bold">İçerik</label>
                    <div class="form-control-plaintext bg-light p-3 rounded">
                        {!! nl2br(e($subtopic->content)) !!}
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Oluşturulma Tarihi</label>
                            <p class="form-control-plaintext">{{ $subtopic->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Son Güncelleme</label>
                            <p class="form-control-plaintext">{{ $subtopic->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">İşlemler</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.subtopics.edit', $subtopic) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Düzenle
                    </a>
                    <form action="{{ route('admin.subtopics.destroy', $subtopic) }}" method="POST" 
                          onsubmit="return confirm('Bu alt konuyu silmek istediğinizden emin misiniz?')">
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
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">İlişkili Bilgiler</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Konu</label>
                    <p class="form-control-plaintext">
                        <a href="{{ route('admin.topics.show', $subtopic->topic) }}" class="text-decoration-none">
                            {{ $subtopic->topic->name }}
                        </a>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Ders</label>
                    <p class="form-control-plaintext">
                        <a href="{{ route('admin.courses.show', $subtopic->topic->course) }}" class="text-decoration-none">
                            {{ $subtopic->topic->course->name }}
                        </a>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <p class="form-control-plaintext">
                        <a href="{{ route('admin.categories.show', $subtopic->topic->course->category) }}" class="text-decoration-none">
                            {{ $subtopic->topic->course->category->name }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
