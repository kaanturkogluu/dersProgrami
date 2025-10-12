@extends('admin.layout')

@section('title', 'Admin Detayları')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-shield me-2"></i>
        Admin Detayları
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        @if(Auth::user()->isSuperAdmin() || Auth::user()->id === $admin->id)
            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning ms-2">
                <i class="fas fa-edit me-2"></i>
                Düzenle
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin Bilgileri</h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($admin->name, 0, 1) }}
                </div>
                <h4>{{ $admin->name }}</h4>
                <p class="text-muted">{{ $admin->email }}</p>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-primary mb-1">{{ $admin->students()->count() }}</h5>
                            <small class="text-muted">Toplam Öğrenci</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success mb-1">{{ $admin->activeStudents()->count() }}</h5>
                        <small class="text-muted">Aktif Öğrenci</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-start">
                    <div class="mb-2">
                        <strong>Rol:</strong>
                        @if($admin->role === 'super_admin')
                            <span class="badge bg-danger ms-2">
                                <i class="fas fa-crown me-1"></i>
                                Super Admin
                            </span>
                        @else
                            <span class="badge bg-primary ms-2">
                                <i class="fas fa-user-shield me-1"></i>
                                Admin
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Durum:</strong>
                        @if($admin->is_active)
                            <span class="badge bg-success ms-2">Aktif</span>
                        @else
                            <span class="badge bg-secondary ms-2">Pasif</span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Kayıt Tarihi:</strong>
                        <span class="ms-2">{{ $admin->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    
                    <div class="mb-0">
                        <strong>Son Güncelleme:</strong>
                        <span class="ms-2">{{ $admin->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin'in Öğrencileri</h5>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Öğrenci</th>
                                    <th>Öğrenci No</th>
                                    <th>Email</th>
                                    <th>Telefon</th>
                                    <th>Durum</th>
                                    <th>Kayıt Tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $student->full_name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $student->student_number }}</span>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                            {{ $student->email }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($student->phone)
                                            <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                                {{ $student->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $student->created_at->format('d.m.Y') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $students->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Bu adminin henüz öğrencisi yok</h5>
                        <p class="text-muted">Öğrenci eklemek için öğrenci yönetimi sayfasını kullanın.</p>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Yeni Öğrenci Ekle
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
