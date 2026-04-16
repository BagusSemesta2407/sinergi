<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi WFA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-2xl overflow-hidden card-shadow">
            <div class="md:flex">
                <!-- Left Side - Login Form -->
                <div class="md:w-1/2 p-8 md:p-12">
                    <div class="text-center mb-8">
                        <a href="/" class="inline-flex items-center">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-800">Polsub<span
                                    class="text-purple-600">Attendance</span></span>
                        </a>
                        <p class="text-gray-600 mt-2">Smart Attendance Management System</p>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk ke Akun Anda</h2>
                    <p class="text-gray-600 mb-8">Silakan login untuk mengakses sistem absensi</p>

                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    @foreach ($errors->all() as $error)
                                        <p class="text-sm text-red-700">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-purple-500"></i>Alamat Email
                            </label>
                            <div class="relative">
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    autofocus placeholder="nama@polsub.co.id"
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg input-focus focus:border-purple-500 focus:outline-none transition duration-200">
                                <div class="absolute left-0 top-0 h-full flex items-center px-4 text-gray-400">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-purple-500"></i>Kata Sandi
                            </label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required placeholder="••••••••"
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg input-focus focus:border-purple-500 focus:outline-none transition duration-200">
                                <div class="absolute left-0 top-0 h-full flex items-center px-4 text-gray-400">
                                    <i class="fas fa-key"></i>
                                </div>
                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-0 top-0 h-full flex items-center px-4 text-gray-400 hover:text-purple-500">
                                    <i id="eye-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                            <div class="text-sm">
                                <a href="#" class="font-medium text-purple-600 hover:text-purple-500">
                                    Lupa kata sandi?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full bg-gradient-custom text-white py-3 px-4 rounded-lg font-semibold hover:opacity-90 transition duration-200 flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Masuk ke Sistem
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Pastikan Anda menggunakan email kampus yang terdaftar
                            </p>
                            <p class="text-gray-600 text-sm mt-2">
                                <i class="fas fa-clock mr-2 text-yellow-500"></i>
                                Absensi masuk tersedia pukul 05:30 - 07:30 WIB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Information -->
                <div class="md:w-1/2 bg-gradient-custom text-white p-8 md:p-12 flex flex-col justify-center">
                    <div class="max-w-md mx-auto">
                        <h3 class="text-2xl font-bold mb-6">Polsub Attendance</h3>

                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-white bg-opacity-20 p-3 rounded-lg">
                                    <i class="fas fa-laptop-house text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Work From Anywhere</h4>
                                    <p class="text-white text-opacity-90">Absensi fleksibel dari mana saja, kapan saja
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-white bg-opacity-20 p-3 rounded-lg">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Sesi Terjadwal</h4>
                                    <p class="text-white text-opacity-90">Absensi masuk sesuai sesi yang telah
                                        ditentukan</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-white bg-opacity-20 p-3 rounded-lg">
                                    <i class="fas fa-file-upload text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Upload Bukti</h4>
                                    <p class="text-white text-opacity-90">Upload bukti pekerjaan maksimal pukul 21:00
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-white bg-opacity-20 p-3 rounded-lg">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-lg">Rekap Otomatis</h4>
                                    <p class="text-white text-opacity-90">Riwayat absensi tercatat secara otomatis</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 p-4 bg-white bg-opacity-10 rounded-lg">
                            <h4 class="font-semibold mb-2">Informasi Penting:</h4>
                            <ul class="text-sm space-y-1 text-white text-opacity-90">
                                <li><i class="fas fa-check-circle mr-2 text-green-300"></i>Gunakan koneksi internet
                                    stabil</li>
                                <li><i class="fas fa-check-circle mr-2 text-green-300"></i>Izinkan akses lokasi jika
                                    diperlukan</li>
                                <li><i class="fas fa-check-circle mr-2 text-green-300"></i>Siapkan bukti pekerjaan
                                    sebelum upload</li>
                                <li><i class="fas fa-check-circle mr-2 text-green-300"></i>Hubungi admin jika mengalami
                                    kendala</li>
                            </ul>
                        </div>

                        <div class="mt-8 text-center text-white text-opacity-80">
                            <p class="text-sm">
                                <i class="fas fa-headset mr-2"></i>
                                Butuh bantuan?
                                <a href="mailto:upatik@polsub.co.id" class="underline hover:text-white">Hubungi
                                    IT Support</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                &copy; {{ date('Y') }} Sistem Absensi WFA.
                <span class="text-purple-600 font-medium">v1.0</span>
            </p>
            <p class="text-gray-500 text-xs mt-1">
                Hak Cipta Dilindungi. Penggunaan sistem ini tunduk pada ketentuan kampus.
            </p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bg-red-50');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Add focus effects
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-purple-300', 'ring-opacity-50');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-purple-300', 'ring-opacity-50');
            });
        });
    </script>
</body>

</html>
