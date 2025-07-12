<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Fayrooz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .error-message {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
        }
        .error-alert {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
        }
    </style>
</head>

<body class="h-screen w-screen overflow-hidden relative bg-white">
    <!-- Background Split -->
    <div class="w-full h-screen" style="background-image: conic-gradient(from 135deg, #ffffff 0%, #59aaf0 50%, #f7f7f7 50%, #5e4a7e 100%);">
        <!-- Login Card -->
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg flex overflow-hidden w-[700px] max-w-full">
                <!-- Kotak sebelah kiri -->
                <div class="bg-[#5e4a7e] text-white flex items-center justify-center w-1/3 p-6">
                    <div class="text-center">
                        <div class="bg-white rounded-full p-2 shadow-lg mb-4 mx-auto w-20 h-20 flex items-center justify-center">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Klinik" class="w-16 h-16 object-contain rounded-full">
                        </div>
                        <h2 class="text-2xl font-bold tracking-wide">Fayrooz</h2>
                        <p class="text-sm opacity-80 mt-1">Health and Beauty</p>
                    </div>
                </div>

                <!-- Form sebelah kanan -->
                <div class="w-2/3 p-8">
                    <h2 class="text-xl font-semibold mb-2 text-[#5e4a7e] text-center">Selamat datang kembali</h2>
                    <p class="text-sm text-gray-500 text-center mb-6">Silakan masuk ke akun Anda</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#59aaf0] focus:border-[#59aaf0] sm:text-sm transition duration-150 ease-in-out @error('username') input-error @enderror"
                                placeholder="Masukkan username" required autofocus>
                            @error('username')
                                <div class="error-message flex items-center mt-1 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="password" name="password"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#59aaf0] focus:border-[#59aaf0] sm:text-sm transition duration-150 ease-in-out @error('password') input-error @enderror"
                                placeholder="Masukkan password" required>
                            @error('password')
                                <div class="error-message flex items-center mt-1 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-[#5e4a7e] hover:bg-[#4a3a6a] text-white py-2.5 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5e4a7e] focus:ring-offset-2 transition duration-150 ease-in-out">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>