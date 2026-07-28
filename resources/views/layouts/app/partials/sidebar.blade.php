@php
$menuGroups = \DB::table('menugroups')->orderBy('menugroup_order')->get();
$allMenus = \DB::table('menus')
    ->join('menugroups', 'menugroups.id', '=', 'menus.menugroup_id')
    ->orderBy('menugroups.menugroup_order')
    ->orderBy('menus.menu_order')
    ->select('menus.*', 'menugroups.menugroup_label')
    ->get();
$grouped = $allMenus->groupBy('menugroup_id');
@endphp

<nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6" x-data="{ selected: '' }">
    <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">MENU</h3>
        <ul class="mb-6 flex flex-col gap-1.5">
            <li>
                <a href="{{ url('/home') }}"
                   class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark {{ request()->is('home') ? 'bg-graydark' : '' }}">
                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M6.10322 0.956299H2.53135C1.5751 0.956299 0.787598 1.7438 0.787598 2.70005V6.27192C0.787598 7.22817 1.5751 8.01567 2.53135 8.01567H6.10322C7.05947 8.01567 7.84697 7.22817 7.84697 6.27192V2.72817C7.8751 1.7438 7.0876 0.956299 6.10322 0.956299Z" fill=""/>
                        <path d="M15.4689 0.956299H11.8971C10.9408 0.956299 10.1533 1.7438 10.1533 2.70005V6.27192C10.1533 7.22817 10.9408 8.01567 11.8971 8.01567H15.4689C16.4252 8.01567 17.2127 7.22817 17.2127 6.27192V2.72817C17.2127 1.7438 16.4252 0.956299 15.4689 0.956299Z" fill=""/>
                        <path d="M6.10322 9.92822H2.53135C1.5751 9.92822 0.787598 10.7157 0.787598 11.672V15.2438C0.787598 16.2001 1.5751 16.9876 2.53135 16.9876H6.10322C7.05947 16.9876 7.84697 16.2001 7.84697 15.2438V11.7001C7.8751 10.7157 7.0876 9.92822 6.10322 9.92822Z" fill=""/>
                        <path d="M15.4689 9.92822H11.8971C10.9408 9.92822 10.1533 10.7157 10.1533 11.672V15.2438C10.1533 16.2001 10.9408 16.9876 11.8971 16.9876H15.4689C16.4252 16.9876 17.2127 16.2001 17.2127 15.2438V11.7001C17.2127 10.7157 16.4252 9.92822 15.4689 9.92822Z" fill=""/>
                    </svg>
                    Dashboard
                </a>
            </li>
        </ul>
    </div>

    @foreach($menuGroups as $group)
    @php $menus = $grouped->get($group->id, collect()); @endphp
    @if($menus->count() > 0)
    <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">{{ strtoupper($group->menugroup_label) }}</h3>
        <ul class="mb-6 flex flex-col gap-1.5">
            @foreach($menus as $menu)
            <li>
                <a href="{{ url($menu->route) }}"
                   class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark {{ request()->is(trim($menu->route, '/').'*') ? 'bg-graydark' : '' }}">
                    @if($menu->menu_icon)
                    <span class="[&>svg]:w-5 [&>svg]:h-5 [&>svg]:fill-current">{!! $menu->menu_icon !!}</span>
                    @endif
                    {{ $menu->menu_label }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
    @endforeach
</nav>

