@extends('admin.layout')

@section('title', $schedule->name . ' - Program Detayı')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <div class="d-flex align-items-center">
            <div class="color-preview me-3" 
                 style="width: 40px; height: 40px; background-color: #3B82F6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <div>
                <span class="text-dark">{{ $schedule->name }}</span>
                <br>
                <small class="text-muted">{{ $schedule->student->full_name }} - {{ implode(', ', $schedule->areas ?? []) }}</small>
            </div>
        </div>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>
            Düzenle
        </a>
        <form action="{{ route('admin.schedules.destroy', $schedule) }}" 
              method="POST" class="d-inline"
              onsubmit="return confirm('Bu programı silmek istediğinizden emin misiniz?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>
                Sil
            </button>
        </form>
    </div>
</div>

<!-- Program Bilgileri -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #3B82F620 0%, #3B82F610 100%); border-left: 4px solid #3B82F6;">
                <h5 class="card-title mb-0">Program Bilgileri</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Program Adı</h6>
                        <p class="text-muted">{{ $schedule->name }}</p>
                        
                        <h6 class="fw-bold">Öğrenci</h6>
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
                        
                        <h6 class="fw-bold mt-3">Alanlar</h6>
                        @if($schedule->areas && count($schedule->areas) > 0)
                            @foreach($schedule->areas as $area)
                                <span class="badge bg-{{ $area == 'TYT' ? 'primary' : ($area == 'AYT' ? 'success' : ($area == 'KPSS' ? 'warning' : ($area == 'DGS' ? 'info' : 'secondary'))) }} me-1">
                                    {{ $area }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted">Alan belirtilmemiş</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Tarih Aralığı</h6>
                        <p class="text-muted">
                            @if($schedule->start_date && $schedule->end_date)
                                {{ $schedule->start_date->format('d.m.Y') }} - {{ $schedule->end_date->format('d.m.Y') }}
                            @else
                                <span class="text-muted">Tarih belirtilmemiş</span>
                            @endif
                        </p>
                        
                        <h6 class="fw-bold">Süre</h6>
                        <span class="badge bg-warning">{{ $schedule->duration_in_days }} gün</span>
                        
                        <h6 class="fw-bold mt-3">Durum</h6>
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
                    </div>
                </div>
                
                @if($schedule->description)
                <div class="mt-3">
                    <h6 class="fw-bold">Açıklama</h6>
                    <p class="text-muted">{{ $schedule->description }}</p>
                </div>
                @endif
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
                        <div class="stats-item">
                            <div class="stats-number text-primary">{{ $schedule->scheduleItems->count() }}</div>
                            <div class="stats-label">Toplam Ders</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-success">{{ $schedule->scheduleItems->where('is_completed', true)->count() }}</div>
                            <div class="stats-label">Tamamlanan</div>
                        </div>
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-warning">{{ $schedule->scheduleItems->where('is_completed', false)->count() }}</div>
                            <div class="stats-label">Bekleyen</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-item">
                            <div class="stats-number text-info">{{ $schedule->scheduleItems->sum('duration_minutes') }}</div>
                            <div class="stats-label">Toplam Dakika</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Haftalık Program -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Haftalık Program</h5>
    </div>
    <div class="card-body">
        @if($weeklySchedule && count($weeklySchedule) > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Gün</th>
                            <th style="width: 25%;">Ders</th>
                            <th style="width: 25%;">Konu</th>
                            <th style="width: 20%;">Alt Konu</th>
                            <th style="width: 15%;">Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeklySchedule as $day => $items)
                            @if(count($items) > 0)
                                @foreach($items as $index => $item)
                                <tr>
                                    @if($index == 0)
                                        <td rowspan="{{ count($items) }}" class="text-center align-middle">
                                            <strong>{{ $item->day_name }}</strong>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->course->name }}</span>
                                    </td>
                                    <td>
                                        @if($item->topic)
                                            <span class="badge bg-info">{{ $item->topic->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->subtopic)
                                            <span class="badge bg-primary">{{ $item->subtopic->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_completed)
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @else
                                            <span class="badge bg-secondary">Bekliyor</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center">
                                        <strong>{{ ucfirst($day) }}</strong>
                                    </td>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-calendar-times me-2"></i>
                                        Bu gün için ders planlanmamış
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Bu program için henüz ders planlanmamış</h4>
                <p class="text-muted mb-4">Programa ders eklemek için düzenle butonuna tıklayın.</p>
                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-edit me-2"></i>
                    Programı Düzenle
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
