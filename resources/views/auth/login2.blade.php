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
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }

        .brand-side {
            background: linear-gradient(135deg, #5e4a7e 0%, #8E2DE2 100%);
        }

        .btn-login {
            background: linear-gradient(to right, #5e4a7e, #8E2DE2);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(94, 74, 126, 0.3);
        }

        .input-focus:focus {
            border-color: #5e4a7e;
            box-shadow: 0 0 0 3px rgba(94, 74, 126, 0.2);
        }
    </style>
</head>

<body>
    <div class=" w-full h-screen"
        style="background-image: conic-gradient(from 135deg, #ffffff 0%, #59aaf0 50%, #f7f7f7 50%, #5e4a7e 100%);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl flex overflow-hidden  w-full max-w-[700px]">
                <!-- Bagian Kiri - Logo dan Branding -->
                <div class="brand-side text-white flex flex-col items-center justify-center w-1/3 p-6">
                    <div class="bg-white rounded-full p-4 shadow-lg mb-6">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo Klinik Fayrooz"
                            class=" w-full h-full object-cover rounded-full">
                    </div>
                    <h1 class="text-2xl font-bold mb-1">Fayrooz</h1>
                    <p class="text-sm opacity-90">Health and Beauty</p>
                    <div class="mt-8 w-12 h-1 bg-white opacity-50"></div>
                    <p class="text-xs mt-2 opacity-80">Sistem Administrasi Klinik</p>
                </div>

                <!-- Bagian Kanan - Form Login -->
                <div class="w-2/3 p-8 flex flex-col justify-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Masuk ke Sistem</h2>
                    <p class="text-gray-600 text-sm mb-6">Silakan masukkan username dan password</p>

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" id="username" name="username" required autofocus
                                class=" w-full px-4 py-2.5 rounded-lg border border-gray-300 input-focus transition duration-200
                            @error('username') border-red-500 @enderror"
                                placeholder="Masukkan username">
                            @error('username')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="password" name="password" required
                                class=" w-full px-4 py-2.5 rounded-lg border border-gray-300 input-focus transition duration-200
                            @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('password')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-[#5e4a7e] focus:ring-[#5e4a7e] border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit"
                            class=" w-full btn-login text-white font-medium py-2.5 px-4 rounded-lg mt-2
                        focus:outline-none focus:ring-2 focus:ring-[#5e4a7e] focus:ring-offset-2">
                            Masuk
                        </button>
                    </form>

                    <div class="mt-6 text-center text-xs text-gray-500">
                        © {{ date('Y') }} Klinik Fayrooz. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
