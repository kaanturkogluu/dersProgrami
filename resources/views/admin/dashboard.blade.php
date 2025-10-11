@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Dashboard
    </h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-1">Kategoriler</h6>
                    <h2 class="mb-0">{{ $stats['categories'] }}</h2>
                </div>
                <div class="align-self-center">
                    <i class="fas fa-tags fa-2x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-1">Öğrenciler</h6>
                    <h2 class="mb-0">{{ $stats['students'] }}</h2>
                </div>
                <div class="align-self-center">
                    <i class="fas fa-users fa-2x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-1">Dersler</h6>
                    <h2 class="mb-0">{{ $stats['courses'] }}</h2>
                </div>
                <div class="align-self-center">
                    <i class="fas fa-book fa-2x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card info">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-1">Konular</h6>
                    <h2 class="mb-0">{{ $stats['topics'] }}</h2>
                </div>
                <div class="align-self-center">
                    <i class="fas fa-list fa-2x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Students -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Son Kayıt Olan Öğrenciler
                </h5>
            </div>
            <div class="card-body">
                @if($recentStudents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ad Soyad</th>
                                    <th>Öğrenci No</th>
                                    <th>Email</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentStudents as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            {{ $student->full_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $student->student_number }}</span>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        <small class="text-muted">{{ $student->created_at->format('d.m.Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-primary">
                            Tüm Öğrencileri Görüntüle
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz kayıtlı öğrenci bulunmuyor.</p>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                            İlk Öğrenciyi Kaydet
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>
                    Son Eklenen Dersler
                </h5>
            </div>
            <div class="card-body">
                @if($recentCourses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ders Adı</th>
                                    <th>Kategori</th>
                                    <th>Süre</th>
                                    <th>Fiyat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCourses as $course)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded d-flex align-items-center justify-content-center me-2" 
                                                 style="background-color: {{ $course->category->color }}20; color: {{ $course->category->color }};">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            {{ $course->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $course->category->color }};">
                                            {{ $course->category->name }}
                                        </span>
                                    </td>
                                    <td>{{ $course->duration_hours }} saat</td>
                                    <td>{{ number_format($course->price, 2) }} ₺</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-primary">
                            Tüm Dersleri Görüntüle
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Henüz ders eklenmemiş.</p>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                            İlk Dersi Ekle
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Hızlı İşlemler
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus me-2"></i>
                            Yeni Kategori
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.students.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Yeni Öğrenci
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-book-medical me-2"></i>
                            Yeni Ders
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.topics.create') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-list-plus me-2"></i>
                            Yeni Konu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
