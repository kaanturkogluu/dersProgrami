@extends('admin.layout')

@section('title', $area . ' - Haftalık Programlar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <div class="d-flex align-items-center">
            <div class="color-preview me-3" 
                 style="width: 40px; height: 40px; background-color: #3B82F6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <div>
                <span class="text-dark">{{ $area }} Haftalık Programlar</span>
                <br>
                <small class="text-muted">{{ $area }} alanına ait tüm haftalık programlar</small>
            </div>
        </div>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Program
        </a>
    </div>
</div>

<!-- Alan Bilgileri -->
<div class="card mb-4">
    <div class="card-header" style="background: linear-gradient(135deg, #3B82F620 0%, #3B82F610 100%); border-left: 4px solid #3B82F6;">
        <h5 class="card-title mb-0">Alan Bilgileri</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-bold">{{ $area }} Alanı</h6>
                <p class="text-muted mb-2">{{ $area }} alanına ait haftalık ders programları listelenmektedir.</p>
                <div class="d-flex align-items-center">
                    <div class="color-preview me-2" 
                         style="width: 20px; height: 20px; background-color: #3B82F6; border-radius: 4px;"></div>
                    <span class="text-muted">Alan Rengi: #3B82F6</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-primary">{{ $schedules->total() }}</div>
                            <div class="stats-label">Toplam Program</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-success">{{ $schedules->count() }}</div>
                            <div class="stats-label">Bu Sayfada</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Programlar Listesi -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ $area }} Programları</h5>
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
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-2" 
                                         style="background-color: #10B98120; color: #10B981;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $schedule->student->full_name }}</strong>
                                        <br><small class="text-muted">{{ $schedule->student->student_number }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $schedule->area }}</span>
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
                {{ $schedules->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">{{ $area }} alanında henüz program oluşturulmamış</h4>
                <p class="text-muted mb-4">İlk haftalık programınızı oluşturmak için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Programı Oluştur
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
