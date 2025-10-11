@extends('admin.layout')

@section('title', 'Yeni Öğrenci')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>
        Yeni Öğrenci Kaydet
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Öğrenci Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Ad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                       placeholder="Öğrencinin adı" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Soyad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                       placeholder="Öğrencinin soyadı" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="ornek@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="0555 123 45 67">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_number" class="form-label">Öğrenci Numarası <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_number') is-invalid @enderror" 
                                       id="student_number" name="student_number" value="{{ old('student_number') }}" 
                                       placeholder="Örn: 2024001" required>
                                @error('student_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Benzersiz bir öğrenci numarası girin.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Doğum Tarihi</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adres</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Öğrencinin adresi...">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Öğrenci Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Önizleme</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="student-preview mb-3">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             id="preview-avatar">
                            <span id="preview-initials">AD</span>
                        </div>
                        <h4 id="preview-name" class="mb-2">Ad Soyad</h4>
                        <p id="preview-email" class="text-muted mb-2">email@example.com</p>
                        <p id="preview-phone" class="text-muted mb-2">-</p>
                        <span class="badge bg-light text-dark" id="preview-student-number">Öğrenci No</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">İpuçları</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Öğrenci numarası benzersiz olmalıdır.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Email adresi benzersiz olmalıdır.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Doğum tarihi yaş hesaplaması için kullanılır.
                    </li>
                    <li>
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Tüm alanlar daha sonra düzenlenebilir.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .student-preview {
        padding: 20px;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        background-color: #f8fafc;
    }

    .avatar-lg {
        width: 80px;
        height: 80px;
        font-size: 24px;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const studentNumberInput = document.getElementById('student_number');
        
        const previewInitials = document.getElementById('preview-initials');
        const previewName = document.getElementById('preview-name');
        const previewEmail = document.getElementById('preview-email');
        const previewPhone = document.getElementById('preview-phone');
        const previewStudentNumber = document.getElementById('preview-student-number');

        // First name change
        firstNameInput.addEventListener('input', function() {
            updatePreview();
        });

        // Last name change
        lastNameInput.addEventListener('input', function() {
            updatePreview();
        });

        // Email change
        emailInput.addEventListener('input', function() {
            previewEmail.textContent = this.value || 'email@example.com';
        });

        // Phone change
        phoneInput.addEventListener('input', function() {
            previewPhone.textContent = this.value || '-';
        });

        // Student number change
        studentNumberInput.addEventListener('input', function() {
            previewStudentNumber.textContent = this.value || 'Öğrenci No';
        });

        function updatePreview() {
            const firstName = firstNameInput.value || 'Ad';
            const lastName = lastNameInput.value || 'Soyad';
            
            previewInitials.textContent = firstName.charAt(0).toUpperCase() + lastName.charAt(0).toUpperCase();
            previewName.textContent = firstName + ' ' + lastName;
        }

        // Initialize preview
        updatePreview();
    });
</script>
@endpush
