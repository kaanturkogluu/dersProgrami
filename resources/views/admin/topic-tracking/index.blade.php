@extends('admin.layout')

@section('title', 'Konu Takip Sistemi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line me-2"></i>
        Konu Takip Sistemi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.topic-tracking.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Konu Takibi
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $students->sum(function($s) { return $s->topicTrackings->count(); }) }}</h4>
                        <p class="card-text">Toplam Takip</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $students->sum(function($s) { return $s->topicTrackings->where('status', 'completed')->count(); }) }}</h4>
                        <p class="card-text">Tamamlanan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $students->sum(function($s) { return $s->topicTrackings->where('status', 'in_progress')->count(); }) }}</h4>
                        <p class="card-text">Devam Eden</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $students->sum(function($s) { return $s->topicTrackings->where('status', 'approved')->count(); }) }}</h4>
                        <p class="card-text">Onaylanan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-thumbs-up fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>
            Öğrenci Konu Takip Listesi
        </h5>
    </div>
    <div class="card-body">
        @if($students->count() > 0)
            <div class="accordion" id="studentsAccordion">
                @foreach($students as $student)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $student->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse{{ $student->id }}" aria-expanded="false" 
                                aria-controls="collapse{{ $student->id }}">
                            <div class="d-flex justify-content-between w-100 me-3">
                                <div>
                                    <strong>{{ $student->full_name }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ $student->topicTrackings->count() }} konu</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    @php
                                        $completed = $student->topicTrackings->where('status', 'completed')->count();
                                        $total = $student->topicTrackings->count();
                                        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                                    @endphp
                                    <span class="badge bg-success me-2">{{ $percentage }}% tamamlandı</span>
                                    <a href="{{ route('admin.topic-tracking.create', ['student_id' => $student->id]) }}" 
                                       class="btn btn-sm btn-primary" 
                                       onclick="event.stopPropagation();" 
                                       title="Bu öğrenci için konu takibi ekle">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse{{ $student->id }}" class="accordion-collapse collapse" 
                         aria-labelledby="heading{{ $student->id }}" data-bs-parent="#studentsAccordion">
                        <div class="accordion-body">
                            @if($student->topicTrackings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Konu</th>
                                                <th>Alt Konu</th>
                                                <th>Durum</th>
                                                <th>Zorluk</th>
                                                <th>Süre</th>
                                                <th>Başlangıç</th>
                                                <th>Bitiş</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->topicTrackings as $tracking)
                                            <tr>
                                                <td>
                                                    <strong>{{ $tracking->topic->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $tracking->topic->course->name }}</small>
                                                </td>
                                                <td>
                                                    @if($tracking->subtopic)
                                                        {{ $tracking->subtopic->name }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
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
                                                    <span class="badge bg-{{ $statusColors[$tracking->status] }}">
                                                        {{ $statusTexts[$tracking->status] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $tracking->difficulty_level ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </td>
                                                <td>
                                                    @if($tracking->time_spent_minutes > 0)
                                                        {{ $tracking->time_spent_minutes }} dk
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($tracking->started_at)
                                                        {{ $tracking->started_at->format('d.m.Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($tracking->completed_at)
                                                        {{ $tracking->completed_at->format('d.m.Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.topic-tracking.show', $tracking) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Görüntüle">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.topic-tracking.edit', $tracking) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($tracking->status === 'completed')
                                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                                    onclick="updateStatus({{ $tracking->id }}, 'approved')" title="Onayla">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                        @if($tracking->status === 'not_started')
                                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                                    onclick="updateStatus({{ $tracking->id }}, 'in_progress')" title="Başlat">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        @endif
                                                        @if($tracking->status === 'in_progress')
                                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                                    onclick="updateStatus({{ $tracking->id }}, 'completed')" title="Tamamla">
                                                                <i class="fas fa-flag-checkered"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Bu öğrenci için henüz konu takibi bulunmuyor.</p>
                                    <a href="{{ route('admin.topic-tracking.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Yeni Konu Takibi Ekle
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Henüz öğrenci bulunmuyor</h5>
                <p>Konu takibi yapmak için önce öğrenci eklemeniz gerekiyor.</p>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Yeni Öğrenci Ekle
                </a>
            </div>
        @endif
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
