@extends('admin.layout')

@section('title', 'Şablon Detayı')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-copy me-2"></i>
                {{ $template->name }}
            </h1>
            <p class="text-muted mb-0">Program şablonu detayları</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.schedules.create', ['template_id' => $template->id]) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Bu Şablonu Kullan
            </a>
            <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>
                Düzenle
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Şablon Bilgileri -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Şablon Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Şablon Adı:</label>
                                <p class="mb-0">{{ $template->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Oluşturulma Tarihi:</label>
                                <p class="mb-0">{{ $template->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($template->description)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Açıklama:</label>
                        <p class="mb-0">{{ $template->description }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alanlar:</label>
                        <div>
                            @foreach($template->areas as $area)
                                <span class="badge bg-primary me-1">{{ $area }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">İstatistikler</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 text-primary">{{ $template->items_count }}</div>
                                <small class="text-muted">Toplam Ders</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">{{ count($template->areas) }}</div>
                            <small class="text-muted">Alan Sayısı</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <div class="h4 mb-0 text-info">{{ $template->schedule_items->groupBy('day_of_week')->count() }}</div>
                        <small class="text-muted">Gün Sayısı</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Öğeleri -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Program Öğeleri</h5>
        </div>
        <div class="card-body">
            @if($template->schedule_items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="15%">Gün</th>
                                <th width="25%">Ders</th>
                                <th width="20%">Konu</th>
                                <th width="20%">Alt Konu</th>
                                <th width="15%">Notlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($template->schedule_items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item['day_name'] ?? ucfirst($item['day_of_week']) }}</span>
                                </td>
                                <td>
                                    @if(isset($item['course']))
                                        {{ $item['course']['name'] }}
                                    @else
                                        <span class="text-muted">Ders bulunamadı</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($item['topic']) && $item['topic'])
                                        {{ $item['topic']['name'] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($item['subtopic']) && $item['subtopic'])
                                        {{ $item['subtopic']['name'] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($item['notes']) && $item['notes'])
                                        {{ $item['notes'] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Bu şablonda henüz ders bulunmuyor</h5>
                    <p class="text-muted">Şablonu düzenleyerek ders ekleyebilirsiniz.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection