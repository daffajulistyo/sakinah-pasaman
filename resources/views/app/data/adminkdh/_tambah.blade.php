@extends('layouts.app.main')
@section('title', ' | Tambah Admin KDH')
@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-lg font-bold text-black dark:text-white">Tambah Admin KDH</h2>
    <a href="/data/adminkdh" class="inline-flex items-center gap-2 rounded border border-stroke px-4 py-2 font-medium hover:bg-gray-2 dark:border-strokedark dark:text-white">&laquo; Kembali</a>
</div>
<div x-data="userCrud()" class="rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
    <div x-show="successAlert.open" class="relative px-4 py-3 text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-300" role="alert"><span x-text="successAlert.message"></span></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left"><thead class="border-b border-stroke bg-gray-2 dark:border-strokedark dark:bg-meta-4"><tr><th class="px-4 py-3 w-12">No</th><th class="px-4 py-3">NIP</th><th class="px-4 py-3">Nama</th><th class="px-4 py-3">OPD</th><th class="px-4 py-3">Jabatan</th><th class="px-4 py-3 w-16">Action</th></tr></thead>
            <tbody>
                <template x-for="(row, index) in datatable.data" :key="index">
                    <tr class="border-b border-stroke dark:border-strokedark"><td class="px-4 py-3" x-text="datatable.numbering(index)"></td><td class="px-4 py-3" x-text="row.nip"></td><td class="px-4 py-3" x-text="row.nama_pegawai"></td><td class="px-4 py-3" x-text="row.opd"></td><td class="px-4 py-3" x-text="row.jabatan"></td><td class="px-4 py-3"><button class="text-primary hover:text-opacity-70" @click="confirmAdd(row.id)"><svg class="fill-current" width="18" height="18" viewBox="0 0 18 18"><path d="M9 0C8.44772 0 8 0.447715 8 1V8H1C0.447715 8 0 8.44772 0 9C0 9.55228 0.447715 10 1 10H8V17C8 17.5523 8.44772 18 9 18C9.55228 18 10 17.5523 10 17V10H17C17.5523 10 18 9.55228 18 9C18 8.44772 17.5523 8 17 8H10V1C10 0.447715 9.55228 0 9 0Z" fill=""/></svg></button></td></tr>
                </template>
                <tr x-show="datatable.isEmpty() && !datatable.loading"><td class="px-4 py-6 text-center text-bodydark2" colspan="100%">No data.</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('_inJs') @include('app.data.adminkdh._inJsTambah') @endsection

