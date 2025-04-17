<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <!-- Tambah ini di <head> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="bg-[#5e4a7e] min-h-screen flex flex-col" x-data="{ sidebarOpen: window.innerWidth >= 1024, mobileMenuOpen: false }"
    @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay lg:hidden" :class="mobileMenuOpen && 'sidebar-overlay-visible'"
        @click="mobileMenuOpen = false"></div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="flex-grow flex flex-col min-h-screen main-content"
        :class="{ 'main-content-collapsed': !sidebarOpen && window.innerWidth >= 1024 }">
        <!-- Header -->
        @include('layouts.header')

        <!-- Content -->
        <main class="bg-gray-100 mr-5 flex-1 p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
