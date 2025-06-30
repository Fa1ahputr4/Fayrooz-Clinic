<header class="bg-white shadow mt-5 mr-5 px-6 py-4 flex justify-between border-b border-gray-200 items-center rounded-t-[30px]">
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-1 rounded-full bg-[#6e5d94] text-white shadow-lg hover:bg-[#7f6da5] focus:outline-none transition-transform duration-300"
                :class="{ 'rotate-180': sidebarOpen && !isMobile }">
            
            <!-- Icon untuk mobile -->
            <template x-if="isMobile">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     x-show="!sidebarOpen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </template>
            
            <template x-if="isMobile">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     x-show="sidebarOpen" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </template>
            
            <!-- Icon untuk desktop -->
            <template x-if="!isMobile">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     x-show="!sidebarOpen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </template>
            
            <template x-if="!isMobile">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     x-show="sidebarOpen" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </template>
        </button>

        <h1 class="ml-4 text-2xl font-semibold text-[#5e4a7e]">Sistem Administrasi Klinik</h1>
    </div>

    <!-- Profile dropdown -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="focus:outline-none flex items-center">
            <img src="{{ asset('images/user.png') }}" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-[#6e5d94]" />
        </button>

        <!-- Dropdown menu -->
        <div x-show="open" @click.outside="open = false"
             x-transition
             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 overflow-hidden">
            
            <!-- Profil Saya -->
            <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil Saya
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2v-1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>