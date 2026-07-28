@extends('backend.layouts.app')

@section('content-header')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">@yield('page-title', 'Dashboard')</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'Dashboard')</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->
@endsection

@section('content')
<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        @yield('main-content')
    </div>
</div>
<!--end::App Content-->
@endsection
