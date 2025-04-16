<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen w-screen overflow-hidden relative bg-white">

    <!-- Background Split -->
    <div class="w-full h-screen" style="background-image: conic-gradient(from 135deg, #ffffff 0%, #59aaf0 50%, #f7f7f7 50%, #5e4a7e 100%);">

        <!-- Login Card -->
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg flex overflow-hidden w-[700px] max-w-full">
                
                <!-- Kotak sebelah kiri -->
                <div class="bg-[#5e4a7e] text-white flex items-center justify-center w-1/3 p-6">
                    <h2 class="text-3xl font-bold tracking-wide">Login</h2>
                </div>

                <!-- Form sebelah kanan -->
                <div class="w-2/3 p-8">
                    <h2 class="text-xl font-semibold mb-6 text-[#5e4a7e] text-center">Selamat datang kembali</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="username" name="username"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#59aaf0] focus:border-[#59aaf0] sm:text-sm @error('email') border-red-500 @enderror"
                                placeholder="Masukkan username" required autofocus>
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#59aaf0] focus:border-[#59aaf0] sm:text-sm @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password" required>
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-[#59aaf0] text-white py-2 px-4 rounded-md hover:bg-[#1488ed] focus:outline-none focus:ring-2 focus:ring-[#59aaf0] focus:ring-offset-2">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>


</html>
