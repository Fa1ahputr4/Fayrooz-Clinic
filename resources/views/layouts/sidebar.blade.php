<aside x-cloak class="sidebar bg-[#5e4a7e] text-white fixed top-0 left-0 h-full z-10"
    :class="{ 'sidebar-collapsed': !$store.sidebar.open }">


    <div class="px-5 py-[26px] text-2xl font-bold border-b border-gray-700 flex items-center gap-3">
        <!-- Logo -->
        <img src="{{ asset('images/fayrooz.png') }}" alt="Logo Klinik" class="h-10 w-10 object-cover rounded-full" />

        <!-- Teks Nama Klinik -->
        <span class="menu-text transition-all duration-200" :class="!$store.sidebar.open && 'menu-text-hidden'">
            Fayrooz Clinic
        </span>
    </div>

    @php
        $user = auth()->user();
        $role = $user->role;

        $menus = collect(config('menu'))->filter(function ($menu) use ($role) {
            return in_array($role, $menu['roles']) || $role === 'admin';
        });
    @endphp

    <nav class="mt-10 ml-3">
        <ul class="space-y-1">
           @foreach ($menus as $menu)
    @php
        // Cek apakah salah satu submenu aktif
        $isSubmenuActive = false;

        if (isset($menu['submenu'])) {
            foreach ($menu['submenu'] as $submenu) {
                if (request()->is(trim($submenu['url'], '/'))) {
                    $isSubmenuActive = true;
                    break;
                }
            }
        }

        // Tandai menu parent aktif jika:
        // - URL utama aktif, atau
        // - Salah satu submenu aktif
        $isActive = request()->is(trim($menu['url'], '/')) || $isSubmenuActive;
    @endphp

    <li class="relative" x-data="{ open: {{ $isSubmenuActive ? 'true' : 'false' }} }">
        <a href="{{ $menu['url'] === '' ? '#' : url($menu['url']) }}"
            @if (isset($menu['submenu'])) @click.prevent="open = !open" @endif
            class="nav-link flex items-center py-2.5 px-4 transition-all duration-200 rounded-l-full
            {{ $isActive ? 'bg-gray-100 text-[#6e5d94] font-semibold active-link' : 'hover:bg-[#6e5d94] hover:text-white' }}">

            <div class="w-6 h-6 flex items-center justify-center">
                {!! $menu['icon'] !!}
            </div>

            <span class="menu-text ml-3" :class="!$store.sidebar.open && 'menu-text-hidden'">
                {{ $menu['label'] }}
            </span>

            @if (isset($menu['submenu']))
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor"
                    class="ml-auto transition-transform duration-300"
                    :class="open ? 'rotate-0' : 'rotate-90'">
                    <path d="M10 6l-4 4-4-4h8z" />
                </svg>
            @endif
        </a>

        @if (isset($menu['submenu']))
            <ul x-show="open" x-transition
                class="space-y-1 pl-1 mt-2 bg-white bg-opacity-10 shadow-lg rounded-lg mr-[13px]">
                @foreach ($menu['submenu'] as $submenu)
                    @php
                        $submenuActive = request()->is(trim($submenu['url'], '/'));
                    @endphp
                    <li class="py-1">
                        <a href="{{ $submenu['url'] }}"
                            class="nav-link flex items-center gap-3 transition-all duration-200 px-3 py-1 mr-1
                            {{ $submenuActive ? 'text-white font-bold border-2 rounded-lg border-opacity-20 border-white' : 'hover:bg-white hover:bg-opacity-20 hover:text-white rounded-lg' }}">
                            <div class="w-6 h-6">
                                {!! $submenu['icon'] !!}
                            </div>
                            <span class="submenu-text transition-all duration-200"
                                :class="!$store.sidebar.open && 'submenu-text-hidden'">
                                {{ $submenu['label'] }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach

        </ul>
    </nav>
</aside>
