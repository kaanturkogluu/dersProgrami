@extends('admin.layout')

@section('title', 'Konu Düzenle')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Konu Düzenle
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Konu Bilgileri</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Konu Adı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $topic->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Ders <span class="text-danger">*</span></label>
                        <select class="form-select @error('course_id') is-invalid @enderror" 
                                id="course_id" name="course_id" required>
                            <option value="">Ders Seçin</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                        {{ old('course_id', $topic->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->category->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $topic->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_index" class="form-label">Sıra Numarası <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                               id="order_index" name="order_index" value="{{ old('order_index', $topic->order_index) }}" 
                               min="0" required>
                        @error('order_index')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Süre (Dakika) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                               id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $topic->duration_minutes) }}" 
                               min="0" required>
                        @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
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
@endsection
