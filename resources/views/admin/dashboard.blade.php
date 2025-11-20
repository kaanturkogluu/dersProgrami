@extends('admin.layout')

@section('title', 'Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Panel
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('student.login') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-graduate me-2"></i>
            Öğrenci Girişi
        </a>
    </div>
</div>

<!-- Navigation Menu Cards -->
<div class="row">
    <!-- İçerik Yönetimi -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    İçerik Yönetimi
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tags text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Kategoriler</h6>
                                <small class="text-muted">Ders kategorilerini yönetin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book text-success me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Dersler</h6>
                                <small class="text-muted">Dersleri ekleyin ve düzenleyin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.topics.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list text-warning me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Konular</h6>
                                <small class="text-muted">Ders konularını yönetin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.subtopics.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list-ul text-info me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Alt Konular</h6>
                                <small class="text-muted">Alt konuları yönetin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Öğrenci Yönetimi -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-success">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Öğrenci Yönetimi
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.students.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-graduate text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Öğrenciler</h6>
                                <small class="text-muted">Öğrenci bilgilerini yönetin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.programs.students') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-success me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Öğrenci Programları</h6>
                                <small class="text-muted">Öğrenci programlarını oluşturup düzenleyin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.templates.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-copy text-warning me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Program Şablonları</h6>
                                <small class="text-muted">Program şablonlarını yönetin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- TYT/AYT Takip -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    TYT/AYT Takip
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.topic-tracking.student-progress') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clipboard-check text-success me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Öğrenci Ders Takibi</h6>
                                <small class="text-muted">Hangi öğrenci hangi derste hangi konuda?</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.topic-tracking.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tasks text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Konu Takip</h6>
                                <small class="text-muted">Öğrenci konu takibini görüntüleyin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.question-analysis.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-question-circle text-info me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Soru Analizi</h6>
                                <small class="text-muted">Soru analizlerini inceleyin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Raporlar ve Sistem Yönetimi -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-info">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Raporlar & Sistem
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.daily-reports.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Günlük Raporlar</h6>
                                <small class="text-muted">Günlük öğrenci raporlarını görüntüleyin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    <a href="{{ route('admin.mail.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-success me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Mail Yönetimi</h6>
                                <small class="text-muted">Mail ayarları ve gönderimi</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.admins.index') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users-cog text-danger me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Admin Yönetimi</h6>
                                <small class="text-muted">Admin kullanıcılarını yönetin</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem;
    transition: all 0.2s ease;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.list-group-item i.fa-chevron-right {
    transition: transform 0.2s ease;
}

.list-group-item:hover i.fa-chevron-right {
    transform: translateX(3px);
}

.card-header h5 {
    font-weight: 600;
}
</style>
@endsection
