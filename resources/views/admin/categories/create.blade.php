@extends('admin.layout')

@section('title', 'Yeni Kategori')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Yeni Kategori Oluştur
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Kategori Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Örn: KPSS, TYT, AYT" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Kategori hakkında açıklama...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Renk <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#3B82F6') }}" required>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="color-text" 
                                       value="{{ old('color', '#3B82F6') }}" readonly>
                            </div>
                        </div>
                        <small class="form-text text-muted">Kategori için bir renk seçin. Bu renk derslerde ve raporlarda kullanılacak.</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Kategori Oluştur
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
                    <div class="category-preview mb-3">
                        <div class="avatar-lg mx-auto rounded d-flex align-items-center justify-content-center mb-3" 
                             id="preview-icon" style="background-color: #3B82F620; color: #3B82F6;">
                            <i class="fas fa-tag fa-2x"></i>
                        </div>
                        <h4 id="preview-name" class="mb-2">Kategori Adı</h4>
                        <p id="preview-description" class="text-muted mb-3">Açıklama burada görünecek...</p>
                        <span class="badge" id="preview-badge" style="background-color: #3B82F6;">
                            Kategori
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Renk Önerileri</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-3">
                        <div class="color-option" data-color="#3B82F6" style="background-color: #3B82F6;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#10B981" style="background-color: #10B981;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#F59E0B" style="background-color: #F59E0B;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#EF4444" style="background-color: #EF4444;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#8B5CF6" style="background-color: #8B5CF6;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#06B6D4" style="background-color: #06B6D4;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#84CC16" style="background-color: #84CC16;"></div>
                    </div>
                    <div class="col-3">
                        <div class="color-option" data-color="#F97316" style="background-color: #F97316;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .color-option {
        width: 100%;
        height: 40px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .color-option:hover {
        transform: scale(1.05);
        border-color: #000;
    }

    .color-option.selected {
        border-color: #000;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
    }

    .category-preview {
        padding: 20px;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        background-color: #f8fafc;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color');
        const colorText = document.getElementById('color-text');
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        
        const previewIcon = document.getElementById('preview-icon');
        const previewName = document.getElementById('preview-name');
        const previewDescription = document.getElementById('preview-description');
        const previewBadge = document.getElementById('preview-badge');

        // Color input change
        colorInput.addEventListener('input', function() {
            const color = this.value;
            colorText.value = color;
            updatePreview(color);
        });

        // Name input change
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Kategori Adı';
        });

        // Description input change
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Açıklama burada görünecek...';
        });

        // Color options click
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                const color = this.dataset.color;
                colorInput.value = color;
                colorText.value = color;
                updatePreview(color);
                
                // Update selected state
                document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        function updatePreview(color) {
            previewIcon.style.backgroundColor = color + '20';
            previewIcon.style.color = color;
            previewBadge.style.backgroundColor = color;
        }

        // Initialize preview
        updatePreview(colorInput.value);
    });
</script>
@endpush
