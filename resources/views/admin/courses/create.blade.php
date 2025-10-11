@extends('admin.layout')

@section('title', 'Yeni Ders')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-book-medical me-2"></i>
        Yeni Ders Oluştur
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ders Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Ders Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Örn: Matematik, Türkçe, Tarih" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Kategori seçin...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        data-color="{{ $category->color }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Ders hakkında açıklama...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration_hours" class="form-label">Süre (Saat) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" 
                                       id="duration_hours" name="duration_hours" value="{{ old('duration_hours', 0) }}" 
                                       min="0" step="0.5" required>
                                @error('duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Dersin toplam süresi (saat cinsinden).</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Fiyat (₺) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', 0) }}" 
                                       min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Dersin fiyatı (TL cinsinden).</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Ders Oluştur
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
                    <div class="course-preview mb-3">
                        <div class="avatar-lg mx-auto rounded d-flex align-items-center justify-content-center mb-3" 
                             id="preview-icon" style="background-color: #3B82F620; color: #3B82F6;">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <h4 id="preview-name" class="mb-2">Ders Adı</h4>
                        <p id="preview-category" class="text-muted mb-2">
                            <span class="badge" id="preview-badge" style="background-color: #3B82F6;">Kategori</span>
                        </p>
                        <p id="preview-description" class="text-muted mb-3">Açıklama burada görünecek...</p>
                        <div class="row text-center">
                            <div class="col-6">
                                <strong id="preview-duration">0 saat</strong>
                                <br><small class="text-muted">Süre</small>
                            </div>
                            <div class="col-6">
                                <strong id="preview-price" class="text-success">0,00 ₺</strong>
                                <br><small class="text-muted">Fiyat</small>
                            </div>
                        </div>
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
                        Kategori seçimi zorunludur.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Süre 0.5 saat aralıklarla girilebilir.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Fiyat kuruş hassasiyetinde girilebilir.
                    </li>
                    <li>
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Ders oluşturduktan sonra konular ekleyebilirsiniz.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .course-preview {
        padding: 20px;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        background-color: #f8fafc;
    }

    .avatar-lg {
        width: 80px;
        height: 80px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const categorySelect = document.getElementById('category_id');
        const descriptionInput = document.getElementById('description');
        const durationInput = document.getElementById('duration_hours');
        const priceInput = document.getElementById('price');
        
        const previewIcon = document.getElementById('preview-icon');
        const previewName = document.getElementById('preview-name');
        const previewCategory = document.getElementById('preview-category');
        const previewBadge = document.getElementById('preview-badge');
        const previewDescription = document.getElementById('preview-description');
        const previewDuration = document.getElementById('preview-duration');
        const previewPrice = document.getElementById('preview-price');

        // Name input change
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Ders Adı';
        });

        // Category select change
        categorySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const categoryName = selectedOption.text;
            const categoryColor = selectedOption.dataset.color || '#3B82F6';
            
            previewBadge.textContent = categoryName;
            previewBadge.style.backgroundColor = categoryColor;
            previewIcon.style.backgroundColor = categoryColor + '20';
            previewIcon.style.color = categoryColor;
        });

        // Description input change
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Açıklama burada görünecek...';
        });

        // Duration input change
        durationInput.addEventListener('input', function() {
            const duration = parseFloat(this.value) || 0;
            previewDuration.textContent = duration + ' saat';
        });

        // Price input change
        priceInput.addEventListener('input', function() {
            const price = parseFloat(this.value) || 0;
            previewPrice.textContent = price.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' ₺';
        });

        // Initialize preview
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
