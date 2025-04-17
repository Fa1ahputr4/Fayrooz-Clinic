<aside class="sidebar bg-[#5e4a7e] text-white"
    :class="{
        'sidebar-collapsed': !sidebarOpen && window.innerWidth >= 1024,
        'sidebar-open': mobileMenuOpen
    }">
    <div class="px-5 py-[26px] text-2xl font-bold border-b border-gray-700 flex items-center gap-3">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                        class="bi bi-speedometer2" viewBox="0 0 16 16">
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z" />
                        <path fill-rule="evenodd"
                            d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3" />
                    </svg>
                    <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="/user"
                    class="nav-link flex items-center py-2.5 px-4 transition-all duration-200 rounded-l-full
                    {{ request()->is('profile') ? 'bg-white text-[#6e5d94] font-semibold' : 'hover:bg-[#6e5d94] hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                        class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                        <path
                            d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
                    </svg>
                    <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">User Management</span>
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
