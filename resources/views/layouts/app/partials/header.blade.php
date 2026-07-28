@php
$myroles = session('myroles.roles', []);
$user = auth()->user();
$currentRoleName = session('current_role_name', 'Role');
@endphp

<header class="sticky top-0 z-[999] flex w-full bg-white shadow-sm dark:bg-boxdark dark:shadow-none">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-sm md:px-6 2xl:px-11">
        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <button class="z-[99999] block rounded-sm border border-stroke bg-white p-1.5 shadow-sm dark:border-strokedark dark:bg-boxdark lg:hidden"
                @click.stop="sidebarToggle = !sidebarToggle">
                <svg class="fill-current" width="20" height="14" viewBox="0 0 20 14" fill="none">
                    <path d="M0 1C0 0.447715 0.447715 0 1 0H19C19.5523 0 20 0.447715 20 1C20 1.55228 19.5523 2 19 2H1C0.447715 2 0 1.55228 0 1Z" fill=""/>
                    <path d="M0 7C0 6.44772 0.447715 6 1 6H19C19.5523 6 20 6.44772 20 7C20 7.55228 19.5523 8 19 8H1C0.447715 8 0 7.55228 0 7Z" fill=""/>
                    <path d="M0 13C0 12.4477 0.447715 12 1 12H12C12.5523 12 13 12.4477 13 13C13 13.5523 12.5523 14 12 14H1C0.447715 14 0 13.5523 0 13Z" fill=""/>
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-3 ms-auto">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 text-gray-600 hover:text-black dark:text-gray-400 dark:hover:text-white">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-medium text-white">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </span>
                    <span class="text-sm font-medium text-black dark:text-white">
                        {{ $user->name ?? 'User' }}
                        <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ $currentRoleName }})</span>
                    </span>
                    <svg class="fill-current" width="12" height="8" viewBox="0 0 12 8">
                        <path d="M1.41 0L6 4.58L10.59 0L12 1.41L6 7.41L0 1.41L1.41 0Z" fill=""/>
                    </svg>
                </button>
                    <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 mt-1 w-56 rounded-sm border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark z-50">
                    <div class="border-b border-stroke px-4 py-3 dark:border-strokedark">
                        <span class="block text-sm font-medium text-black dark:text-white">{{ $user->name ?? 'User' }}</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $currentRoleName }}</span>
                    </div>
                    @if(count($myroles) > 0)
                    <div class="border-b border-stroke px-4 py-2 dark:border-strokedark">
                        <span class="text-xs font-medium text-gray-500 uppercase">Switch Role</span>
                    </div>
                    @foreach($myroles as $role)
                    <a href="{{ url('/roleplay/switch/' . $role['id']) }}"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-boxdark-2 transition-colors">
                        <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
                        {{ $role['role_name'] ?? '' }}
                    </a>
                    @endforeach
                    @endif
                    <div class="border-t border-stroke dark:border-strokedark">
                        <a href="{{ url('/logout') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-gray-50 dark:hover:bg-boxdark-2 transition-colors">
                            <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16"><path d="M5 1C4.44772 1 4 1.44772 4 2V3H6V3C6 2.44772 5.55228 2 5 2V1ZM2 4C1.44772 4 1 4.44772 1 5V12C1 12.5523 1.44772 13 2 13H8C8.55228 13 9 12.5523 9 12V11H7V11C7 11.5523 7.44772 12 8 12V12C8 12.5523 8.44772 13 8 12V11ZM9 6C9 5.44772 9.44772 5 10 5H12.5858L11.2929 3.70711C10.9024 3.31658 10.9024 2.68342 11.2929 2.29289C11.6834 1.90237 12.3166 1.90237 12.7071 2.29289L13.7071 3.29289C14.0976 3.68342 14.0976 4.31658 13.7071 4.70711L12.7071 5.70711C12.3166 6.09763 11.6834 6.09763 11.2929 5.70711C10.9024 5.31658 10.9024 4.68342 11.2929 4.29289L11.5858 4H10C9.44772 4 9 4.44772 9 5V6Z" fill="currentColor"/></svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <button @click="darkMode = !darkMode"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-stroke text-gray-500 hover:text-black dark:border-strokedark dark:hover:text-white transition-colors">
                <svg class="hidden dark:block fill-current" width="18" height="18" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 10 4.2915C9.58579 4.2915 9.25 3.95572 9.25 3.5415V2.2915C9.25 1.87729 9.58579 1.5415 10 1.5415Z" fill="currentColor"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327Z" fill="currentColor"/>
                </svg>
                <svg class="dark:hidden fill-current" width="18" height="18" viewBox="0 0 20 20">
                    <path d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97Z" fill="currentColor"/>
                    <path d="M8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459Z" fill="currentColor"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0003 16.9586C6.15734 16.9586 3.04199 13.8433 3.04199 10.0003H1.54199C1.54199 14.6717 5.32892 18.4586 10.0003 18.4586V16.9586Z" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>
</header>

