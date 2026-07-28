<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-icon.svg') }}">
    <title>@yield('title', 'Dashboard') | Backend SAKINAH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; }
        body { background: #f5f6fa; font-size: 0.875rem; }
        .sidebar { width: var(--sidebar-width); background: #1e293b; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1040; transition: transform 0.3s; }
        .sidebar .nav-link { color: #94a3b8; padding: 0.6rem 1.25rem; font-size: 0.8125rem; border-radius: 0.5rem; margin: 0.125rem 0.75rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.08); }
        .sidebar .nav-link i { font-size: 1.1rem; width: 1.5rem; }
        .sidebar .menu-title { color: #64748b; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.25rem 0.25rem; font-weight: 600; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .navbar-top { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        .card-stat { border-left: 4px solid #0d6efd; transition: transform 0.15s; }
        .card-stat:hover { transform: translateY(-2px); }
        .btn-sm-table { padding: 0.15rem 0.45rem; font-size: 0.75rem; }
        .pagination { --bs-pagination-font-size: 0.8125rem; }
        @media (max-width: 991.98px) { .sidebar { transform: translateX(-100%); } .sidebar.show { transform: translateX(0); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="d-flex align-items-center gap-2 px-4 py-3 border-bottom border-white border-opacity-10">
            <img src="{{ asset('images/logo/logo-icon.svg') }}" alt="Logo" height="28">
            <span class="text-white fw-semibold small">SAKINAH</span>
        </div>
        <nav class="mt-2">
            <div class="menu-title">Menu</div>
            <a href="{{ url('/backend/home') }}" class="nav-link {{ request()->is('backend/home') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="menu-title">Master Data</div>
            @php
            $menus = [
                ['route' => 'backend/jabatan', 'label' => 'Jabatan', 'icon' => 'bi-briefcase'],
                ['route' => 'backend/eselon', 'label' => 'Eselon', 'icon' => 'bi-bar-chart'],
                ['route' => 'backend/jenis-jabatan', 'label' => 'Jenis Jabatan', 'icon' => 'bi-tags'],
                ['route' => 'backend/golongan', 'label' => 'Golongan', 'icon' => 'bi-award'],
                ['route' => 'backend/program', 'label' => 'Program', 'icon' => 'bi-journal-code'],
                ['route' => 'backend/anggaran', 'label' => 'Anggaran', 'icon' => 'bi-cash-stack'],
                ['route' => 'backend/opd', 'label' => 'OPD', 'icon' => 'bi-building'],
                ['route' => 'backend/sub-opd', 'label' => 'Sub OPD', 'icon' => 'bi-diagram-2'],
                ['route' => 'backend/satuan', 'label' => 'Satuan', 'icon' => 'bi-rulers'],
            ];
            @endphp
            @foreach($menus as $m)
            <a href="{{ url('/'.$m['route']) }}" class="nav-link {{ request()->is($m['route'].'*') ? 'active' : '' }}">
                <i class="bi {{ $m['icon'] }}"></i> {{ $m['label'] }}
            </a>
            @endforeach

            <div class="menu-title">Pengguna</div>
            <a href="{{ url('/backend/user') }}" class="nav-link {{ request()->is('backend/user*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> User Management
            </a>
            <a href="{{ url('/backend/pegawai') }}" class="nav-link {{ request()->is('backend/pegawai*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Pegawai
            </a>
        </nav>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-top navbar-light px-3 px-md-4 py-2 sticky-top">
            <button class="navbar-toggler border-0 p-0 d-lg-none" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="ms-auto d-flex align-items-center gap-3">
                @php $user = auth()->user(); $currentRoleName = session('current_role_name', 'Role'); @endphp
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:30px;height:30px;font-size:0.75rem;">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                        <span class="d-none d-sm-inline small">{{ $user->name }} <span class="text-muted">({{ $currentRoleName }})</span></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted">{{ $user->name }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="{{ url('/backend/logout') }}" class="dropdown-item text-danger small"><i class="bi bi-box-arrow-right me-1"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="p-3 p-md-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show small" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            if (window.innerWidth < 992 && sidebar.classList.contains('show') && !sidebar.contains(e.target) && e.target !== toggle) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
