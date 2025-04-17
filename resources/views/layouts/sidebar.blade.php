<aside class="sidebar bg-[#5e4a7e] text-white"
    :class="{
        'sidebar-collapsed': !sidebarOpen && window.innerWidth >= 1024,
        'sidebar-open': mobileMenuOpen
    }">
    <div class="px-5 py-5.5 text-2xl font-bold border-b border-gray-700 flex items-center gap-3">
        <!-- Logo -->
        <img src="{{ asset('images/fayrooz.png') }}" alt="Logo Klinik" class="h-10 w-10 object-cover rounded-full" />

        <!-- Teks Nama Klinik -->
        <span class="menu-text transition-all duration-200" :class="!sidebarOpen && 'menu-text-hidden'">
            Fayrooz Clinic
        </span>
    </div>

    <nav class="mt-10 ml-3">
        <ul class="space-y-1">
            <li class="relative">
                <a href="/dashboard"
                    class="nav-link flex items-center py-2.5 px-4 transition-all duration-200 rounded-l-full
                    {{ request()->is('dashboard') ? 'bg-gray-100 text-[#6e5d94] font-semibold active-link' : 'hover:bg-[#6e5d94] hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="/user"
                    class="nav-link flex items-center py-2.5 px-4 transition-all duration-200 rounded-l-full
                    {{ request()->is('profile') ? 'bg-white text-[#6e5d94] font-semibold' : 'hover:bg-[#6e5d94] hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">User</span>
                </a>
            </li>

            <li>
                <a href="/settings"
                    class="nav-link flex items-center py-2.5 px-4 transition-all duration-200 hover:bg-[#6e5d94] hover:text-white hover:rounded-l-[20px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    </div>
</aside>
