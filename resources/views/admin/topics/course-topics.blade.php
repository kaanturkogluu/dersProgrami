@extends('admin.layout')

@section('title', $course->name . ' - Konular')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <div class="d-flex align-items-center">
            <div class="color-preview me-3" 
                 style="width: 32px; height: 32px; background-color: {{ $course->category->color }}; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-book text-white"></i>
            </div>
            <div>
                <span class="text-dark">{{ $course->name }}</span>
                <br>
                <small class="text-muted">
                    <i class="fas fa-tag me-1"></i>{{ $course->category->name }}
                </small>
            </div>
        </div>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.topics.create', ['course_id' => $course->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Konu
        </a>
    </div>
</div>

<!-- Ders Bilgileri -->
<div class="card mb-4">
    <div class="card-header" style="background: linear-gradient(135deg, {{ $course->category->color }}20 0%, {{ $course->category->color }}10 100%); border-left: 4px solid {{ $course->category->color }};">
        <h5 class="card-title mb-0">Ders Bilgileri</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-bold">{{ $course->name }}</h6>
                @if($course->description)
                    <p class="text-muted mb-2">{{ $course->description }}</p>
                @endif
                <div class="d-flex align-items-center">
                    <div class="color-preview me-2" 
                         style="width: 16px; height: 16px; background-color: {{ $course->category->color }}; border-radius: 4px;"></div>
                    <span class="text-muted">{{ $course->category->name }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-primary">{{ $topics->count() }}</div>
                            <div class="stats-label">Toplam Konu</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-success">{{ $topics->sum('duration_minutes') }}</div>
                            <div class="stats-label">Toplam Süre (dk)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Konular Listesi -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Konular</h5>
    </div>
    <div class="card-body">
        @if($topics->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Sıra</th>
                            <th style="width: 35%;">Konu Adı</th>
                            <th style="width: 25%;">Açıklama</th>
                            <th style="width: 10%;">Süre</th>
                            <th style="width: 10%;">Alt Konular</th>
                            <th style="width: 10%;">Oluşturulma</th>
                            <th style="width: 15%;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topics as $topic)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $topic->order_index }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-2" 
                                         style="background-color: #3B82F620; color: #3B82F6;">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <strong>{{ $topic->name }}</strong>
                                </div>
                            </td>
                            <td>
                                @if($topic->description)
                                    {{ Str::limit($topic->description, 60) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $topic->duration_minutes }} dk</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $topic->subtopics_count }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $topic->created_at->format('d.m.Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.topics.show', $topic) }}" 
                                       class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.topics.edit', $topic) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.topics.destroy', $topic) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu konuyu silmek istediğinizden emin misiniz?')">
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
        @else
            <div class="text-center py-5">
                <i class="fas fa-list fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Bu derse henüz konu eklenmemiş</h4>
                <p class="text-muted mb-4">İlk konunuzu oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.topics.create', ['course_id' => $course->id]) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Konuyu Oluştur
                </a>
            </div>
        @endif
    </div>
</div>

<style>
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
