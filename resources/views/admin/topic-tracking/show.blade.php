@extends('admin.layout')

@section('title', 'Konu Takip Detayı')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye me-2"></i>
        Konu Takip Detayı
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topic-tracking.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>
            Geri Dön
        </a>
        <a href="{{ route('admin.topic-tracking.edit', $topicTracking) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>
            Düzenle
        </a>
        <form action="{{ route('admin.topic-tracking.destroy', $topicTracking) }}" method="POST" 
              style="display: inline;" onsubmit="return confirm('Bu konu takip kaydını silmek istediğinizden emin misiniz?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>
                Sil
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Konu Takip Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Öğrenci:</label>
                            <p class="form-control-plaintext">{{ $topicTracking->student->full_name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Durum:</label>
                            <p class="form-control-plaintext">
                                @php
                                    $statusColors = [
                                        'not_started' => 'secondary',
                                        'in_progress' => 'warning',
                                        'completed' => 'success',
                                        'approved' => 'info'
                                    ];
                                    $statusTexts = [
                                        'not_started' => 'Başlanmadı',
                                        'in_progress' => 'Devam Ediyor',
                                        'completed' => 'Tamamlandı',
                                        'approved' => 'Onaylandı'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$topicTracking->status] }} fs-6">
                                    {{ $statusTexts[$topicTracking->status] }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konu:</label>
                            <p class="form-control-plaintext">
                                <strong>{{ $topicTracking->topic->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $topicTracking->topic->course->name }} - {{ $topicTracking->topic->course->category->name }}</small>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alt Konu:</label>
                            <p class="form-control-plaintext">
                                @if($topicTracking->subtopic)
                                    {{ $topicTracking->subtopic->name }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Zorluk Seviyesi:</label>
                            <p class="form-control-plaintext">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $topicTracking->difficulty_level ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">({{ $topicTracking->difficulty_level }}/5)</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Harcanan Süre:</label>
                            <p class="form-control-plaintext">
                                @if($topicTracking->time_spent_minutes > 0)
                                    {{ $topicTracking->time_spent_minutes }} dakika
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($topicTracking->notes)
                <div class="mb-3">
                    <label class="form-label fw-bold">Notlar:</label>
                    <div class="form-control-plaintext bg-light p-3 rounded">
                        {{ $topicTracking->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Tarih Bilgileri
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Oluşturulma Tarihi:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->created_at->format('d.m.Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Son Güncelleme:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->updated_at->format('d.m.Y H:i') }}</p>
                </div>
                
                @if($topicTracking->started_at)
                <div class="mb-3">
                    <label class="form-label fw-bold">Başlangıç Tarihi:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->started_at->format('d.m.Y') }}</p>
                </div>
                @endif
                
                @if($topicTracking->completed_at)
                <div class="mb-3">
                    <label class="form-label fw-bold">Tamamlanma Tarihi:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->completed_at->format('d.m.Y') }}</p>
                </div>
                @endif
                
                @if($topicTracking->approved_at)
                <div class="mb-3">
                    <label class="form-label fw-bold">Onay Tarihi:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->approved_at->format('d.m.Y') }}</p>
                </div>
                
                @if($topicTracking->approvedBy)
                <div class="mb-3">
                    <label class="form-label fw-bold">Onaylayan:</label>
                    <p class="form-control-plaintext">{{ $topicTracking->approvedBy->name }}</p>
                </div>
                @endif
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Hızlı İşlemler
                </h5>
            </div>
            <div class="card-body">
                @if($topicTracking->status === 'not_started')
                    <button type="button" class="btn btn-info w-100 mb-2" 
                            onclick="updateStatus({{ $topicTracking->id }}, 'in_progress')">
                        <i class="fas fa-play me-2"></i>
                        Başlat
                    </button>
                @endif
                
                @if($topicTracking->status === 'in_progress')
                    <button type="button" class="btn btn-success w-100 mb-2" 
                            onclick="updateStatus({{ $topicTracking->id }}, 'completed')">
                        <i class="fas fa-flag-checkered me-2"></i>
                        Tamamla
                    </button>
                @endif
                
                @if($topicTracking->status === 'completed')
                    <button type="button" class="btn btn-primary w-100 mb-2" 
                            onclick="updateStatus({{ $topicTracking->id }}, 'approved')">
                        <i class="fas fa-check me-2"></i>
                        Onayla
                    </button>
                @endif
                
                <a href="{{ route('admin.question-analysis.create') }}?student_id={{ $topicTracking->student_id }}&topic_id={{ $topicTracking->topic_id }}&subtopic_id={{ $topicTracking->subtopic_id }}" 
                   class="btn btn-outline-primary w-100">
                    <i class="fas fa-question-circle me-2"></i>
                    Soru Analizi Ekle
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(trackingId, status) {
    if (confirm('Durumu güncellemek istediğinizden emin misiniz?')) {
        fetch(`/admin/topic-tracking/${trackingId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Bir hata oluştu: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu.');
        });
    }
}
</script>
@endsection
