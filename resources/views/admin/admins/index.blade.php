@extends('admin.layout')

@section('title', 'Admin Yönetimi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users-cog me-2"></i>
        Admin Yönetimi
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Yeni Admin
            </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">Admin Listesi</h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Admin ara..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($admins->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Öğrenci Sayısı</th>
                            <th>Durum</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $admin->name }}</strong>
                                        @if($admin->id === Auth::user()->id)
                                            <br><small class="text-success">(Siz)</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $admin->email }}" class="text-decoration-none">
                                    {{ $admin->email }}
                                </a>
                            </td>
                            <td>
                                @if($admin->role === 'super_admin')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-crown me-1"></i>
                                        Super Admin
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-user-shield me-1"></i>
                                        Admin
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $admin->students()->count() }} öğrenci
                                </span>
                            </td>
                            <td>
                                @if($admin->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $admin->created_at->format('d.m.Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-sm btn-outline-info" title="Detaylar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(Auth::user()->isSuperAdmin() || Auth::user()->id === $admin->id)
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-outline-warning" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->isSuperAdmin() && Auth::user()->id !== $admin->id)
                                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu admini silmek istediğinizden emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $admins->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users-cog fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Henüz admin bulunmuyor</h5>
                @if(Auth::user()->isSuperAdmin())
                    <p class="text-muted">İlk admini eklemek için yukarıdaki butonu kullanın.</p>
                @endif
            </div>
        @endif
    </div>
</div>

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
@endsection
