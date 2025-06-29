<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">

    <!-- Tailwind & App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/virtualselect.css') }}">

    <!-- Tambahan CSS lokal (jika ada style custom) -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- jQuery Editable Select -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @livewireStyles

</head>

<body class="bg-[#5e4a7e] min-h-screen flex flex-col" x-data="{
    sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? (window.innerWidth >= 1024)),
    mobileMenuOpen: false
}" x-init="$watch('sidebarOpen', value => localStorage.setItem('sidebarOpen', JSON.stringify(value)))"
    @resize.window="sidebarOpen = window.innerWidth >= 1024" x-cloak>

    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay lg:hidden" :class="mobileMenuOpen && 'sidebar-overlay-visible'"
        @click="mobileMenuOpen = false"></div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-grow flex flex-col min-h-screen main-content"
        :class="{ 'main-content-collapsed': !$store.sidebar.open && window.innerWidth >= 1024 }">
        {{-- <x-flash-message /> --}}
        <x-flash-message />

        <!-- Header -->
        @include('layouts.header')

        <!-- Page Content -->
        <main class="bg-gray-100 mr-5 flex-1 p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.footer')

        @if (session('success') || session('error'))
            <x-session-flash-message />
        @endif

    </div>

    <!-- Optional Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/js/virtualselect.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery (Harus dimuat sebelum plugin lainnya) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    @livewireScripts

    @stack('scripts')

</body>

</html>
