<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Tailwind & App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tambahan CSS lokal (jika ada style custom) -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
        <x-flash-message />

        <!-- Header -->
        @include('layouts.header')

        <!-- Page Content -->
        <main class="bg-gray-100 mr-5 flex-1 p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @livewireScripts
    <!-- Optional Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
