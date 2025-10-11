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
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="areas" class="form-label">Alanlar <span class="text-danger">*</span></label>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Alan Seçimi:</strong> Birden fazla alan seçebilirsiniz.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="TYT" id="area_tyt" name="areas[]"
                                           {{ in_array('TYT', old('areas', $schedule->areas ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_tyt">
                                        <strong>TYT</strong> - Temel Yeterlilik Testi
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="EA" id="area_ea" name="areas[]"
                                           {{ in_array('EA', old('areas', $schedule->areas ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_ea">
                                        <strong>EA</strong> - Eşit Ağırlık (TYT + AYT EA)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="SAY" id="area_say" name="areas[]"
                                           {{ in_array('SAY', old('areas', $schedule->areas ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_say">
                                        <strong>SAY</strong> - Sayısal (TYT + AYT Sayısal)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="SOZ" id="area_soz" name="areas[]"
                                           {{ in_array('SOZ', old('areas', $schedule->areas ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_soz">
                                        <strong>SÖZ</strong> - Sözel (TYT + AYT Sözel)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="DIL" id="area_dil" name="areas[]"
                                           {{ in_array('DIL', old('areas', $schedule->areas ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_dil">
                                        <strong>DİL</strong> - Dil (TYT + YDT)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input area-checkbox" type="checkbox" value="KPSS" id="area_kpss" name="areas[]"
                                           {{ in_array('KPSS', old('areas', $schedule->areas ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_kpss">
                                        <strong>KPSS</strong> - Kamu Personeli Seçme Sınavı
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('areas')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('areas.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
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
                            <th>Ders</th>
                            <th>Konu</th>
                            <th>Alt Konu</th>
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
                                @if($item->subtopic)
                                    <span class="badge bg-warning">{{ $item->subtopic->name }}</span>
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
