<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Absensi WFA</title>
    <!-- Install Tailwind CSS locally for production -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white bg-opacity-90 z-50 flex items-center justify-center hidden">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto">
            </div>
            <p class="mt-4 text-gray-600 font-medium">Memproses...</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Header -->
        <header class="mb-8 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Absensi</h1>
                            <p class="text-gray-600 text-sm">Smart Attendance Management System</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Selamat datang</p>
                                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-day text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Hari ini</p>
                                <p class="font-semibold text-gray-800">{{ now()->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waktu sekarang</p>
                                <p id="waktu-sekarang" class="font-bold text-purple-600">{{ now()->format('H:i:s') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button onclick="showNotification()"
                        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="relative group">
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-sm hover:shadow transition-shadow">
                            <div class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center">
                                <span
                                    class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span
                                class="hidden md:inline text-gray-700 font-medium">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>

                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user mr-2"></i>Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            <hr class="my-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Notification Messages -->
        @if (session('success'))
            <div class="mb-6 fade-in">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 fade-in">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in">
            <!-- Stat Card 1: Sesi Hari Ini -->
            <div class="bg-white rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                        {{ $sesiHariIni ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Sesi Absen Hari Ini</h3>
                @if ($sesiHariIni)
                    <p class="text-2xl font-bold text-gray-800 mb-2">{{ $sesiHariIni->nama_sesi }}</p>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>{{ \Carbon\Carbon::parse($sesiHariIni->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($sesiHariIni->jam_selesai)->format('H:i') }}</span>
                    </div>
                @else
                    <p class="text-lg font-semibold text-yellow-600">Tidak ada sesi aktif</p>
                @endif
            </div>

            <!-- Stat Card 2: Status Hari Ini -->
            <div class="bg-white rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 {{ $absensiHariIni ? 'bg-green-50' : 'bg-gray-100' }} rounded-lg">
                        <i
                            class="fas {{ $absensiHariIni ? 'fa-user-check text-green-600' : 'fa-user-clock text-gray-400' }} text-xl"></i>
                    </div>
                    <span
                        class="text-xs font-medium px-2 py-1 rounded-full {{ $absensiHariIni ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $absensiHariIni ? 'Sudah Absen' : 'Belum Absen' }}
                    </span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Status Absensi</h3>
                @if ($absensiHariIni)
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Masuk:</span>
                            <span
                                class="font-semibold {{ $absensiHariIni->status_masuk == 'tepat_waktu' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $absensiHariIni->jam_masuk ?: '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pulang:</span>
                            <span
                                class="font-semibold {{ $absensiHariIni->jam_pulang ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $absensiHariIni->jam_pulang ?: 'Belum' }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-lg font-semibold text-gray-400">Belum ada absensi</p>
                @endif
            </div>

            <!-- Stat Card 3: Bukti Pekerjaan -->
            <div class="bg-white rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 {{ $absensiHariIni && $absensiHariIni->bukti_diupload ? 'bg-purple-50' : 'bg-gray-100' }} rounded-lg">
                        <i
                            class="fas {{ $absensiHariIni && $absensiHariIni->bukti_diupload ? 'fa-file-upload text-purple-600' : 'fa-file-upload text-gray-400' }} text-xl"></i>
                    </div>
                    @if ($absensiHariIni)
                        <span
                            class="text-xs font-medium px-2 py-1 rounded-full {{ $absensiHariIni->bukti_diupload ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $absensiHariIni->bukti_diupload ? 'Sudah Upload' : 'Belum Upload' }}
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Bukti Pekerjaan</h3>
                @if ($absensiHariIni)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xl font-bold text-gray-800">
                                {{ $absensiHariIni->bukti_diupload ? '✅ Selesai' : '❌ Pending' }}
                            </p>
                            @if ($absensiHariIni->jam_upload_bukti)
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ $absensiHariIni->jam_upload_bukti }}
                                </p>
                            @endif
                        </div>
                        @if (!$absensiHariIni->bukti_diupload)
                            <button onclick="toggleUpload()"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition">
                                Upload
                            </button>
                        @endif
                    </div>
                @else
                    <p class="text-lg font-semibold text-gray-400">Belum ada absensi</p>
                @endif
            </div>

            <!-- Stat Card 4: Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm card-hover p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <i class="fas fa-bolt text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-indigo-100 text-indigo-800">
                        Quick Actions
                    </span>
                </div>

                <h3 class="text-gray-500 text-sm font-medium mb-4">Aksi Cepat</h3>

                <!-- Informasi Waktu -->
                @if ($sesiHariIni)
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center gap-2 text-sm mb-2">
                            <i class="fas fa-calendar-day text-blue-500"></i>
                            <span class="text-gray-700 font-medium">{{ $sesiHariIni->nama_sesi }}</span>
                        </div>

                        <div class="space-y-1 text-xs">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>
                                    Masuk: {{ \Carbon\Carbon::parse($sesiHariIni->jam_mulai)->format('H:i') }} -
                                    @if ($sesiHariIni->toleransi_keterlambatan)
                                        {{ \Carbon\Carbon::parse($sesiHariIni->toleransi_keterlambatan)->format('H:i') }}
                                        <span class="text-blue-500 ml-1">(toleransi)</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($sesiHariIni->jam_selesai)->format('H:i') }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>
                                    Pulang: Setelah {{ \Carbon\Carbon::parse($sesiHariIni->jam_selesai)->format('H:i') }}
                                    @if ($sesiHariIni->maksimal_jam_pulang)
                                        - {{ \Carbon\Carbon::parse($sesiHariIni->maksimal_jam_pulang)->format('H:i') }}
                                    @else
                                        <span class="text-green-500 ml-1">(tidak terbatas)</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-3">
                    @if ($bisaAbsenMasuk)
                        <form id="formMasuk" method="POST" action="{{ route('absen.masuk') }}">
                            @csrf
                            <button type="button" onclick="absenMasuk()"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Absen Masuk</span>
                            </button>
                        </form>
                    @elseif(!$absensiHariIni)
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                @if ($sesiHariIni)
                                    Belum waktu absen masuk
                                @else
                                    Tidak ada sesi absen aktif
                                @endif
                            </p>
                        </div>
                    @endif

                    @if ($bisaAbsenPulang)
                        <form id="formPulang" method="POST" action="{{ route('absen.pulang') }}">
                            @csrf
                            <button type="button" onclick="absenPulang()"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Absen Pulang</span>
                            </button>

                            @if ($pesanAbsenPulang)
                                <p class="text-xs text-gray-500 mt-1 text-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $pesanAbsenPulang }}
                                </p>
                            @endif
                        </form>
                    @elseif($absensiHariIni && !$absensiHariIni->jam_pulang)
                        <div class="text-center p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <p class="text-sm text-amber-700 font-medium mb-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Tidak bisa absen pulang
                            </p>
                            @if ($alasanTidakBisaPulang)
                                <p class="text-xs text-amber-600">
                                    {{ $alasanTidakBisaPulang }}
                                </p>
                            @else
                                <p class="text-xs text-amber-600">
                                    Cek kembali waktu absen pulang
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 fade-in">
            <!-- Left Column: Attendance Actions & Session Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Attendance Progress -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Progress Harian</h2>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Work From Anywhere</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Morning Check-in -->
                        <div class="text-center">
                            <div class="relative inline-block mb-3">
                                <div
                                    class="w-20 h-20 rounded-full {{ $absensiHariIni && $absensiHariIni->jam_masuk ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                    <i
                                        class="fas {{ $absensiHariIni && $absensiHariIni->jam_masuk ? 'fa-check text-green-600' : 'fa-clock text-gray-400' }} text-2xl"></i>
                                </div>
                                @if ($absensiHariIni && $absensiHariIni->jam_masuk)
                                    <div
                                        class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-700 mb-1">Check-in</h4>
                            <p class="text-sm text-gray-500 mb-2">Sesi:
                                {{ $sesiHariIni ? \Carbon\Carbon::parse($sesiHariIni->jam_mulai)->format('H:i') : '-' }}
                            </p>
                            <p
                                class="text-lg font-bold {{ $absensiHariIni && $absensiHariIni->jam_masuk ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $absensiHariIni && $absensiHariIni->jam_masuk ? $absensiHariIni->jam_masuk : 'Belum' }}
                            </p>
                        </div>

                        <!-- Work Proof -->
                        <div class="text-center">
                            <div class="relative inline-block mb-3">
                                <div
                                    class="w-20 h-20 rounded-full {{ $absensiHariIni && $absensiHariIni->bukti_diupload ? 'bg-purple-100' : 'bg-gray-100' }} flex items-center justify-center">
                                    <i
                                        class="fas {{ $absensiHariIni && $absensiHariIni->bukti_diupload ? 'fa-file text-purple-600' : 'fa-file text-gray-400' }} text-2xl"></i>
                                </div>
                                @if ($absensiHariIni && $absensiHariIni->bukti_diupload)
                                    <div
                                        class="absolute -top-1 -right-1 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-700 mb-1">Bukti Kerja</h4>
                            <p class="text-sm text-gray-500 mb-2">Batas: 21:00 WIB</p>
                            <p
                                class="text-lg font-bold {{ $absensiHariIni && $absensiHariIni->bukti_diupload ? 'text-purple-600' : 'text-gray-400' }}">
                                {{ $absensiHariIni && $absensiHariIni->bukti_diupload ? 'Terkirim' : 'Pending' }}
                            </p>
                        </div>

                        <!-- Evening Check-out -->
                        <div class="text-center">
                            <div class="relative inline-block mb-3">
                                <div
                                    class="w-20 h-20 rounded-full {{ $absensiHariIni && $absensiHariIni->jam_pulang ? 'bg-blue-100' : 'bg-gray-100' }} flex items-center justify-center">
                                    <i
                                        class="fas {{ $absensiHariIni && $absensiHariIni->jam_pulang ? 'fa-check text-blue-600' : 'fa-home text-gray-400' }} text-2xl"></i>
                                </div>
                                @if ($absensiHariIni && $absensiHariIni->jam_pulang)
                                    <div
                                        class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-700 mb-1">Check-out</h4>
                            <p class="text-sm text-gray-500 mb-2">Selesai bekerja</p>
                            <p
                                class="text-lg font-bold {{ $absensiHariIni && $absensiHariIni->jam_pulang ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $absensiHariIni && $absensiHariIni->jam_pulang ? $absensiHariIni->jam_pulang : 'Belum' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Session Details & Instructions -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm p-6 border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg mr-4">
                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Sesi & Panduan</h3>
                            <p class="text-sm text-gray-600">Pastikan Anda mengikuti ketentuan absensi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Detail Sesi
                            </h4>
                            @if ($sesiHariIni)
                                <ul class="space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-clock text-blue-400 mr-2"></i>
                                        <span class="text-gray-600">Waktu: <span
                                                class="font-semibold">{{ \Carbon\Carbon::parse($sesiHariIni->jam_mulai)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($sesiHariIni->jam_selesai)->format('H:i') }}</span></span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-sticky-note text-blue-400 mr-2"></i>
                                        <span class="text-gray-600">Status: <span
                                                class="font-semibold text-green-600">Aktif</span></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-comment text-blue-400 mr-2 mt-1"></i>
                                        <span class="text-gray-600">{{ $sesiHariIni->keterangan }}</span>
                                    </li>
                                </ul>
                            @else
                                <p class="text-gray-500 italic">Tidak ada sesi aktif hari ini</p>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>Tips & Panduan
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-400 mr-2 mt-1"></i>
                                    <span class="text-gray-600">Absen dalam rentang waktu sesi yang ditentukan</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-400 mr-2 mt-1"></i>
                                    <span class="text-gray-600">Upload bukti pekerjaan maksimal pukul 21:00 WIB</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-400 mr-2 mt-1"></i>
                                    <span class="text-gray-600">Gunakan koneksi internet yang stabil</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-400 mr-2 mt-1"></i>
                                    <span class="text-gray-600">Izinkan akses lokasi untuk verifikasi</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Activity & Upload Form -->
            <div class="space-y-8">
                <!-- Upload Form Card -->
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h3>
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                            7 Hari Terakhir
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($riwayatAbsensi as $absensi)
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-full {{ $absensi->status_masuk == 'tepat_waktu' ? 'bg-green-100' : 'bg-yellow-100' }} flex items-center justify-center">
                                        <i
                                            class="fas {{ $absensi->status_masuk == 'tepat_waktu' ? 'fa-check text-green-600' : 'fa-clock text-yellow-600' }}"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $absensi->tanggal->format('d M Y') }}
                                    </p>
                                    <div class="text-sm text-gray-500">
                                        <span class="mr-3">
                                            <i class="fas fa-sign-in-alt mr-1"></i>{{ $absensi->jam_masuk ?: '-' }}
                                        </span>
                                        {{-- <span>
                                            <i class="fas fa-sign-out-alt mr-1"></i>{{ $absensi->jam_pulang ?: '-' }}
                                        </span> --}}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{-- <span class="mr-3">
                                            <i class="fas fa-sign-in-alt mr-1"></i>{{ $absensi->jam_masuk ?: '-' }}
                                        </span> --}}
                                        <span>
                                            <i class="fas fa-sign-out-alt mr-1"></i>{{ $absensi->jam_pulang ?: '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="status-badge {{ $absensi->status_masuk == 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $absensi->status_masuk == 'tepat_waktu' ? 'Tepat' : 'Terlambat' }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">
                                        @if ($absensi->bukti_diupload)
                                            <i class="fas fa-check text-green-500"></i>
                                        @else
                                            <i class="fas fa-times text-red-400"></i>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        @if ($riwayatAbsensi->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Belum ada riwayat absensi</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($absensiHariIni && !$absensiHariIni->bukti_diupload)
                    <div id="uploadForm" class="hidden bg-white rounded-xl shadow-sm p-6 border border-purple-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">📎 Upload Bukti Pekerjaan</h3>
                            <button onclick="toggleUpload()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">
                                <span class="font-semibold">⏰ Batas waktu:</span> Maksimal pukul 21:00 WIB
                            </p>
                        </div>

                        <form action="{{ route('upload.bukti') }}" method="POST" enctype="multipart/form-data"
                            id="formUploadBukti">
                            @csrf

                            <div class="space-y-4">
                                <!-- File Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih File Bukti
                                    </label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mx-auto"></i>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="bukti_pekerjaan"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                                    <span>Upload file</span>
                                                    <input id="bukti_pekerjaan" name="bukti_pekerjaan" type="file"
                                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="sr-only"
                                                        onchange="previewFileName(this)" required>
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500" id="filePreview">
                                                JPG, PNG, PDF, DOC up to 5MB
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan Pekerjaan
                                    </label>
                                    <textarea name="catatan" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition"
                                        placeholder="Deskripsi pekerjaan yang telah dilakukan hari ini..."></textarea>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex gap-3 pt-4">
                                    <button type="submit"
                                        class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-upload"></i>
                                        <span>Upload Sekarang</span>
                                    </button>
                                </div>

                                <!-- Info -->
                                <div class="mt-4 p-3 bg-yellow-50 rounded-lg text-sm text-yellow-700">
                                    <p class="font-semibold mb-1">📝 Perhatian:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Pastikan file jelas terbaca</li>
                                        <li>Upload hanya sekali per hari</li>
                                        <li>File akan diverifikasi oleh admin</li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif


            </div>
        </div>

        <!-- Attendance History Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 fade-in">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800">Riwayat Absensi</h2>
                    <div class="flex items-center space-x-2">
                        <button
                            class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <button
                            class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bukti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($riwayatAbsensi as $absensi)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $absensi->tanggal->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $absensi->tanggal->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-sign-in-alt text-gray-400 mr-2"></i>
                                        <span
                                            class="text-sm font-medium {{ $absensi->jam_masuk ? 'text-gray-900' : 'text-gray-400' }}">
                                            {{ $absensi->jam_masuk ?: 'Tidak ada' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-sign-out-alt text-gray-400 mr-2"></i>
                                        <span
                                            class="text-sm font-medium {{ $absensi->jam_pulang ? 'text-gray-900' : 'text-gray-400' }}">
                                            {{ $absensi->jam_pulang ?: 'Tidak ada' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if ($absensi->jam_masuk && $absensi->jam_pulang)
                                            @php
                                                $start = \Carbon\Carbon::parse($absensi->jam_masuk);
                                                $end = \Carbon\Carbon::parse($absensi->jam_pulang);
                                                $duration = $start->diff($end);
                                            @endphp
                                            {{ $duration->h }}h {{ $duration->i }}m
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 text-xs rounded-full font-medium 
                                    {{ $absensi->status_masuk == 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $absensi->status_masuk == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($absensi->bukti_diupload)
                                        <div class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span class="text-sm">Uploaded</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-gray-400">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            <span class="text-sm">Belum</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if ($absensi->bukti_pekerjaan)
                                        <a href="{{ asset('storage/' . $absensi->bukti_pekerjaan) }}" target="_blank"
                                            class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($riwayatAbsensi->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada riwayat absensi</h3>
                    <p class="text-gray-500">Riwayat absensi akan muncul setelah Anda melakukan absensi</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <span class="text-lg font-bold text-gray-800">Polsub Attendance</span>
                    </div>
                    <p class="text-sm text-gray-600">Smart Attendance Management System © {{ date('Y') }}</p>
                </div>

                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">
                        <i class="fas fa-question-circle mr-1"></i>Bantuan
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">
                        <i class="fas fa-shield-alt mr-1"></i>Privacy
                    </a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">
                        <i class="fas fa-file-alt mr-1"></i>Terms
                    </a>
                    <div class="text-sm text-gray-500">
                        v1.0.0
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Notification Toast -->
    <div id="notificationToast" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 border-blue-500">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-bell text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Notifikasi Sistem</p>
                    <p class="mt-1 text-sm text-gray-500" id="notificationMessage">Pesan notifikasi</p>
                </div>
                <button onclick="hideNotification()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Global variable untuk menyimpan reference
        let waktuElement = null;
        let waktuInterval = null;

        // Safe element selector dengan null check
        function safeGetElement(id) {
            const element = document.getElementById(id);
            if (!element) {
                console.warn(`Element dengan ID "${id}" tidak ditemukan`);
            }
            return element;
        }

        function showNotification(message = 'Sistem siap digunakan!') {
            document.getElementById('notificationMessage').textContent = message;
            const toast = document.getElementById('notificationToast');
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }

        function hideNotification() {
            document.getElementById('notificationToast').classList.add('hidden');
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
        // Update waktu dengan null check
        function updateWaktu() {
            // Gunakan global waktuElement
            if (!waktuElement) {
                waktuElement = document.getElementById('waktu-sekarang');
            }

            // Jika masih null, cari lagi
            if (!waktuElement) {
                console.warn('Elemen "waktu-sekarang" tidak ditemukan di updateWaktu()');
                return;
            }

            const now = new Date();
            const jam = now.getHours().toString().padStart(2, '0');
            const menit = now.getMinutes().toString().padStart(2, '0');
            const detik = now.getSeconds().toString().padStart(2, '0');
            waktuElement.textContent = `${jam}:${menit}:${detik}`;
        }

        // Function untuk inisialisasi waktu
        function initWaktu() {
            // Hentikan interval sebelumnya jika ada
            if (waktuInterval) {
                clearInterval(waktuInterval);
                waktuInterval = null;
            }

            // Cari elemen
            waktuElement = document.getElementById('waktu-sekarang');

            if (waktuElement) {
                // Update waktu pertama kali
                updateWaktu();

                // Set interval untuk update berikutnya
                waktuInterval = setInterval(updateWaktu, 1000);

                return true;
            } else {
                console.warn('Elemen "waktu-sekarang" tidak ditemukan');
                return false;
            }
        }

        function showCustomConfirm(message, onConfirm, onCancel = null) {
            // Create modal HTML
            const modalHTML = `
        <div id="customConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm mx-4">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-question text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi</h3>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex gap-3">
                        <button id="confirmNo" 
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Tidak
                        </button>
                        <button id="confirmYes" 
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Ya
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Get modal element
            const modal = document.getElementById('customConfirmModal');
            const btnYes = document.getElementById('confirmYes');
            const btnNo = document.getElementById('confirmNo');

            // Button event listeners
            btnYes.addEventListener('click', function() {
                modal.remove();
                if (onConfirm) onConfirm();
            });

            btnNo.addEventListener('click', function() {
                modal.remove();
                if (onCancel) onCancel();
            });

            // Close on background click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                    if (onCancel) onCancel();
                }
            });
        }

        // Initialize setelah DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi waktu
            const waktuInitialized = initWaktu();

            if (waktuInitialized) {
                // Gunakan MutationObserver untuk monitor perubahan
                const observer = new MutationObserver(function(mutations) {
                    let waktuElementRemoved = false;
                    let waktuElementAdded = false;

                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            // Cek jika elemen waktu-sekarang dihapus
                            mutation.removedNodes.forEach(function(node) {
                                if (node.id === 'waktu-sekarang' ||
                                    (node.querySelector && node.querySelector(
                                        '#waktu-sekarang'))) {
                                    console.warn('Elemen waktu-sekarang dihapus dari DOM');
                                    waktuElementRemoved = true;
                                }
                            });

                            // Cek jika elemen waktu-sekarang ditambahkan kembali
                            mutation.addedNodes.forEach(function(node) {
                                if (node.id === 'waktu-sekarang' ||
                                    (node.querySelector && node.querySelector(
                                        '#waktu-sekarang'))) {
                                    waktuElementAdded = true;
                                }
                            });
                        }
                    });

                    // Jika elemen dihapus, reset
                    if (waktuElementRemoved) {
                        waktuElement = null;
                        if (waktuInterval) {
                            clearInterval(waktuInterval);
                            waktuInterval = null;
                        }
                    }

                    // Jika elemen ditambahkan dan sebelumnya tidak ada, inisialisasi ulang
                    if (waktuElementAdded && !waktuElement) {
                        setTimeout(initWaktu, 100); // Tunggu sebentar agar DOM selesai update
                    }
                });

                // Observe perubahan pada parent element
                if (waktuElement && waktuElement.parentNode) {
                    observer.observe(waktuElement.parentNode, {
                        childList: true,
                        subtree: true
                    });
                }
            }
        });

        // Geolocation functions dengan error handling
        // Geolocation functions dengan error handling yang lebih baik
        function getLocation(callback) {
            if (!navigator.geolocation) {
                showNotification('Browser tidak mendukung geolocation.');
                callback(null);
                return;
            }

            // Tampilkan loading sebelum minta izin
            showLoading();

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    hideLoading();
                    callback({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    });
                },
                function(error) {
                    hideLoading();

                    let errorMessage = 'Gagal mendapatkan lokasi. ';
                    let showRetryOption = true;

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Akses lokasi ditolak. ';
                            showRetryOption = false;
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Informasi lokasi tidak tersedia. ';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Permintaan lokasi timeout. ';
                            break;
                        default:
                            errorMessage = 'Error tidak diketahui. ';
                            break;
                    }

                    errorMessage += 'Lanjutkan tanpa lokasi?';

                    if (showRetryOption) {
                        // Tanya apakah mau retry atau lanjut tanpa lokasi
                        if (confirm(errorMessage +
                                '\n\nKlik OK untuk lanjut tanpa lokasi.\nKlik Cancel untuk coba lagi.')) {
                            callback(null); // Lanjut tanpa lokasi
                        } else {
                            // User ingin coba lagi
                            setTimeout(() => getLocation(callback), 100);
                        }
                    } else {
                        // Untuk permission denied, langsung tanya tanpa retry option
                        if (confirm(errorMessage)) {
                            callback(null); // Lanjut tanpa lokasi
                        }
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000, // 10 detik timeout
                    maximumAge: 0
                }
            );
        }

        function submitAbsenMasuk(location) {
            const form = safeGetElement('formMasuk');
            if (!form) {
                showNotification('Form absen masuk tidak ditemukan');
                return;
            }

            // Clear previous location inputs
            const oldLat = form.querySelector('input[name="latitude"]');
            const oldLng = form.querySelector('input[name="longitude"]');
            if (oldLat) oldLat.remove();
            if (oldLng) oldLng.remove();

            // Add location inputs if available
            if (location) {
                const latInput = document.createElement('input');
                latInput.type = 'hidden';
                latInput.name = 'latitude';
                latInput.value = location.latitude;

                const lngInput = document.createElement('input');
                lngInput.type = 'hidden';
                lngInput.name = 'longitude';
                lngInput.value = location.longitude;

                form.appendChild(latInput);
                form.appendChild(lngInput);
            }

            // Submit form
            form.submit();
        }

        function submitAbsenPulang(location) {
            const form = safeGetElement('formPulang');
            if (!form) {
                showNotification('Form absen pulang tidak ditemukan');
                return;
            }

            // Clear previous location inputs
            const oldLat = form.querySelector('input[name="latitude"]');
            const oldLng = form.querySelector('input[name="longitude"]');
            if (oldLat) oldLat.remove();
            if (oldLng) oldLng.remove();

            // Add location inputs if available
            if (location) {
                const latInput = document.createElement('input');
                latInput.type = 'hidden';
                latInput.name = 'latitude';
                latInput.value = location.latitude;

                const lngInput = document.createElement('input');
                lngInput.type = 'hidden';
                lngInput.name = 'longitude';
                lngInput.value = location.longitude;

                form.appendChild(latInput);
                form.appendChild(lngInput);
            }

            // Submit form
            form.submit();
        }

        // Attendance functions
        window.absenMasuk = function() {
            // Langsung minta geolocation dulu
            getLocation(function(location) {
                // Jika location null (user menolak GPS)
                if (location === null) {
                    // Tanya apakah mau lanjut tanpa lokasi
                    showCustomConfirm(
                        'Lokasi tidak tersedia. Lanjutkan absen tanpa lokasi?',
                        function() {
                            // User memilih lanjut tanpa lokasi
                            showCustomConfirm(
                                'Apakah Anda yakin ingin absen masuk?',
                                function() {
                                    submitAbsenMasuk(null); // Submit tanpa lokasi
                                }
                            );
                        },
                        function() {
                            showNotification('Absen masuk dibatalkan');
                        }
                    );
                    return;
                }

                // Jika dapat lokasi, langsung confirm absen
                showCustomConfirm(
                    `Lokasi berhasil didapatkan. Apakah Anda yakin ingin absen masuk?`,
                    function() {
                        submitAbsenMasuk(location);
                    },
                    function() {
                        showNotification('Absen masuk dibatalkan');
                    }
                );
            });
        };


        window.absenPulang = function() {
            // Langsung minta geolocation dulu
            getLocation(function(location) {
                // Jika location null (user menolak GPS)
                if (location === null) {
                    // Tanya apakah mau lanjut tanpa lokasi
                    showCustomConfirm(
                        'Lokasi tidak tersedia. Lanjutkan absen tanpa lokasi?',
                        function() {
                            // User memilih lanjut tanpa lokasi
                            showCustomConfirm(
                                'Apakah Anda yakin ingin absen pulang?',
                                function() {
                                    submitAbsenPulang(null); // Submit tanpa lokasi
                                }
                            );
                        },
                        function() {
                            showNotification('Absen pulang dibatalkan');
                        }
                    );
                    return;
                }

                // Jika dapat lokasi, langsung confirm absen
                showCustomConfirm(
                    `Lokasi berhasil didapatkan. Apakah Anda yakin ingin absen pulang?`,
                    function() {
                        submitAbsenPulang(location);
                    },
                    function() {
                        showNotification('Absen pulang dibatalkan');
                    }
                );
            });
        };

        // Upload form toggle
        window.toggleUpload = function() {
            const form = safeGetElement('uploadForm');
            if (!form) {
                console.warn('Upload form tidak ditemukan');
                return;
            }

            form.classList.toggle('hidden');

            // Smooth scroll to upload form
            if (!form.classList.contains('hidden')) {
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        };

        // File preview
        window.previewFileName = function(input) {
            const filePreview = safeGetElement('filePreview');
            if (!filePreview) return;

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                filePreview.innerHTML = `
                <span class="font-medium">${file.name}</span><br>
                <span class="text-gray-600">${fileSize} MB • ${file.type}</span>
            `;

                if (file.size > 5 * 1024 * 1024) {
                    filePreview.innerHTML += `<br><span class="text-red-600">File terlalu besar (max 5MB)</span>`;
                    input.value = '';
                }
            } else {
                filePreview.textContent = 'JPG, PNG, PDF, DOC up to 5MB';
            }
        };

        // Validate upload form
        const uploadForm = safeGetElement('formUploadBukti');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                const fileInput = safeGetElement('bukti_pekerjaan');
                if (!fileInput) {
                    e.preventDefault();
                    alert('File input tidak ditemukan');
                    return false;
                }

                const now = new Date();
                const currentHour = now.getHours();

                // Validate upload time (before 21:00)
                if (currentHour >= 21) {
                    e.preventDefault();
                    alert('⚠️ Upload bukti hanya sampai pukul 21:00 WIB');
                    return false;
                }

                // Validate file
                if (!fileInput.files || fileInput.files.length === 0) {
                    e.preventDefault();
                    alert('⚠️ Silakan pilih file bukti pekerjaan');
                    return false;
                }

                // Show loading
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
                    submitBtn.disabled = true;
                }
            });
        }

        // Re-initialize waktu jika page menjadi visible kembali
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                console.log('Page became visible, reinitializing waktu');
                initWaktu();
            }
        });
    </script>
</body>

</html>
