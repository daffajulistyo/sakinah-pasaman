@extends('layouts.app.main')
@section('title', ' | Admin KDH')
@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-lg font-bold text-black dark:text-white">Admin Kepala Daerah</h2>
    <a href="/data/adminkdh/tambah" class="inline-flex items-center gap-2 rounded bg-primary px-4 py-2 font-medium text-white hover:bg-opacity-90">+ Tambah</a>
</div>
<div x-data="userCrud()" class="rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
    <div x-show="successAlert.open" class="relative px-4 py-3 text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-300" role="alert"><span x-text="successAlert.message"></span></div>
    <div class="p-4"><input type="text" x-model="datatable.search" @input.debounce.500ms="datatable.refreshTable()" class="w-64 rounded border border-stroke bg-transparent px-3 py-1.5 text-sm outline-none focus:border-primary dark:border-strokedark" placeholder="Search..."></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left"><thead class="border-b border-stroke bg-gray-2 dark:border-strokedark dark:bg-meta-4"><tr><th class="px-4 py-3 w-12">No</th><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Username</th><th class="px-4 py-3">OPD</th><th class="px-4 py-3">Jabatan</th><th class="px-4 py-3">Dibuat</th><th class="px-4 py-3 w-16">Action</th></tr></thead>
            <tbody>
                <template x-for="(row, index) in datatable.data" :key="index">
                    <tr class="border-b border-stroke dark:border-strokedark"><td class="px-4 py-3" x-text="datatable.numbering(index)"></td><td class="px-4 py-3" x-text="row.name"></td><td class="px-4 py-3" x-text="row.username"></td><td class="px-4 py-3" x-text="row.opd"></td><td class="px-4 py-3" x-text="row.jabatan"></td><td class="px-4 py-3 text-sm text-bodydark2" x-text="row.created_at"></td><td class="px-4 py-3"><template x-if="row.username != 'superadmin'"><button class="text-red-500 hover:text-red-700" @click="confirmDelete(row.user_id, row.id_roleplay)"><svg class="fill-current" width="16" height="16" viewBox="0 0 16 16"><path d="M2 4h12M5.333 4V2.667a1.333 1.333 0 011.334-1.334h2.666a1.333 1.333 0 011.334 1.334V4m2 0v9.333a1.333 1.333 0 01-1.334 1.334H4.667a1.333 1.333 0 01-1.334-1.334V4h9.334z"/></svg></button></template></td></tr>
                </template>
                <tr x-show="datatable.isEmpty() && !datatable.loading"><td class="px-4 py-6 text-center text-bodydark2" colspan="100%">No data.</td></tr>
                <tr x-show="datatable.loading"><td class="px-4 py-6 text-center text-bodydark2" colspan="100%">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('_inJs') @include('app.data.adminkdh._inJs') @endsection

