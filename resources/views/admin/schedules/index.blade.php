@extends('admin.layout')

@section('title', 'Haftalık Programlar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-alt me-2"></i>
        Haftalık Programlar
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Program
        </a>
    </div>
</div>

<!-- Alan Filtreleri -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Alan Filtreleri</h6>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.schedules.index') }}" 
                       class="btn {{ !request('area') ? 'btn-primary' : 'btn-outline-primary' }}">
                        Tümü
                    </a>
                    <a href="{{ route('admin.schedules.area', 'TYT') }}" 
                       class="btn {{ request('area') == 'TYT' ? 'btn-primary' : 'btn-outline-primary' }}">
                        TYT
                    </a>
                    <a href="{{ route('admin.schedules.area', 'AYT') }}" 
                       class="btn {{ request('area') == 'AYT' ? 'btn-primary' : 'btn-outline-primary' }}">
                        AYT
                    </a>
                    <a href="{{ route('admin.schedules.area', 'KPSS') }}" 
                       class="btn {{ request('area') == 'KPSS' ? 'btn-primary' : 'btn-outline-primary' }}">
                        KPSS
                    </a>
                    <a href="{{ route('admin.schedules.area', 'DGS') }}" 
                       class="btn {{ request('area') == 'DGS' ? 'btn-primary' : 'btn-outline-primary' }}">
                        DGS
                    </a>
                    <a href="{{ route('admin.schedules.area', 'ALES') }}" 
                       class="btn {{ request('area') == 'ALES' ? 'btn-primary' : 'btn-outline-primary' }}">
                        ALES
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Program Listesi</h5>
    </div>
    <div class="card-body">
        @if($schedules->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Program Adı</th>
                            <th>Öğrenci</th>
                            <th>Alan</th>
                            <th>Tarih Aralığı</th>
                            <th>Süre</th>
                            <th>Ders Sayısı</th>
                            <th>Durum</th>
                            <th>Oluşturulma</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-2" 
                                         style="background-color: #3B82F620; color: #3B82F6;">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $schedule->name }}</strong>
                                        @if($schedule->description)
                                            <br><small class="text-muted">{{ Str::limit($schedule->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $schedule->student->full_name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $schedule->area }}</span>
                            </td>
                            <td>
                                <small>
                                    {{ $schedule->start_date->format('d.m.Y') }} - {{ $schedule->end_date->format('d.m.Y') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $schedule->duration_in_days }} gün</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $schedule->scheduleItems->count() }}</span>
                            </td>
                            <td>
                                @if($schedule->status == 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($schedule->status == 'upcoming')
                                    <span class="badge bg-info">Yaklaşan</span>
                                @else
                                    <span class="badge bg-secondary">Tamamlandı</span>
                                @endif
                                @if(!$schedule->is_active)
                                    <br><small class="text-muted">Pasif</small>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $schedule->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.schedules.show', $schedule) }}" 
                                       class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu programı silmek istediğinizden emin misiniz?')">
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
                        Toplam {{ $schedules->total() }} kayıt, {{ $schedules->currentPage() }}. sayfa
                    </div>
                    {{ $schedules->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz program oluşturulmamış</h4>
                <p class="text-muted mb-4">İlk haftalık programınızı oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Programı Oluştur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
