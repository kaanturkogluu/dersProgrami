<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel') - Ders Programı Yönetimi</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3B82F6;
            --secondary-color: #64748B;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --info-color: #06B6D4;
            --light-color: #F8FAFC;
            --dark-color: #1E293B;
        }

        html, body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-color);
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Prevent background scroll when mobile menu is open */
        body.no-scroll {
            overflow: hidden;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1E40AF 100%);
            height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Desktop'ta sidebar genişlikleri */
        @media (min-width: 768px) and (max-width: 991px) {
            .sidebar {
                width: 25%; /* col-md-3 */
            }
        }

        @media (min-width: 992px) {
            .sidebar {
                width: 16.666667%; /* col-lg-2 */
            }
        }


        /* Sidebar scroll bar gizle ama scroll özelliği kalsın */
        .sidebar .overflow-auto {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE ve Edge */
        }

        .sidebar .overflow-auto::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .nav-section-header {
            padding: 8px 20px;
            margin: 10px 0 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-section-header small {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .sidebar .nav-item.mt-3 {
            margin-top: 1.5rem !important;
        }

        .sidebar .nav-link {
            position: relative;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: #fff;
            border-radius: 0 3px 3px 0;
        }

        /* Sidebar Collapse Styles */
        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-section-header,
        .sidebar.collapsed .sidebar-brand h4,
        .sidebar.collapsed .sidebar-brand small {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 12px 10px;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .sidebar.collapsed .nav-section-header {
            padding: 8px 10px;
            text-align: center;
        }

        .sidebar.collapsed .nav-section-header i {
            display: block;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Overlay styles */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        /* Mobile sidebar - slide from left */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                max-height: 100vh;
                z-index: 1050;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 2px 0 20px rgba(0,0,0,0.3);
                overflow-y: auto;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            /* Mobile toggle button */
            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1051;
                background: var(--primary-color);
                color: white;
                border: none;
                border-radius: 8px;
                padding: 12px;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
                transition: all 0.3s ease;
            }
            
            .mobile-menu-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            }
            
            /* Overlay for mobile */
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                transition: opacity 0.3s ease;
            }
            
            .overlay.show {
                display: block;
                opacity: 1;
            }
            
            /* Main content adjustment */
            .main-content {
                margin-left: 0;
                padding-top: 60px; /* Space for mobile menu button */
            }
            
            /* Mobile sidebar content adjustments */
            .sidebar .d-flex {
                height: 100vh;
                max-height: 100vh;
            }
            
            .sidebar .flex-grow-1 {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .sidebar .nav {
                padding-bottom: 1rem;
            }
        }

        .main-content {
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1E40AF 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-color);
            border: none;
            font-weight: 600;
            color: var(--dark-color);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stats-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stats-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 12px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1040;
        }

        /* Toolbars should wrap neatly on small screens */
        .btn-toolbar {
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 16px 20px;
            position: relative;
            z-index: 1050;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        /* Desktop sidebar - always visible */
        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
            
            .overlay {
                display: none !important;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                overflow-y: auto;
            }

            /* Main content margin - sidebar genişliğine göre */
            @media (min-width: 768px) and (max-width: 991px) {
                .main-content {
                    margin-left: 25%; /* col-md-3 = 3/12 = 25% */
                    padding-left: 1.5rem !important;
                }
            }

            @media (min-width: 992px) {
                .main-content {
                    margin-left: 16.666667%; /* col-lg-2 = 2/12 = 16.666667% */
                    padding-left: 1.5rem !important;
                }
            }
        }

        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background: #fff;
            padding: 0.5rem;
        }

        .pagination .page-link {
            color: #495057;
            border: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
            margin: 0 2px;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            font-weight: 500;
            background: #fff;
            min-width: 44px;
            text-align: center;
        }

        .pagination .page-link:hover {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            cursor: not-allowed;
        }

        .pagination .page-item:first-child .page-link {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }

        .pagination .page-item:last-child .page-link {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        /* Pagination Container */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
        }

        .pagination-info {
            margin: 0 1rem;
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Responsive Pagination */
        @media (max-width: 768px) {
            .pagination .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                min-width: 36px;
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                padding: 0.75rem;
            }
            
            .pagination {
                padding: 0.25rem;
            }

            /* Reduce card paddings on small screens */
            .card-header { padding: 14px; }
            .card-body { padding: 14px; }

            /* Make tables easier to view on mobile */
            .table { font-size: 0.95rem; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="container-fluid px-0">
        <div class="row g-0">
            
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebar">
                <div class="d-flex flex-column h-100">
                    <!-- Header -->
                    <div class="text-center mb-4 pt-3">
                        <h4 class="text-white">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Ders Programı
                        </h4>
                        <small class="text-white-50">Admin Panel</small>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <div class="flex-grow-1 overflow-auto">
                        <ul class="nav flex-column">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <!-- İçerik Yönetimi -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-header">
                                <small class="text-white-50 fw-bold text-uppercase">
                                    <i class="fas fa-cogs me-1"></i>
                                    İçerik Yönetimi
                                </small>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-tags"></i>
                                Kategoriler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                                <i class="fas fa-book"></i>
                                Dersler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}" href="{{ route('admin.topics.index') }}">
                                <i class="fas fa-list"></i>
                                Konular
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.subtopics.*') ? 'active' : '' }}" href="{{ route('admin.subtopics.index') }}">
                                <i class="fas fa-list-ul"></i>
                                Alt Konular
                            </a>
                        </li>
                        
                        <!-- Öğrenci Yönetimi -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-header">
                                <small class="text-white-50 fw-bold text-uppercase">
                                    <i class="fas fa-users me-1"></i>
                                    Öğrenci Yönetimi
                                </small>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                                <i class="fas fa-user-graduate"></i>
                                Öğrenciler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.programs.*') || request()->routeIs('admin.schedules.*') ? 'active' : '' }}" href="{{ route('admin.programs.students') }}">
                                <i class="fas fa-calendar-alt"></i>
                                Öğrenci Programları
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}" href="{{ route('admin.templates.index') }}">
                                <i class="fas fa-copy"></i>
                                Program Şablonları
                            </a>
                        </li>
                        
                        <!-- TYT/AYT Takip Sistemi -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-header">
                                <small class="text-white-50 fw-bold text-uppercase">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    TYT/AYT Takip
                                </small>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.topic-tracking.student-progress') ? 'active' : '' }}" href="{{ route('admin.topic-tracking.student-progress') }}">
                                <i class="fas fa-clipboard-check"></i>
                                Öğrenci Ders Takibi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.topic-tracking.index') || request()->routeIs('admin.topic-tracking.create') || request()->routeIs('admin.topic-tracking.edit') || request()->routeIs('admin.topic-tracking.show') ? 'active' : '' }}" href="{{ route('admin.topic-tracking.index') }}">
                                <i class="fas fa-tasks"></i>
                                Konu Takip
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.question-analysis.*') ? 'active' : '' }}" href="{{ route('admin.question-analysis.index') }}">
                                <i class="fas fa-question-circle"></i>
                                Soru Analizi
                            </a>
                        </li>
                        
                        <!-- Raporlar -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-header">
                                <small class="text-white-50 fw-bold text-uppercase">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Raporlar
                                </small>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.daily-reports.*') ? 'active' : '' }}" href="{{ route('admin.daily-reports.index') }}">
                                <i class="fas fa-chart-line"></i>
                                Günlük Raporlar
                            </a>
                        </li>
                        
                        <!-- Sistem Yönetimi -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-header">
                                <small class="text-white-50 fw-bold text-uppercase">
                                    <i class="fas fa-cog me-1"></i>
                                    Sistem Yönetimi
                                </small>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.mail.*') ? 'active' : '' }}" href="{{ route('admin.mail.index') }}">
                                <i class="fas fa-envelope"></i>
                                Mail Yönetimi
                            </a>
                        </li>
                        @if(Auth::user()->isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}">
                                <i class="fas fa-users-cog"></i>
                                Admin Yönetimi
                            </a>
                        </li>
                        @endif
                        </ul>
                    </div>
                    
                    <!-- Kullanıcı Bilgisi ve Çıkış Butonu -->
                    <div class="mt-auto p-3 border-top border-white-10">
                        <!-- Kullanıcı Bilgisi -->
                        <div class="text-center mb-3">
                            <div class="text-white-50 small">
                                <i class="fas fa-user-shield me-1"></i>
                                Hoş geldiniz, {{ Auth::user()->name }}
                            </div>
                        </div>
                        
                        <!-- Çıkış Butonu -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100" 
                                    onclick="return confirm('Çıkış yapmak istediğinizden emin misiniz?')">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Çıkış Yap
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Collapse/Expand -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const body = document.body;
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    body.classList.remove('no-scroll');
                }
            });

            // Close sidebar when a nav link is clicked on mobile
            sidebar.querySelectorAll('.nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                        body.classList.remove('no-scroll');
                    }
                });
            });

            // Auto-wrap tables in a responsive container if not already wrapped
            document.querySelectorAll('.main-content table').forEach(function(tbl) {
                if (!tbl.closest('.table-responsive')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'table-responsive';
                    tbl.parentNode.insertBefore(wrapper, tbl);
                    wrapper.appendChild(tbl);
                }
            });
        });
    </script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const body = document.body;
            
            // Only toggle on mobile
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                body.classList.toggle('no-scroll', sidebar.classList.contains('show'));
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
