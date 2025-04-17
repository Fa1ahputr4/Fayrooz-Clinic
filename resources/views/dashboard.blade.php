@extends('layouts.app')
@section('content')
<!-- Content -->
<!-- Breadcrumb -->
<nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    <ol class="list-none p-0 inline-flex">
        <li class="flex items-center">
            <a href="/dashboard" class="text-[#5e4a7e] hover:underline">Dashboard</a>
            <svg class="h-4 w-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </li>
    </ol>
</nav>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4 text-[#5e4a7e]">Selamat Datang, {{ Auth::user()->name ?? Auth::user()->username }}!</h2>
    <p class="text-gray-700">Ini adalah halaman dashboard utama kamu.</p>
</div>
@endsection
