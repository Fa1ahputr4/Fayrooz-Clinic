<aside x-data="{ scrollTop: 0 }" x-ref="sidebarEl" x-init="requestAnimationFrame(() => {
    const last = localStorage.getItem('sidebar-scroll');
    if (last) $refs.sidebarEl.scrollTop = parseInt(last);
    $refs.sidebarEl.addEventListener('scroll', () => {
        localStorage.setItem('sidebar-scroll', $refs.sidebarEl.scrollTop);
    });
});" id="sidebar"
    class="fixed top-0 left-0 h-full overflow-y-auto bg-[#5e4a7e] text-white z-40 transition-all duration-300 hide-scrollbar"
    :class="{
        'w-64': sidebarOpen && !isMobile,
        'w-20': !sidebarOpen && !isMobile,
        '-translate-x-full': isMobile && !sidebarOpen,
        'translate-x-0 w-64': isMobile && sidebarOpen
    }">

    <!-- Logo -->
    <div class="px-5 py-[26px] text-2xl font-bold border-b border-gray-700 flex items-center gap-3 overflow-hidden">
        <img src="{{ asset('images/fayrooz.png') }}" alt="Logo Klinik"
            class="h-10 w-10 object-cover rounded-full min-w-[2.5rem]" />
        <span class="whitespace-nowrap transition-all duration-200"
            :class="{ 'opacity-0 w-0': !sidebarOpen && !isMobile, 'opacity-100 w-auto': sidebarOpen || isMobile }">
            Fayrooz Clinic
        </span>
    </div>

    <!-- Menu Sections -->
    @php
        $user = auth()->user();
        $role = $user?->role;

        $menuSections = collect(config('menu', []))
            ->map(function ($section) use ($role) {
                $items = collect($section['items'])
                    ->filter(function ($item) use ($role) {
                        return in_array($role, $item['roles'] ?? []) || $role === 'admin';
                    })
                    ->toArray();

                return [
                    'section' => $section['section'],
                    'items' => $items,
                ];
            })
            ->filter(fn($section) => count($section['items']) > 0)
            ->toArray();
    @endphp

    <nav class="mt-5 ml-3 relative overflow-hidden pt-5 pb-10">
        @foreach ($menuSections as $section)
            <div class="px-4 mt-4 mb-2 text-xs font-semibold uppercase tracking-wide text-white text-opacity-50"
                :class="{ 'opacity-0': !sidebarOpen && !isMobile, 'opacity-100': sidebarOpen || isMobile }">
                {{ $section['section'] }}
            </div>

            <ul class="space-y-1">
                @foreach ($section['items'] as $menu)
                    @php
                        $isActive = request()->is(trim($menu['url'], '/'));
                    @endphp

                    <li>
                        <a wire:navigate.hover href="{{ route($menu['url']) }}"
                            class="nav-link flex items-center py-2.5 px-4 transition-all duration-200 rounded-l-full
                               {{ $isActive ? 'bg-gray-100 text-[#6e5d94] font-semibold active-link' : 'hover:bg-[#6e5d94] hover:text-white' }}">

                            <div class="w-6 h-6 flex items-center justify-center min-w-[1.5rem]">
                                {!! $menu['icon'] !!}
                            </div>

                            <span class="ml-3 whitespace-nowrap transition-all duration-200 overflow-hidden"
                                :class="{
                                    'opacity-0 w-0': !sidebarOpen && !isMobile,
                                    'opacity-100 w-auto': sidebarOpen ||
                                        isMobile
                                }">
                                {{ $menu['label'] }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </nav>
</aside>
