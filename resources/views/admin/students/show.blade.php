@extends('admin.layout')

@section('title', 'Öğrenci Detayları')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-graduate me-2"></i>
                Öğrenci Detayları
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Öğrenciler</a></li>
                    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Düzenle
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Student Information -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Öğrenci Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </div>
                        <h4 class="mb-1">{{ $student->full_name }}</h4>
                        <p class="text-muted mb-0">{{ $student->student_number }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <strong>Email:</strong><br>
                                    <a href="mailto:{{ $student->email }}" class="text-decoration-none">{{ $student->email }}</a>
                                </div>
                            </div>
                        </div>

                        @if($student->phone)
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-primary me-3"></i>
                                <div>
                                    <strong>Telefon:</strong><br>
                                    <a href="tel:{{ $student->phone }}" class="text-decoration-none">{{ $student->phone }}</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($student->birth_date)
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-birthday-cake text-primary me-3"></i>
                                <div>
                                    <strong>Doğum Tarihi:</strong><br>
                                    {{ $student->birth_date->format('d.m.Y') }} ({{ $student->birth_date->age }} yaşında)
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($student->address)
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                                <div>
                                    <strong>Adres:</strong><br>
                                    {{ $student->address }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-primary me-3"></i>
                                <div>
                                    <strong>Durum:</strong><br>
                                    @if($student->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Pasif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-primary me-3"></i>
                                <div>
                                    <strong>Kayıt Tarihi:</strong><br>
                                    {{ $student->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>
                        </div>

                        @if($student->admin)
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-tie text-primary me-3"></i>
                                <div>
                                    <strong>Sorumlu Admin:</strong><br>
                                    {{ $student->admin->name }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sticky-note me-2"></i>Öğrenci Notları
                    </h5>
                    <button type="button" class="btn btn-light btn-sm" onclick="toggleEditMode()">
                        <i class="fas fa-edit me-1"></i> Düzenle
                    </button>
                </div>
                <div class="card-body">
                    <!-- Display Mode -->
                    <div id="displayMode">
                        @if($student->notes)
                            <div class="notes-content">
                                {!! nl2br(e($student->notes)) !!}
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-sticky-note fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">Henüz not eklenmemiş.</p>
                                <small>Düzenle butonuna tıklayarak not ekleyebilirsiniz.</small>
                            </div>
                        @endif
                    </div>

                    <!-- Edit Mode -->
                    <div id="editMode" style="display: none;">
                        <form id="notesForm" action="{{ route('admin.students.update', $student) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="from_show" value="1">
                            <div class="mb-3">
                                <textarea 
                                    name="notes" 
                                    id="notes" 
                                    class="form-control" 
                                    rows="12" 
                                    placeholder="Öğrenci hakkında notlarınızı buraya yazabilirsiniz..."
                                    style="resize: vertical; min-height: 200px;"
                                    data-original-value="{{ $student->notes ?? '' }}">{{ old('notes', $student->notes) }}</textarea>
                                @error('notes')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                    <i class="fas fa-times me-1"></i> İptal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Student Programs -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Öğrenci Programları
                    </h5>
                </div>
                <div class="card-body">
                    @if($student->schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Program Adı</th>
                                        <th>Alan</th>
                                        <th>Durum</th>
                                        <th>Başlangıç</th>
                                        <th>Bitiş</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->schedules as $schedule)
                                    <tr>
                                        <td>
                                            <strong>{{ $schedule->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $schedule->area }}</span>
                                        </td>
                                        <td>
                                            @if($schedule->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Pasif</span>
                                            @endif
                                        </td>
                                        <td>{{ $schedule->start_date->format('d.m.Y') }}</td>
                                        <td>{{ $schedule->end_date->format('d.m.Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                            <p class="mb-0">Bu öğrenci için henüz program oluşturulmamış.</p>
                            <a href="{{ route('admin.schedules.create') }}?student_id={{ $student->id }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Program Oluştur
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    document.getElementById('displayMode').style.display = 'none';
    document.getElementById('editMode').style.display = 'block';
    document.getElementById('notes').focus();
}

function cancelEdit() {
    document.getElementById('displayMode').style.display = 'block';
    document.getElementById('editMode').style.display = 'none';
    // Reset form to original value
    const notesField = document.getElementById('notes');
    notesField.value = notesField.getAttribute('data-original-value');
}

// Auto-save functionality (optional)
let autoSaveTimeout;
document.getElementById('notes').addEventListener('input', function() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(function() {
        // You can implement auto-save here if needed
    }, 2000);
});
</script>

<style>
.notes-content {
    line-height: 1.6;
    font-size: 0.95rem;
}

.avatar-lg {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    border: none;
}

.btn-group .btn {
    border-radius: 8px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #374151;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
}
</style>
@endsection
