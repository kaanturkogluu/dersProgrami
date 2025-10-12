@extends('admin.layout')

@section('title', 'Öğrenci Programları')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i>
                Öğrenci Programları
            </h1>
            <p class="text-muted mb-0">Programı olan öğrencileri görüntüleyin ve programlarını inceleyin</p>
        </div>
        <div>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Program
            </a>
        </div>
    </div>

    <!-- Program Türü Filtreleri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Program Türü Filtreleri</h6>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.programs.students') }}" 
                           class="btn {{ !$areaFilter ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-list me-1"></i>
                            Tümü
                        </a>
                        <a href="{{ route('admin.programs.students', ['area' => 'TYT']) }}" 
                           class="btn {{ $areaFilter == 'TYT' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-graduation-cap me-1"></i>
                            TYT
                        </a>
                        <a href="{{ route('admin.programs.students', ['area' => 'AYT']) }}" 
                           class="btn {{ $areaFilter == 'AYT' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-university me-1"></i>
                            AYT
                        </a>
                        <a href="{{ route('admin.programs.students', ['area' => 'KPSS']) }}" 
                           class="btn {{ $areaFilter == 'KPSS' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-briefcase me-1"></i>
                            KPSS
                        </a>
                        <a href="{{ route('admin.programs.students', ['area' => 'DGS']) }}" 
                           class="btn {{ $areaFilter == 'DGS' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-chart-line me-1"></i>
                            DGS
                        </a>
                        <a href="{{ route('admin.programs.students', ['area' => 'ALES']) }}" 
                           class="btn {{ $areaFilter == 'ALES' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-book me-1"></i>
                            ALES
                        </a>
                    </div>
                    @if($areaFilter)
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-filter me-1"></i>
                                <strong>{{ $areaFilter }}</strong> program türüne göre filtreleniyor
                                <a href="{{ route('admin.programs.students') }}" class="ms-2 text-decoration-none">
                                    <i class="fas fa-times"></i> Filtreyi Kaldır
                                </a>
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Öğrenci
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allStudents->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Toplam Program
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allStudents->sum(function($student) { return $student->schedules->count(); }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aktif Program
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allStudents->sum(function($student) { return $student->schedules->where('is_active', true)->count(); }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ortalama Program
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allStudents->count() > 0 ? round($allStudents->sum(function($student) { return $student->schedules->count(); }) / $allStudents->count(), 1) : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtrelenmiş Sonuçlar Bilgisi -->
    @if($areaFilter && $students->count() > 0)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>{{ $areaFilter }}</strong> program türü için <strong>{{ $students->count() }}</strong> öğrenci bulundu.
                </div>
            </div>
        </div>
    @endif

    <!-- Öğrenci Kartları -->
    @if($students->count() > 0)
        <div class="row">
            @foreach($students as $student)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 student-card" style="cursor: pointer; transition: all 0.3s ease;" 
                     data-href="{{ route('admin.programs.student.calendar', $student) }}">
                    <div class="card-header d-flex align-items-center">
                        <div class="student-avatar me-3">
                            <div class="avatar-circle">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{{ $student->full_name }}</h5>
                            <small class="text-muted">
                                <i class="fas fa-id-card me-1"></i>
                                {{ $student->student_number }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="stats-item">
                                    <div class="stats-number text-primary">{{ $student->schedules->count() }}</div>
                                    <div class="stats-label">Toplam Program</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-item">
                                    <div class="stats-number text-success">{{ $student->schedules->where('is_active', true)->count() }}</div>
                                    <div class="stats-label">Aktif Program</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Program Alanları -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Program Alanları:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($student->schedules->pluck('areas')->flatten()->unique() as $area)
                                    <span class="badge bg-{{ $area == 'TYT' ? 'primary' : ($area == 'AYT' ? 'success' : ($area == 'KPSS' ? 'warning' : ($area == 'DGS' ? 'info' : 'secondary'))) }}">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Son Program -->
                        @if($student->schedules->count() > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Son Program:</h6>
                            <div class="program-item">
                                <strong>{{ $student->schedules->first()->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $student->schedules->first()->created_at->format('d.m.Y') }}
                                </small>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $student->created_at->format('d.m.Y') }} tarihinde kayıt
                            </small>
                            <span class="badge bg-primary">
                                <i class="fas fa-arrow-right me-1"></i>
                                Programı Gör
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Boş Durum -->
        <div class="text-center py-5">
            @if($areaFilter)
                <i class="fas fa-filter fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">{{ $areaFilter }} program türü için öğrenci bulunamadı</h4>
                <p class="text-muted mb-4">Bu program türü için henüz öğrenci programı oluşturulmamış.</p>
                <a href="{{ route('admin.programs.students') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-list me-2"></i>
                    Tüm Programları Gör
                </a>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Yeni Program Oluştur
                </a>
            @else
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz programı olan öğrenci bulunmuyor</h4>
                <p class="text-muted mb-4">Öğrenciler için program oluşturmaya başlayın.</p>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    İlk Programı Oluştur
                </a>
            @endif
        </div>
    @endif
</div>

<style>
.student-card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s ease;
}

.student-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
    border-color: #4e73df;
}

.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
}

.stats-item {
    text-align: center;
}

.stats-number {
    font-size: 1.5rem;
    font-weight: bold;
    display: block;
}

.stats-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.program-item {
    background-color: #f8f9fc;
    padding: 0.75rem;
    border-radius: 0.35rem;
    border-left: 4px solid #4e73df;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    border: none;
    border-radius: 0.35rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

.card-footer {
    background-color: #f8f9fc;
    border-top: 1px solid #e3e6f0;
    border-radius: 0 0 0.35rem 0.35rem !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Student card click handlers
    document.querySelectorAll('.student-card[data-href]').forEach(function(card) {
        card.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });
});
</script>
@endsection
