@extends('layouts.app.main')
@section('title', 'Dashboard')
@section('content')
<div class="rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark p-6">
    <h2 class="text-lg font-bold text-black dark:text-white mb-2">Selamat Datang!</h2>
    <p class="text-lg text-bodydark2">{{ $user->name }}, anda login sebagai <span class="rounded bg-primary px-2 py-0.5 text-sm text-white">{{ $current_role_name }}</span></p>
</div>
@endsection

