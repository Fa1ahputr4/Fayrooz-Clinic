<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Animasi super halus */
        .sidebar-container {
            position: relative;
            overflow: hidden;
            width: 16rem; /* 64 */
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem;
            transform: translateX(0);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
        
        .sidebar-collapsed {
            transform: translateX(-13rem); /* Sembunyikan 13rem dari 16rem */
        }
        
        .main-content {
            transition: margin-left 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: margin-left;
        }
        
        .menu-item {
            position: relative;
            overflow: hidden;
        }
        
        .menu-text {
            transition: opacity 0.3s ease 0.1s, transform 0.3s ease 0.1s;
            transform: translateX(0);
            opacity: 1;
        }
        
        .menu-text-hidden {
            opacity: 0;
            transform: translateX(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        
        .toggle-icon {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .toggle-icon-rotated {
            transform: rotateY(180deg);
        }
        
        /* Efek hover halus */
        .nav-link {
            transition: background-color 0.2s ease;
        }
        
        /* Overlay untuk mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay-visible {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay lg:hidden" 
         :class="mobileMenuOpen && 'sidebar-overlay-visible'"
         @click="mobileMenuOpen = false"></div>

    <div class="flex flex-1">

        <!-- Sidebar Container -->
        <div class="sidebar-container flex-shrink-0">
            <!-- Sidebar -->
            <aside class="sidebar bg-[#5e4a7e] text-white h-screen z-30"
                   :class="!sidebarOpen && 'sidebar-collapsed'"
                   x-show="!mobileMenuOpen || window.innerWidth >= 1024">
                <div class="p-6 text-2xl font-bold border-b border-gray-700 flex items-center justify-between">
                    <span class="menu-text" :class="!sidebarOpen && 'menu-text-hidden'">MyApp</span>
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="text-white focus:outline-none toggle-icon"
                            :class="!sidebarOpen && 'toggle-icon-rotated'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <nav class="mt-4">
                    <a href="/dashboard" class="nav-link flex items-center py-2.5 px-4 hover:bg-[#6e5d94] menu-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Dashboard</span>
                    </a>
                    <a href="/profile" class="nav-link flex items-center py-2.5 px-4 hover:bg-[#6e5d94] menu-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Profile</span>
                    </a>
                    <a href="/settings" class="nav-link flex items-center py-2.5 px-4 hover:bg-[#6e5d94] menu-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full nav-link flex items-center py-2.5 px-4 hover:bg-red-600 bg-red-500 mt-10 menu-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="menu-text ml-3" :class="!sidebarOpen && 'menu-text-hidden'">Logout</span>
                        </button>
                    </form>
                </nav>
                
                <!-- Toggle button fixed at bottom -->
                <div class="absolute bottom-4 left-0 right-0 flex justify-center">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 rounded-full bg-[#6e5d94] text-white shadow-lg hover:bg-[#7f6da5] focus:outline-none toggle-icon"
                            :class="!sidebarOpen && 'toggle-icon-rotated'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 flex flex-col" :class="{'lg:ml-64': sidebarOpen, 'lg:ml-16': !sidebarOpen}">
            <!-- Header -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <button @click="mobileMenuOpen = true" class="mr-4 text-gray-600 focus:outline-none lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-[#5e4a7e]">Dashboard</h1>
                </div>
                <div class="text-gray-600">Selamat datang, {{ Auth::user()->username }}</div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-lg font-semibold mb-4">Konten Utama</h2>
                    <p>Ini adalah halaman dashboard utama kamu.</p>
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="mt-4 px-4 py-2 bg-[#5e4a7e] text-white rounded transition-all duration-300 hover:bg-[#6e5d94]">
                        <span x-text="sidebarOpen ? 'Sembunyikan Sidebar' : 'Tampilkan Sidebar'"></span>
                    </button>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow px-6 py-3 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} MyApp. All rights reserved.
            </footer>
        </div>

    </div>

    <script>
        // Simpan state sidebar di localStorage
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: localStorage.getItem('sidebarOpen') === 'true',
                
                toggle() {
                    this.open = !this.open
                    localStorage.setItem('sidebarOpen', this.open)
                }
            })
        })
    </script>

</body>
</html>