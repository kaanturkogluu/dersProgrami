@extends('admin.layout')

@section('title', 'Dersler')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-book me-2"></i>
        Dersler
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Ders
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">Ders Listesi</h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Ders ara..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($courses->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ders</th>
                            <th>Kategori</th>
                            <th>Açıklama</th>
                            <th>Süre</th>
                            <th>Fiyat</th>
                            <th>Konu Sayısı</th>
                            <th>Durum</th>
                            <th>Oluşturulma</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-3" 
                                         style="background-color: {{ $course->category->color }}20; color: {{ $course->category->color }};">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $course->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background-color: {{ $course->category->color }};">
                                    {{ $course->category->name }}
                                </span>
                            </td>
                            <td>
                                @if($course->description)
                                    {{ Str::limit($course->description, 50) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $course->duration_hours }} saat</span>
                            </td>
                            <td>
                                <strong class="text-success">{{ number_format($course->price, 2) }} ₺</strong>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $course->topics_count }}</span>
                            </td>
                            <td>
                                @if($course->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $course->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.courses.show', $course) }}" 
                                       class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.topics.create', ['course_id' => $course->id]) }}" 
                                       class="btn btn-sm btn-outline-success" title="Konu Ekle">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu dersi silmek istediğinizden emin misiniz?')">
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
            @if($courses->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $courses->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz ders eklenmemiş</h4>
                <p class="text-muted mb-4">İlk dersinizi oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-book-medical me-2"></i>
                    İlk Dersi Oluştur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
