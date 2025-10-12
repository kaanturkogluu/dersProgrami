@extends('admin.layout')

@section('title', 'Admin Düzenle')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-edit me-2"></i>
        Admin Düzenle
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $admin->name) }}" 
                                       placeholder="Admin adı ve soyadı" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $admin->email) }}" 
                                       placeholder="admin@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Yeni Şifre</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" value="{{ old('password') }}" 
                                       placeholder="Değiştirmek istemiyorsanız boş bırakın">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Şifre değiştirmek istemiyorsanız boş bırakın.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Şifre Tekrarı</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" 
                                       placeholder="Şifreyi tekrar girin">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Şifre değiştiriyorsanız tekrar girin.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Rol seçin</option>
                                    <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="super_admin" {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <strong>Admin:</strong> Sadece kendi öğrencilerini yönetebilir<br>
                                    <strong>Super Admin:</strong> Tüm adminleri ve öğrencileri yönetebilir
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                                <small class="form-text text-muted">Admin hesabının aktif olup olmayacağını belirler.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-secondary">
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
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Mevcut Bilgiler</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ substr($admin->name, 0, 1) }}
                    </div>
                    <h5>{{ $admin->name }}</h5>
                    <p class="text-muted">{{ $admin->email }}</p>
                </div>
                
                <hr>
                
                <div class="text-start">
                    <div class="mb-2">
                        <strong>Mevcut Rol:</strong>
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
                        <strong>Mevcut Durum:</strong>
                        @if($admin->is_active)
                            <span class="badge bg-success ms-2">Aktif</span>
                        @else
                            <span class="badge bg-secondary ms-2">Pasif</span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Öğrenci Sayısı:</strong>
                        <span class="ms-2">{{ $admin->students()->count() }}</span>
                    </div>
                    
                    <div class="mb-0">
                        <strong>Kayıt Tarihi:</strong>
                        <span class="ms-2">{{ $admin->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Bilgi</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Dikkat</h6>
                    <ul class="mb-0">
                        <li>Şifre değiştirmek istemiyorsanız boş bırakın</li>
                        <li>Rol değişikliği hemen etkili olur</li>
                        <li>Pasif yapılan adminler giriş yapamaz</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
