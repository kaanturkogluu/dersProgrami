@extends('admin.layout')

@section('title', $schedule->name . ' - Program Düzenle')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <div class="d-flex align-items-center">
            <div class="color-preview me-3" 
                 style="width: 40px; height: 40px; background-color: #3B82F6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-edit text-white"></i>
            </div>
            <div>
                <span class="text-dark">{{ $schedule->name }} - Düzenle</span>
                <br>
                <small class="text-muted">Haftalık program düzenleme</small>
            </div>
        </div>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Program Bilgilerini Düzenle</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Program Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $schedule->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Öğrenci <span class="text-danger">*</span></label>
                        <select class="form-select @error('student_id') is-invalid @enderror" 
                                id="student_id" name="student_id" required>
                            <option value="">Öğrenci Seçin</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                        {{ old('student_id', $schedule->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->full_name }} ({{ $student->student_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="area" class="form-label">Alan <span class="text-danger">*</span></label>
                        <select class="form-select @error('area') is-invalid @enderror" 
                                id="area" name="area" required>
                            <option value="">Alan Seçin</option>
                            <option value="TYT" {{ old('area', $schedule->area) == 'TYT' ? 'selected' : '' }}>TYT</option>
                            <option value="AYT" {{ old('area', $schedule->area) == 'AYT' ? 'selected' : '' }}>AYT</option>
                            <option value="KPSS" {{ old('area', $schedule->area) == 'KPSS' ? 'selected' : '' }}>KPSS</option>
                            <option value="DGS" {{ old('area', $schedule->area) == 'DGS' ? 'selected' : '' }}>DGS</option>
                            <option value="ALES" {{ old('area', $schedule->area) == 'ALES' ? 'selected' : '' }}>ALES</option>
                        </select>
                        @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Bitiş Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date', $schedule->end_date->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $schedule->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Program Aktif
                    </label>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-secondary me-2">
                    <i class="fas fa-times me-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Mevcut Program Öğeleri -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Mevcut Program Öğeleri</h5>
    </div>
    <div class="card-body">
        @if($schedule->scheduleItems->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Gün</th>
                            <th>Saat</th>
                            <th>Ders</th>
                            <th>Konu</th>
                            <th>Süre</th>
                            <th>Durum</th>
                            <th>Notlar</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedule->scheduleItems as $item)
                        <tr>
                            <td>
                                <span class="badge bg-info">{{ $item->day_name }}</span>
                            </td>
                            <td>
                                <small>{{ $item->start_time->format('H:i') }} - {{ $item->end_time->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->course->name }}</span>
                            </td>
                            <td>
                                @if($item->topic)
                                    <span class="badge bg-primary">{{ $item->topic->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $item->duration_formatted }}</span>
                            </td>
                            <td>
                                @if($item->is_completed)
                                    <span class="badge bg-success">Tamamlandı</span>
                                @else
                                    <span class="badge bg-secondary">Bekliyor</span>
                                @endif
                            </td>
                            <td>
                                @if($item->notes)
                                    <small class="text-muted">{{ Str::limit($item->notes, 30) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('admin.schedule-items.destroy', $item) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu program öğesini silmek istediğinizden emin misiniz?')">
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
            <div class="text-center py-4">
                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Bu program için henüz ders planlanmamış</h5>
                <p class="text-muted">Programa ders eklemek için yeni program öğesi oluşturun.</p>
            </div>
        @endif
    </div>
</div>
@endsection
