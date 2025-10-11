@extends('admin.layout')

@section('title', 'Kategoriler')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tags me-2"></i>
        Kategoriler
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Kategori
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Kategori Listesi</h5>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kategori Adı</th>
                            <th>Açıklama</th>
                            <th>Renk</th>
                            <th>Ders Sayısı</th>
                            <th>Durum</th>
                            <th>Oluşturulma</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-2" 
                                         style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <strong>{{ $category->name }}</strong>
                                </div>
                            </td>
                            <td>
                                @if($category->description)
                                    {{ Str::limit($category->description, 50) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="color-preview me-2" 
                                         style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 4px;"></div>
                                    <code>{{ $category->color }}</code>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->courses_count }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $category->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')">
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
                <i class="fas fa-tags fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz kategori eklenmemiş</h4>
                <p class="text-muted mb-4">İlk kategorinizi oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Kategoriyi Oluştur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
