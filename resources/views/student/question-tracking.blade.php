<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Takibi - {{ $student->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .topic-stat {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .progress {
            height: 8px;
            border-radius: 10px;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .form-control-lg {
            font-size: 1.25rem;
            padding: 0.75rem 1rem;
        }
        .modal-header.bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bi-1-circle-fill, .bi-2-circle-fill, .bi-3-circle-fill {
            font-size: 1.5rem;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('student.dashboard') }}">
                <i class="bi bi-mortarboard-fill"></i> Öğrenci Paneli
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            <i class="bi bi-house-fill"></i> Ana Sayfa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.daily-tracking') }}">
                            <i class="bi bi-calendar-check"></i> Günlük Takip
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('student.question-tracking') }}">
                            <i class="bi bi-journal-text"></i> Soru Takibi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.previous-lessons') }}">
                            <i class="bi bi-clock-history"></i> Geçmiş Dersler
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle"></i> {{ $student->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('student.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">
                                <i class="bi bi-box-arrow-right"></i> Çıkış
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Başlık -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-journal-text"></i> Soru Takibi</h2>
                <p class="text-muted mb-0">Çözdüğünüz soruları buradan takip edebilirsiniz</p>
            </div>
            <button class="btn btn-gradient btn-lg" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                <i class="bi bi-plus-circle"></i> Hızlı Kayıt Ekle
            </button>
        </div>

        <!-- Başarı mesajı -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- İstatistik Kartları -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Toplam Soru</div>
                    <div class="stat-value">{{ $stats['total_questions'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="stat-label">Doğru</div>
                    <div class="stat-value">{{ $stats['total_correct'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                    <div class="stat-label">Yanlış</div>
                    <div class="stat-value">{{ $stats['total_incorrect'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%);">
                    <div class="stat-label">Boş</div>
                    <div class="stat-value">{{ $stats['total_empty'] }}</div>
                </div>
            </div>
        </div>

        <!-- Ders Bazlı İstatistikler -->
        @if(count($courseStats) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Ders Bazlı İstatistikler</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($courseStats as $stat)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-book"></i> {{ $stat['name'] }}
                                        </h6>
                                        <div class="row text-center">
                                            <div class="col-6 mb-2">
                                                <div class="fs-5 fw-bold">{{ $stat['total'] }}</div>
                                                <small class="text-muted">Toplam</small>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="fs-5 fw-bold text-primary">{{ $stat['net'] }}</div>
                                                <small class="text-muted">Net</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fs-6 fw-bold text-success">{{ $stat['correct'] }}</div>
                                                <small class="text-muted">Doğru</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fs-6 fw-bold text-danger">{{ $stat['incorrect'] }}</div>
                                                <small class="text-muted">Yanlış</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fs-6 fw-bold text-secondary">{{ $stat['empty'] }}</div>
                                                <small class="text-muted">Boş</small>
                                            </div>
                                        </div>
                                        @php
                                            $successRate = $stat['total'] > 0 ? ($stat['correct'] / $stat['total']) * 100 : 0;
                                        @endphp
                                        <div class="progress mt-3" style="height: 8px;">
                                            <div class="progress-bar {{ $successRate >= 80 ? 'bg-success' : ($successRate >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                 style="width: {{ $successRate }}%"></div>
                                        </div>
                                        <small class="text-muted">Başarı: %{{ round($successRate, 1) }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Net ve Tarih Filtresi -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Net Sayınız</h5>
                        <div style="font-size: 3rem; font-weight: bold; color: #667eea;">
                            {{ $stats['net'] }}
                        </div>
                        <small class="text-muted">Doğru - (Yanlış ÷ 3)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tarih Filtresi</h5>
                        <form action="{{ route('student.question-tracking') }}" method="GET">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="days" id="days7" value="7" {{ $days == 7 ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="btn btn-outline-primary" for="days7">Son 7 Gün</label>

                                <input type="radio" class="btn-check" name="days" id="days30" value="30" {{ $days == 30 ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="btn btn-outline-primary" for="days30">Son 30 Gün</label>

                                <input type="radio" class="btn-check" name="days" id="days90" value="90" {{ $days == 90 ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="btn btn-outline-primary" for="days90">Son 90 Gün</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Soru Kayıtları Listesi -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Soru Kayıtları</h5>
            </div>
            <div class="card-body">
                @if($questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tarih</th>
                                <th>Ders</th>
                                <th class="text-center">Toplam</th>
                                <th class="text-center">Doğru</th>
                                <th class="text-center">Yanlış</th>
                                <th class="text-center">Boş</th>
                                <th class="text-center">Net</th>
                                <th class="text-center">Başarı %</th>
                                <th class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            @php
                                $correct = (int) $question->student_answer;
                                $incorrect = (int) $question->explanation;
                                $empty = (int) $question->notes;
                                $total = (int) $question->question_number;
                                $net = round($correct - ($incorrect / 3), 2);
                                $success = $total > 0 ? round(($correct / $total) * 100, 1) : 0;
                                $courseName = $question->topic && $question->topic->course ? $question->topic->course->name : '-';
                            @endphp
                            <tr>
                                <td>
                                    <i class="bi bi-calendar3"></i> 
                                    {{ \Carbon\Carbon::parse($question->solved_at)->format('d.m.Y') }}
                                </td>
                                <td><strong>{{ $courseName }}</strong></td>
                                <td class="text-center"><span class="badge bg-secondary fs-6">{{ $total }}</span></td>
                                <td class="text-center"><span class="badge bg-success fs-6">{{ $correct }}</span></td>
                                <td class="text-center"><span class="badge bg-danger fs-6">{{ $incorrect }}</span></td>
                                <td class="text-center"><span class="badge bg-secondary fs-6">{{ $empty }}</span></td>
                                <td class="text-center"><span class="badge bg-primary fs-6">{{ $net }}</span></td>
                                <td class="text-center">
                                    <span class="badge {{ $success >= 80 ? 'bg-success' : ($success >= 60 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                        %{{ $success }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('student.question-tracking.destroy', $question->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kaydı silmek istediğinizden emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3">Henüz soru kaydı bulunmuyor. Hemen bir kayıt ekleyin!</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Yeni Soru Kaydı Modal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('student.question-tracking.store') }}" method="POST" id="questionForm">
                    @csrf
                    <div class="modal-header bg-gradient text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Hızlı Soru Kaydı</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Basit ve Hızlı Kayıt!</strong><br>
                            Sadece 4 alan doldurun, boş sayısı ve net otomatik hesaplanacak.<br>
                            <strong>Net =</strong> Doğru - (Yanlış ÷ 3)
                        </div>

                        <div class="mb-4">
                            <label for="course_id" class="form-label fs-5">
                                <i class="bi bi-book text-primary"></i> Ders
                            </label>
                            <select class="form-select form-select-lg" 
                                    id="course_id" 
                                    name="course_id" 
                                    required>
                                <option value="">Ders Seçin</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hangi dersten soru çözdünüz?</small>
                        </div>

                        <div class="mb-4">
                            <label for="total_questions" class="form-label fs-5">
                                <i class="bi bi-1-circle-fill text-secondary"></i> Toplam Soru Sayısı
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="total_questions" 
                                   name="total_questions" 
                                   min="1" 
                                   placeholder="Örn: 40"
                                   required
                                   autofocus>
                        </div>

                        <div class="mb-4">
                            <label for="correct_count" class="form-label fs-5">
                                <i class="bi bi-2-circle-fill text-success"></i> Doğru Sayısı
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="correct_count" 
                                   name="correct_count" 
                                   min="0" 
                                   value="0"
                                   placeholder="Örn: 30"
                                   required>
                            <small class="text-muted">Toplam soru sayısından fazla olamaz</small>
                        </div>

                        <div class="mb-4">
                            <label for="incorrect_count" class="form-label fs-5">
                                <i class="bi bi-3-circle-fill text-danger"></i> Yanlış Sayısı
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="incorrect_count" 
                                   name="incorrect_count" 
                                   min="0" 
                                   value="0"
                                   placeholder="Örn: 5"
                                   required>
                            <small class="text-muted">Doğru + Yanlış, toplam soru sayısını geçemez</small>
                        </div>

                        <div class="alert alert-success" id="calculationPreview" style="display: none;">
                            <!-- JavaScript ile dinamik olarak doldurulacak -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> İptal
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-check-circle"></i> Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Anlık hesaplama ve önizleme
        function updateCalculation() {
            const total = parseInt(document.getElementById('total_questions').value) || 0;
            const correct = parseInt(document.getElementById('correct_count').value) || 0;
            const incorrect = parseInt(document.getElementById('incorrect_count').value) || 0;
            
            const preview = document.getElementById('calculationPreview');
            
            // Doğru + Yanlış kontrolü
            if (correct + incorrect > total && total > 0) {
                // Hata durumu
                preview.className = 'alert alert-danger';
                preview.style.display = 'block';
                preview.innerHTML = `
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                        <div class="mt-2 fw-bold">HATA!</div>
                        <div>Doğru (${correct}) + Yanlış (${incorrect}) = ${correct + incorrect}</div>
                        <div>Toplam soru sayısından (${total}) fazla olamaz!</div>
                    </div>
                `;
                // Kaydet butonunu devre dışı bırak
                document.querySelector('#questionForm button[type="submit"]').disabled = true;
                return;
            }
            
            // Normal hesaplama
            const empty = Math.max(0, total - correct - incorrect);
            const net = Math.max(0, correct - (incorrect / 3)).toFixed(2);
            const success = total > 0 ? ((correct / total) * 100).toFixed(1) : 0;
            
            // Önizleme alanını güncelle
            if (total > 0) {
                preview.className = 'alert alert-success';
                preview.style.display = 'block';
                preview.innerHTML = `
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fs-4 fw-bold">${empty}</div>
                            <small>Boş</small>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold text-primary">${net}</div>
                            <small>Net</small>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold">${success}%</div>
                            <small>Başarı</small>
                        </div>
                    </div>
                `;
                // Kaydet butonunu aktif et
                document.querySelector('#questionForm button[type="submit"]').disabled = false;
            } else {
                preview.style.display = 'none';
                document.querySelector('#questionForm button[type="submit"]').disabled = false;
            }
        }

        // Input değişikliklerini dinle
        document.getElementById('total_questions').addEventListener('input', updateCalculation);
        document.getElementById('correct_count').addEventListener('input', updateCalculation);
        document.getElementById('incorrect_count').addEventListener('input', updateCalculation);

        // Form validasyonu
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            const course = document.getElementById('course_id').value;
            const total = parseInt(document.getElementById('total_questions').value) || 0;
            const correct = parseInt(document.getElementById('correct_count').value) || 0;
            const incorrect = parseInt(document.getElementById('incorrect_count').value) || 0;
            
            if (!course) {
                e.preventDefault();
                alert('❌ Lütfen ders seçin!');
                document.getElementById('course_id').focus();
                return false;
            }
            
            if (total === 0) {
                e.preventDefault();
                alert('❌ Toplam soru sayısı 0 olamaz!');
                return false;
            }
            
            if (correct < 0 || incorrect < 0) {
                e.preventDefault();
                alert('❌ Doğru ve yanlış sayısı negatif olamaz!');
                return false;
            }
            
            if (correct > total) {
                e.preventDefault();
                alert(`❌ Doğru sayısı (${correct}), toplam soru sayısından (${total}) fazla olamaz!`);
                return false;
            }
            
            if (incorrect > total) {
                e.preventDefault();
                alert(`❌ Yanlış sayısı (${incorrect}), toplam soru sayısından (${total}) fazla olamaz!`);
                return false;
            }
            
            if (correct + incorrect > total) {
                e.preventDefault();
                alert(`❌ HATA!\n\nDoğru (${correct}) + Yanlış (${incorrect}) = ${correct + incorrect}\n\nBu toplam, toplam soru sayısından (${total}) fazla olamaz!`);
                return false;
            }
        });

        // Modal açıldığında formu sıfırla
        document.getElementById('addQuestionModal').addEventListener('shown.bs.modal', function() {
            document.getElementById('questionForm').reset();
            document.getElementById('calculationPreview').style.display = 'none';
            document.querySelector('#questionForm button[type="submit"]').disabled = false;
            document.getElementById('course_id').focus();
        });
        
        // Sayfa yüklendiğinde varsayılan durumu ayarla
        document.addEventListener('DOMContentLoaded', function() {
            updateCalculation();
        });
    </script>
</body>
</html>

