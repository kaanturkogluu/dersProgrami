@extends('admin.layout')

@section('title', 'Program Şablonları')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-copy me-2"></i>
                Program Şablonları
            </h1>
            <p class="text-muted mb-0">Program şablonlarını yönetin ve yeni programlarda kullanın</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Şablon
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Şablonlar Listesi -->
    <div class="row">
        @forelse($templates as $template)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">{{ $template->name }}</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.templates.show', $template) }}">
                                    <i class="fas fa-eye me-2"></i>Görüntüle
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.templates.edit', $template) }}">
                                    <i class="fas fa-edit me-2"></i>Düzenle
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bu şablonu silmek istediğinizden emin misiniz?')">
                                        <i class="fas fa-trash me-2"></i>Sil
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @if($template->description)
                        <p class="card-text text-muted small">{{ Str::limit($template->description, 100) }}</p>
                    @endif
                    
                    <div class="mb-3">
                        <small class="text-muted">Alanlar:</small>
                        <div class="mt-1">
                            @foreach($template->areas as $area)
                                <span class="badge bg-primary me-1">{{ $area }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h5 mb-0 text-primary">{{ $template->items_count }}</div>
                                <small class="text-muted">Ders</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 text-success">{{ count($template->areas) }}</div>
                            <small class="text-muted">Alan</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $template->created_at->format('d.m.Y') }}
                        </small>
                        <a href="{{ route('admin.schedules.create', ['template_id' => $template->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>
                            Kullan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-copy fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Henüz şablon oluşturulmamış</h5>
                    <p class="text-muted">İlk program şablonunuzu oluşturmak için "Yeni Şablon" butonuna tıklayın.</p>
                    <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Yeni Şablon Oluştur
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Sayfalama -->
    @if($templates->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $templates->links() }}
        </div>
    @endif
</div>
@endsection