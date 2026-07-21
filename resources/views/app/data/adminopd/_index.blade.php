@extends('layouts.app.main')

@section('title', ' | ADMIN PERANGKAT DAERAH')

@section('content')
<h3 class="text-gray-700 text-3xl font-medium">Data Admin Perangkat Daerah</h3>
<div class="container bg-white p-10 my-10" x-data="userCrud()">
    {{-- <button class="relative bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="livewire.emit('refreshUser')">Refresh Table</button> --}}
    <div x-show="successAlert.open" class="relative py-3 pl-4 pr-10 leading-normal text-blue-700 bg-blue-100 rounded-lg mb-3" role="alert">
        <p x-text="successAlert.message">A simple alert with text and a right icon</p>
        <span class="absolute inset-y-0 right-0 flex items-center mr-4" @click="successAlert.open = false">
          <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
        </span>
    </div>
    <a  href="/data/adminopd/tambah"
    class="relative bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2 focus:outline-none focus:ring-4 focus:ring-aqua-400 disabled:cursor-wait disabled:bg-blue-700"
    :disabled="loadingState"
    >
        <template x-if="!loadingState">
            <i class="fa fa-plus"></i>
        </template>
        <template x-if="loadingState">
            <i class="fa fa-spinner animate-spin"></i>    
        </template>
        <span x-text="loadingState ? `Loading...` : `Tambah Data`"></span>
    </a>

    
    <!-- ini datatable -->
    <x-app.datatable.datatable>
        <x-slot:thead>
            <tr>
                <th scope="col" class="px-4 py-3 w-[1%]">No</th>
                <th scope="col" class="px-4 py-3">Nama</th>
                <th scope="col" class="px-4 py-3">Username</th>
                <th scope="col" class="px-4 py-3">OPD</th>
                <th scope="col" class="px-4 py-3">Jabatan</th>
                <th scope="col" class="px-4 py-3">Dibuat</th>
                <th scope="col" class="px-4 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </x-slot:thead>
        <x-slot:tbody>
            <template x-for="(row, index) in datatable.data" :key="index">
                <tr x-show="!datatable.loading" class="border-b dark:border-gray-700">
                    <td class="px-4 py-3 text-right" x-text="datatable.numbering(index)"></td>
                    <td class="px-4 py-3" x-text="row.name"></td>
                    <td class="px-4 py-3" x-text="row.username"></td>
                    <td class="px-4 py-3" x-text="row.opd"></td>
                    <td class="px-4 py-3" x-text="row.jabatan"></td>
                    <td class="px-4 py-3" x-text="row.created_at"></td>
                    <td class="px-4 py-3 flex items-center justify-center">
                        <template x-if="row.username != 'superadmin'">
                            <div>
                                <button 
                                :disabled="loadingState"
                                class="inline-flex items-center justify-center w-8 h-8 mr-2 text-indigo-100 transition-colors duration-150 bg-orange-700 rounded-full focus:shadow-outline hover:bg-orange-800 
                                        disabled:cursor-wait disabled:bg-orange-800 " @click="confirmDelete(row.user_id, row.id_roleplay)">
                                    
                                    
                                    <template x-if="!loadingState">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </template>
                                    <template x-if="loadingState">
                                        <i class="fa fa-spinner animate-spin"></i>
                                    </template>
                                </button>
                            </div>
                        </template>
                    </td>
                </tr>
            </template>
        </x-slot:tbody>
    </x-app.datatable.datatable>
    <!-- ini datatable -->
</div>
@endsection

<!-- Your Custom Javascript -->
@section('_inJs')
@include('app.data.adminopd._inJs')
@endsection
<!-- /Your Custom Javascript -->