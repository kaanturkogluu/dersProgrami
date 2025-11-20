@extends('admin.layout')

@section('title', 'Öğrenci Ders Takibi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-check"></i> Öğrenci Ders Takibi
            </h1>
            <p class="text-muted mb-0">Hangi öğrenci hangi derste hangi konuda?</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Sol Taraf: Öğrenci Listesi -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Öğrenciler
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($students->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($students as $student)
                                <a href="#" 
                                   class="list-group-item list-group-item-action student-item" 
                                   data-student-id="{{ $student->id }}"
                                   onclick="loadStudentProgress({{ $student->id }}); return false;">
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $student->full_name }}</h6>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Henüz öğrenci bulunmuyor.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sağ Taraf: Ders ve Konu Takibi -->
        <div class="col-md-8">
            <div id="progressContainer">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-hand-pointer fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Lütfen bir öğrenci seçin</h5>
                        <p class="text-muted mb-0">Sol taraftan bir öğrenci seçtiğinizde, o öğrencinin ders ve konu takibi burada görünecektir.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .student-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .student-item:hover {
        background-color: #f8f9fa;
        border-left-color: #667eea;
    }

    .student-item.active {
        background-color: #e7f1ff;
        border-left-color: #667eea;
        font-weight: 500;
    }

    .course-section {
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
    }

    .course-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #4e73df;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4e73df;
    }

    .topic-item {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .topic-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .topic-item.completed {
        background: #d4edda;
        border-color: #c3e6cb;
    }

    .topic-item.in-progress {
        background: #fff3cd;
        border-color: #ffeaa7;
    }

    .subtopic-list {
        margin-left: 30px;
        margin-top: 10px;
    }

    .subtopic-item {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 8px 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .subtopic-item:hover {
        background: #f8f9fa;
    }

    .subtopic-item.completed {
        background: #d4edda;
        border-color: #c3e6cb;
    }

    .custom-checkbox {
        width: 22px;
        height: 22px;
        cursor: pointer;
    }

    .topic-checkbox {
        transform: scale(1.3);
        margin-right: 15px;
        cursor: pointer;
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 5px 10px;
    }

    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: text-bottom;
        border: 0.15em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>

<script>
let currentStudentId = null;

function loadStudentProgress(studentId) {
    currentStudentId = studentId;
    
    // Aktif öğrenciyi vurgula
    document.querySelectorAll('.student-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-student-id="${studentId}"]`).classList.add('active');
    
    // Loading göster
    document.getElementById('progressContainer').innerHTML = `
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="loading-spinner mb-3"></div>
                <p class="text-muted">Yükleniyor...</p>
            </div>
        </div>
    `;
    
    // AJAX ile öğrenci ders/konu bilgilerini getir
    fetch(`/admin/topic-tracking/student-progress/${studentId}`)
        .then(response => response.json())
        .then(data => {
            displayStudentProgress(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('progressContainer').innerHTML = `
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Bir hata oluştu</h5>
                        <p class="text-muted">Lütfen tekrar deneyin.</p>
                    </div>
                </div>
            `;
        });
}

function displayStudentProgress(data) {
    let html = `
        <div class="card shadow mb-3">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate"></i> ${data.student.name}
                </h5>
            </div>
        </div>
    `;
    
    if (data.courses.length === 0) {
        html += `
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Bu öğrencinin aktif programı bulunmuyor</h5>
                    <p class="text-muted mb-0">Öğrenci için bir program oluşturun.</p>
                </div>
            </div>
        `;
    } else {
        data.courses.forEach(course => {
            html += `
                <div class="course-section">
                    <div class="course-title">
                        <i class="fas fa-book"></i> ${course.name}
                    </div>
            `;
            
            if (course.topics.length === 0) {
                html += `<p class="text-muted">Bu ders için konu bulunmuyor.</p>`;
            } else {
                course.topics.forEach(topic => {
                    const isCompleted = topic.status === 'completed' || topic.status === 'approved';
                    const isInProgress = topic.status === 'in_progress';
                    const statusClass = isCompleted ? 'completed' : (isInProgress ? 'in-progress' : '');
                    
                    html += `
                        <div class="topic-item ${statusClass}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <input type="checkbox" 
                                           class="form-check-input topic-checkbox custom-checkbox" 
                                           ${isCompleted ? 'checked' : ''}
                                           onchange="toggleTopicStatus(${data.student.id}, ${topic.id}, null, this.checked)">
                                    <div>
                                        <strong>${topic.name}</strong>
                                        ${topic.completed_at ? `<br><small class="text-muted">Tamamlanma: ${formatDate(topic.completed_at)}</small>` : ''}
                                    </div>
                                </div>
                                <div>
                                    ${getStatusBadge(topic.status)}
                                </div>
                            </div>
                    `;
                    
                    // Alt konuları ekle
                    if (topic.subtopics && topic.subtopics.length > 0) {
                        html += `<div class="subtopic-list">`;
                        topic.subtopics.forEach(subtopic => {
                            const subCompleted = subtopic.status === 'completed' || subtopic.status === 'approved';
                            const subClass = subCompleted ? 'completed' : '';
                            
                            html += `
                                <div class="subtopic-item ${subClass}">
                                    <input type="checkbox" 
                                           class="form-check-input custom-checkbox me-2" 
                                           ${subCompleted ? 'checked' : ''}
                                           onchange="toggleTopicStatus(${data.student.id}, ${topic.id}, ${subtopic.id}, this.checked)">
                                    <span>${subtopic.name}</span>
                                    ${subtopic.completed_at ? `<small class="text-muted ms-2">(${formatDate(subtopic.completed_at)})</small>` : ''}
                                </div>
                            `;
                        });
                        html += `</div>`;
                    }
                    
                    html += `</div>`;
                });
            }
            
            html += `</div>`;
        });
    }
    
    document.getElementById('progressContainer').innerHTML = html;
}

function getStatusBadge(status) {
    const badges = {
        'not_started': '<span class="badge bg-secondary badge-status">Başlanmadı</span>',
        'in_progress': '<span class="badge bg-warning badge-status">Devam Ediyor</span>',
        'completed': '<span class="badge bg-success badge-status">Tamamlandı</span>',
        'approved': '<span class="badge bg-primary badge-status">Onaylandı</span>'
    };
    return badges[status] || badges['not_started'];
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('tr-TR');
}

function toggleTopicStatus(studentId, topicId, subtopicId, isCompleted) {
    const data = {
        student_id: studentId,
        topic_id: topicId,
        subtopic_id: subtopicId,
        completed: isCompleted
    };
    
    fetch('/admin/topic-tracking/toggle-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Başarılı, sayfayı yenile
            loadStudentProgress(currentStudentId);
            
            // Toast mesajı göster
            showToast(result.message, 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu', 'error');
        // Checkbox'ı geri al
        loadStudentProgress(currentStudentId);
    });
}

function showToast(message, type) {
    const bgColor = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = document.createElement('div');
    toast.className = `alert alert-dismissible fade show position-fixed ${bgColor} text-white`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> ${message}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection

