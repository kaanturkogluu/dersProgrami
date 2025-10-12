@extends('admin.layout')

@section('title', 'Mail Yönetimi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-envelope me-2"></i>
        Mail Yönetimi
    </h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Öğrencilere Mail Gönder</h5>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Öğrenci</th>
                                    <th>Email</th>
                                    <th>Öğrenci No</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $student->full_name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                            {{ $student->email }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $student->student_number }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('admin.mail.send-welcome') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('{{ $student->full_name }} adresine hoş geldiniz maili gönderilsin mi?')"
                                                        title="Hoş Geldiniz Maili">
                                                    <i class="fas fa-hand-wave"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.mail.send-daily-reminder') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                <button type="submit" class="btn btn-sm btn-info" 
                                                        onclick="return confirm('{{ $student->full_name }} adresine günlük hatırlatma maili gönderilsin mi?')"
                                                        title="Günlük Hatırlatma">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Henüz öğrenci bulunmuyor</h5>
                        <p class="text-muted">Mail gönderebilmek için önce öğrenci eklemeniz gerekiyor.</p>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Yeni Öğrenci Ekle
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Toplu Mail Gönderimi</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Toplu Mail Gönderimi</h6>
                    <p class="mb-0">Tüm öğrencilerinize aynı anda günlük hatırlatma maili gönderebilirsiniz.</p>
                </div>

                @if($students->count() > 0)
                    <form action="{{ route('admin.mail.send-daily-reminder-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100" 
                                onclick="return confirm('Tüm öğrencilerinize günlük hatırlatma maili gönderilsin mi? ({{ $students->count() }} öğrenci)')">
                            <i class="fas fa-bell me-2"></i>
                            Tümüne Günlük Hatırlatma Gönder
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-warning w-100" disabled>
                        <i class="fas fa-bell me-2"></i>
                        Tümüne Günlük Hatırlatma Gönder
                    </button>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Mail Konfigürasyonu</h5>
            </div>
            <div class="card-body">
                @if($isConfigured)
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-2"></i>Mail Konfigürasyonu Tamamlandı</h6>
                        <p class="mb-0">Tüm gerekli ayarlar yapılmış ve mail gönderimi için hazır.</p>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Mail Konfigürasyonu Eksik</h6>
                        <p class="mb-2">Aşağıdaki alanlar eksik veya hatalı:</p>
                        <ul class="mb-0">
                            @foreach($configErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6>SMTP Ayarları</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Host:</strong></td>
                                <td>{{ $mailConfig['host'] ?: 'Ayarlanmamış' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Port:</strong></td>
                                <td>{{ $mailConfig['port'] ?: 'Ayarlanmamış' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Şifreleme:</strong></td>
                                <td>{{ $mailConfig['encryption'] ?: 'Ayarlanmamış' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kullanıcı:</strong></td>
                                <td>{{ $mailConfig['username'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Şifre:</strong></td>
                                <td>{{ $mailConfig['password'] }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Gönderen Bilgileri</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $mailConfig['from_email'] ?: 'Ayarlanmamış' }}</td>
                            </tr>
                            <tr>
                                <td><strong>İsim:</strong></td>
                                <td>{{ $mailConfig['from_name'] ?: 'Ayarlanmamış' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Timeout:</strong></td>
                                <td>{{ $mailConfig['timeout'] }} saniye</td>
                            </tr>
                            <tr>
                                <td><strong>Debug:</strong></td>
                                <td>{{ $mailConfig['debug'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Karakter Seti:</strong></td>
                                <td>{{ $mailConfig['charset'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Konfigürasyon</h6>
                    <p class="mb-2">Mail ayarlarını değiştirmek için <code>.env</code> dosyasını düzenleyin:</p>
                    <pre class="mb-0"><code>MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TIMEOUT=30
MAIL_DEBUG=false
MAIL_CHARSET=UTF-8</code></pre>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Mail Testi</h5>
            </div>
            <div class="card-body">
                @if($isConfigured)
                    <form action="{{ route('admin.mail.send-test') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="test_email" class="form-label">Test Email Adresi</label>
                            <input type="email" class="form-control" id="test_email" name="test_email" 
                                   placeholder="test@example.com" required>
                        </div>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-paper-plane me-2"></i>
                            Test Maili Gönder
                        </button>
                    </form>

                    <form action="{{ route('admin.mail.test-connection') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-plug me-2"></i>
                            SMTP Bağlantısını Test Et
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Test Yapılamıyor</h6>
                        <p class="mb-0">Mail konfigürasyonu tamamlanmadan test yapılamaz. Lütfen önce .env dosyasındaki mail ayarlarını kontrol edin.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Mail Türleri</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-hand-wave text-success me-2"></i>Hoş Geldiniz Maili</h6>
                    <p class="small text-muted mb-0">Yeni öğrenci oluşturulduğunda otomatik gönderilir. Öğrenciye sistem bilgilerini ve giriş bilgilerini içerir.</p>
                </div>
                
                <div class="mb-3">
                    <h6><i class="fas fa-bell text-info me-2"></i>Günlük Hatırlatma</h6>
                    <p class="small text-muted mb-0">Öğrenciye o günkü derslerini hatırlatır ve sisteme giriş yapmasını teşvik eder.</p>
                </div>

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>PHPMailer Kullanımı</h6>
                    <ul class="mb-0 small">
                        <li>Sistem artık PHPMailer kullanıyor</li>
                        <li>Daha güvenilir ve esnek mail gönderimi</li>
                        <li>Gelişmiş hata yönetimi</li>
                        <li>Toplu mail gönderimi optimizasyonu</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Önemli</h6>
                    <ul class="mb-0 small">
                        <li>Mail gönderimi için SMTP ayarlarının yapılmış olması gerekir</li>
                        <li>Mail gönderimi sırasında hata oluşursa log dosyalarını kontrol edin</li>
                        <li>Toplu mail gönderimi zaman alabilir</li>
                        <li>Test maili göndererek konfigürasyonu doğrulayın</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('errors'))
<div class="row mt-3">
    <div class="col-12">
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Mail Gönderim Hataları</h6>
            <ul class="mb-0">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
@endsection
