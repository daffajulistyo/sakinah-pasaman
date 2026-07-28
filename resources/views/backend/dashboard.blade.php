@extends('backend.layouts.main')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('main-content')
<div class="d-flex align-items-center gap-3 mb-4">
    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
        <i class="bi bi-speedometer2 text-primary fs-5"></i>
    </div>
    <div>
        <h5 class="fw-bold mb-0">Selamat Datang, {{ $user->name }}</h5>
        <span class="text-muted small">Anda login sebagai <span class="badge bg-primary bg-opacity-10 text-primary">{{ $current_role_name }}</span></span>
    </div>
</div>

<div class="row g-3">
    @php
    $cards = [
        ['title' => 'Jabatan', 'icon' => 'bi-briefcase', 'route' => '/backend/jabatan', 'desc' => 'Data referensi jabatan', 'color' => 'primary'],
        ['title' => 'Eselon', 'icon' => 'bi-bar-chart', 'route' => '/backend/eselon', 'desc' => 'Data referensi eselon', 'color' => 'success'],
        ['title' => 'Jenis Jabatan', 'icon' => 'bi-tags', 'route' => '/backend/jenis-jabatan', 'desc' => 'Jenis-jenis jabatan', 'color' => 'info'],
        ['title' => 'Golongan', 'icon' => 'bi-award', 'route' => '/backend/golongan', 'desc' => 'Data golongan & pangkat', 'color' => 'warning'],
        ['title' => 'Program', 'icon' => 'bi-journal-code', 'route' => '/backend/program', 'desc' => 'Data program anggaran', 'color' => 'danger'],
        ['title' => 'Anggaran', 'icon' => 'bi-cash-stack', 'route' => '/backend/anggaran', 'desc' => 'Data anggaran per OPD', 'color' => 'primary'],
        ['title' => 'OPD', 'icon' => 'bi-building', 'route' => '/backend/opd', 'desc' => 'Data perangkat daerah', 'color' => 'success'],
        ['title' => 'Sub OPD', 'icon' => 'bi-diagram-2', 'route' => '/backend/sub-opd', 'desc' => 'Data sub bagian OPD', 'color' => 'info'],
        ['title' => 'Satuan', 'icon' => 'bi-rulers', 'route' => '/backend/satuan', 'desc' => 'Data satuan pengukuran', 'color' => 'warning'],
        ['title' => 'User', 'icon' => 'bi-people', 'route' => '/backend/user', 'desc' => 'Manajemen user & role', 'color' => 'danger'],
        ['title' => 'Pegawai', 'icon' => 'bi-person-badge', 'route' => '/backend/pegawai', 'desc' => 'Data pegawai', 'color' => 'primary'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url($c['route']) }}" class="card card-stat text-decoration-none h-100 border-0 shadow-sm" style="border-left-color: var(--bs-{{ $c['color'] }});">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded bg-{{ $c['color'] }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                        <i class="bi {{ $c['icon'] }} text-{{ $c['color'] }}"></i>
                    </div>
                    <span class="fw-semibold small text-dark">{{ $c['title'] }}</span>
                </div>
                <p class="text-muted mb-0" style="font-size:0.75rem;">{{ $c['desc'] }}</p>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection
