<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-icon.svg') }}">
    <title>@yield('title', 'Dashboard') | SAKINAH</title>
    <link rel="stylesheet" href="{{ asset('css/satoshi.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { satoshi: ['Satoshi', 'sans-serif'] },
                    colors: {
                        primary: '#3C50E0',
                        'primary-hover': '#3051D3',
                        secondary: '#80CAEE',
                        body: '#64748B',
                        bodydark: '#AEB7C0',
                        bodydark1: '#DEE4EE',
                        bodydark2: '#8A99AF',
                        stroke: '#E2E8F0',
                        strokedark: '#2E3A47',
                        'form-strokedark': '#3d4d60',
                        'form-input': '#1d2a39',
                        whiten: '#F1F5F9',
                        boxdark: '#24303F',
                        'boxdark-2': '#1A222C',
                        graydark: '#333A48',
                        'gray-2': '#F7F9FC',
                        'gray-3': '#F8F9FA',
                        'meta-2': '#F0F9FF',
                        'meta-3': '#10B981',
                        'meta-4': '#313D4A',
                        'meta-5': '#259AE6',
                        'meta-6': '#FFBA00',
                        'meta-7': '#FF6766',
                    },
                    spacing: {
                        '5.5': '1.375rem',
                        '6.5': '1.625rem',
                        '72.5': '18.125rem',
                    },
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body
    x-data="{ page: 'dashboard', loaded: true, darkMode: false, sidebarToggle: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{ 'dark bg-boxdark-2': darkMode === true }"
    class="bg-whiten">

    <div class="flex h-screen overflow-hidden">

        <aside
            :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
            class="fixed left-0 top-0 z-[9999] flex h-screen w-72.5 flex-col overflow-y-hidden bg-boxdark duration-300 ease-linear lg:static lg:translate-x-0"
            @click.outside="sidebarToggle = false">

            <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
                <a href="{{ url('/home') }}">
                    <img src="{{ asset('images/logo/logo-icon.svg') }}" alt="Logo" class="h-8">
                </a>
                <button class="block text-white lg:hidden hover:text-bodydark1" @click.stop="sidebarToggle = !sidebarToggle">
                    <svg class="fill-current" width="20" height="18" viewBox="0 0 20 18" fill="none">
                        <path d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z" fill=""/>
                    </svg>
                </button>
            </div>

            <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
                @include('layouts.app.partials.sidebar')
            </div>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            @include('layouts.app.partials.header')

            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        var token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }
    </script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    @yield('_inJs')
    @stack('scripts')
</body>
</html>

