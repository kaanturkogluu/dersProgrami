@extends('admin.layout')

@section('title', 'Öğrenciler')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>
        Öğrenciler
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Yeni Öğrenci
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">Öğrenci Listesi</h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Öğrenci ara..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Öğrenci</th>
                            <th>Öğrenci No</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Doğum Tarihi</th>
                            <th>Durum</th>
                            <th>Kayıt Tarihi</th>
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
                                        @if($student->address)
                                            <br><small class="text-muted">{{ Str::limit($student->address, 30) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $student->student_number }}</span>
                            </td>
                            <td>
                                <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                    {{ $student->email }}
                                </a>
                            </td>
                            <td>
                                @if($student->phone)
                                    <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                        {{ $student->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($student->birth_date)
                                    {{ $student->birth_date->format('d.m.Y') }}
                                    <br><small class="text-muted">({{ $student->birth_date->age }} yaşında)</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($student->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $student->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.students.show', $student) }}" 
                                       class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu öğrenciyi silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz öğrenci kaydı bulunmuyor</h4>
                <p class="text-muted mb-4">İlk öğrencinizi kaydetmek için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>
                    İlk Öğrenciyi Kaydet
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
