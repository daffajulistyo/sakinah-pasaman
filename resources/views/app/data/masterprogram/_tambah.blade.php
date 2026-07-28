@extends('layouts.app.main')

@section('title', ' | Tambah Master Program')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-lg font-bold text-black dark:text-white">Tambah Master Program</h2>
    <a href="/data/masterprogram" class="inline-flex items-center gap-2 rounded border border-stroke px-4 py-2 font-medium text-black hover:bg-gray-2 dark:border-strokedark dark:text-white">
        &laquo; Kembali
    </a>
</div>

<div x-data="userCrud()" class="rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
    <div x-show="successAlert.open" class="relative px-4 py-3 text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-300" role="alert">
        <span x-text="successAlert.message"></span>
        <button class="absolute right-2 top-2" @click="successAlert.open = false">&times;</button>
    </div>
    <div x-show="failedAlert.open" class="relative px-4 py-3 text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-300" role="alert">
        <span x-text="failedAlert.message"></span>
        <button class="absolute right-2 top-2" @click="failedAlert.open = false">&times;</button>
    </div>

    <div class="p-6">
        <form @submit.prevent="simpanData">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-2.5 block font-medium text-black dark:text-white">Kode Program</label>
                    <input type="text" x-model="form.kode_program" required
                        class="w-full rounded-lg border border-stroke bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input"
                        placeholder="PROG-XXX">
                </div>
                <div>
                    <label class="mb-2.5 block font-medium text-black dark:text-white">Tahun</label>
                    <input type="number" x-model="form.tahun" required min="2020" max="2035"
                        class="w-full rounded-lg border border-stroke bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2.5 block font-medium text-black dark:text-white">Nama Program</label>
                    <input type="text" x-model="form.nama_program" required
                        class="w-full rounded-lg border border-stroke bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input"
                        placeholder="Nama program...">
                </div>
                <div>
                    <label class="mb-2.5 block font-medium text-black dark:text-white">OPD</label>
                    <select x-model="form.kode_skpd" required
                        class="w-full rounded-lg border border-stroke bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input">
                        <option value="">-- Pilih OPD --</option>
                        @foreach($opd as $o)
                        <option value="{{ $o->kode_opd }}">{{ $o->kode_opd }} - {{ $o->nama_opd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="checkbox" x-model="form.is_active" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="font-medium text-black dark:text-white">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="rounded bg-primary px-6 py-2.5 font-medium text-white hover:bg-opacity-90" :disabled="loadingState">
                    <span x-show="!loadingState">Simpan Data</span>
                    <span x-show="loadingState">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('_inJs')
@include('app.data.masterprogram._inJsTambah')
@endsection

