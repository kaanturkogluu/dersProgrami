@extends('admin.layout')

@section('title', $category->name . ' - Alt Konular')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <div class="d-flex align-items-center">
            <div class="color-preview me-3" 
                 style="width: 40px; height: 40px; background-color: {{ $category->color }}; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-list-ul text-white"></i>
            </div>
            <div>
                <span class="text-dark">{{ $category->name }}</span>
                <br>
                <small class="text-muted">{{ $category->description }}</small>
            </div>
        </div>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.subtopics.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.subtopics.create', ['category_id' => $category->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Alt Konu
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
                            <div class="stats-number text-primary">{{ $subtopics->total() }}</div>
                            <div class="stats-label">Toplam Alt Konu</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-success">{{ $subtopics->count() }}</div>
                            <div class="stats-label">Bu Sayfada</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alt Konular Listesi -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Alt Konular</h5>
    </div>
    <div class="card-body">
        @if($subtopics->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Alt Konu Adı</th>
                            <th>Konu</th>
                            <th>Ders</th>
                            <th>Açıklama</th>
                            <th>Sıra</th>
                            <th>Süre (dk)</th>
                            <th>Oluşturulma</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subtopics as $subtopic)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-2" 
                                         style="background-color: #10B98120; color: #10B981;">
                                        <i class="fas fa-list-ul"></i>
                                    </div>
                                    <strong>{{ $subtopic->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $subtopic->topic->name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $subtopic->topic->course->name }}</span>
                            </td>
                            <td>
                                @if($subtopic->description)
                                    {{ Str::limit($subtopic->description, 50) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $subtopic->order_index }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $subtopic->duration_minutes }} dk</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $subtopic->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.subtopics.show', $subtopic) }}" 
                                       class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subtopics.edit', $subtopic) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.subtopics.destroy', $subtopic) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu alt konuyu silmek istediğinizden emin misiniz?')">
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
            <div class="d-flex justify-content-center mt-4">
                <div class="pagination-container">
                    <div class="pagination-info">
                        {{ $category->name }} - Toplam {{ $subtopics->total() }} kayıt, {{ $subtopics->currentPage() }}. sayfa
                    </div>
                    {{ $subtopics->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-list-ul fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Bu kategoriye henüz alt konu eklenmemiş</h4>
                <p class="text-muted mb-4">İlk alt konunuzu oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.subtopics.create', ['category_id' => $category->id]) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Alt Konuyu Oluştur
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
