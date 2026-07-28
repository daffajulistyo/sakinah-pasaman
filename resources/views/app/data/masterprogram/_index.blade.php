@extends('layouts.app.main')

@section('title', ' | Master Program')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-lg font-bold text-black dark:text-white">Master Program Anggaran</h2>
    <a href="/data/masterprogram/tambah" class="inline-flex items-center gap-2 rounded bg-primary px-4 py-2 font-medium text-white hover:bg-opacity-90">
        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16"><path d="M8 0C7.44772 0 7 0.447715 7 1V7H1C0.447715 7 0 7.44772 0 8C0 8.55228 0.447715 9 1 9H7V15C7 15.5523 7.44772 16 8 16C8.55228 16 9 15.5523 9 15V9H15C15.5523 9 16 8.55228 16 8C16 7.44772 15.5523 7 15 7H9V1C9 0.447715 8.55228 0 8 0Z" fill=""/></svg>
        Tambah
    </a>
</div>

<div x-data="userCrud()" class="rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
    <div x-show="successAlert.open" class="relative px-4 py-3 text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-300" role="alert">
        <span x-text="successAlert.message"></span>
        <button class="absolute right-2 top-2" @click="successAlert.open = false">&times;</button>
    </div>
    <div class="p-4">
        <div class="flex items-center gap-2 mb-3">
            <input type="text" x-model="datatable.search" @input.debounce.500ms="datatable.refreshTable()"
                class="w-64 rounded border border-stroke bg-transparent px-3 py-1.5 text-sm outline-none focus:border-primary dark:border-strokedark"
                placeholder="Search...">
            <div class="ms-auto flex items-center gap-2 text-sm">
                <span class="text-bodydark2">Show</span>
                <select x-model="datatable.pagination.per_page" @change="datatable.refreshTable()"
                    class="rounded border border-stroke bg-transparent px-2 py-1 text-sm dark:border-strokedark">
                    <option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="border-b border-stroke bg-gray-2 dark:border-strokedark dark:bg-meta-4">
                <tr>
                    <th class="px-4 py-3 w-12">No</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Program</th>
                    <th class="px-4 py-3">OPD</th>
                    <th class="px-4 py-3">Kode SKPD</th>
                    <th class="px-4 py-3">Tahun</th>
                    <th class="px-4 py-3">Aktif</th>
                    <th class="px-4 py-3">Dibuat</th>
                    <th class="px-4 py-3 w-16">Action</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, index) in datatable.data" :key="index">
                    <tr class="border-b border-stroke dark:border-strokedark" x-show="!datatable.loading">
                        <td class="px-4 py-3" x-text="datatable.numbering(index)"></td>
                        <td class="px-4 py-3" x-text="row.kode_program"></td>
                        <td class="px-4 py-3" x-text="row.nama_program"></td>
                        <td class="px-4 py-3" x-text="row.nama_opd"></td>
                        <td class="px-4 py-3" x-text="row.kode_skpd"></td>
                        <td class="px-4 py-3" x-text="row.tahun"></td>
                        <td class="px-4 py-3">
                            <span x-show="row.is_active == 1" class="inline-block rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            <span x-show="row.is_active != 1" class="inline-block rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Non</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-bodydark2" x-text="row.created_at"></td>
                        <td class="px-4 py-3">
                            <button class="text-red-500 hover:text-red-700" @click="confirmDelete(row.id)"><svg class="fill-current" width="16" height="16" viewBox="0 0 16 16"><path d="M2 4h12M5.333 4V2.667a1.333 1.333 0 011.334-1.334h2.666a1.333 1.333 0 011.334 1.334V4m2 0v9.333a1.333 1.333 0 01-1.334 1.334H4.667a1.333 1.333 0 01-1.334-1.334V4h9.334z"/></svg></button>
                        </td>
                    </tr>
                </template>
                <tr x-show="datatable.isEmpty() && !datatable.loading">
                    <td class="px-4 py-6 text-center text-bodydark2" colspan="100%">No data found.</td>
                </tr>
                <tr x-show="datatable.loading">
                    <td class="px-4 py-6 text-center text-bodydark2" colspan="100%">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-4 border-t border-stroke dark:border-strokedark">
        <span class="text-sm text-bodydark2">
            Showing <span class="font-medium" x-text="datatable.showingLabel()"></span>
            of <span class="font-medium" x-text="datatable.pagination.total_records"></span>
        </span>
        <div class="flex gap-1">
            <button @click="datatable.previousPage" :disabled="datatable.pagination.page == 1"
                class="rounded border border-stroke px-3 py-1 text-sm hover:bg-gray-2 dark:border-strokedark disabled:opacity-50">&laquo;</button>
            <template x-for="page in datatable.pages">
                <button @click="datatable.goToPage(page)" x-text="page"
                    class="rounded border px-3 py-1 text-sm"
                    :class="datatable.isCurrentPage(page) ? 'bg-primary text-white border-primary' : 'border-stroke hover:bg-gray-2 dark:border-strokedark'"
                    :disabled="page === '...'"></button>
            </template>
            <button @click="datatable.nextPage" :disabled="datatable.pagination.page == datatable.pagination.total_page"
                class="rounded border border-stroke px-3 py-1 text-sm hover:bg-gray-2 dark:border-strokedark disabled:opacity-50">&raquo;</button>
        </div>
    </div>
</div>
@endsection

@section('_inJs')
@include('app.data.masterprogram._inJs')
@endsection

