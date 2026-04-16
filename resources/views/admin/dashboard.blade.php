@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview sistem absensi WFA')

@section('content')
    <div class="space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users Card -->
            <div class="card group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-info">
                                <i class="fas fa-chart-line mr-1"></i>
                                Total
                            </span>
                        </div>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Total Pengguna</h3>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-gray-800 mb-2">{{ $totalUsers }}</p>
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        style="width: {{ ($totalUsers / max($totalUsers + $totalAdmins, 1)) * 100 }}%">
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">User: {{ $totalUsers }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Admin</p>
                            <p class="text-xl font-bold text-purple-600">{{ $totalAdmins }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance Card -->
            <div class="card group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-day text-green-600 text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success">
                                <i class="fas fa-sun mr-1"></i>
                                Hari Ini
                            </span>
                        </div>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Kehadiran Hari Ini</h3>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-gray-800 mb-2">{{ $todayAttendance }}</p>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                <span class="text-sm text-gray-500">{{ now()->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Rata-rata</p>
                            <p class="text-xl font-bold text-green-600">
                                {{ $totalUsers > 0 ? round(($todayAttendance / $totalUsers) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Attendance Card -->
            <div class="card group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-info">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Bulan Ini
                            </span>
                        </div>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Kehadiran Bulan Ini</h3>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-gray-800 mb-2">{{ $monthlyAttendance }}</p>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-trending-up text-purple-500"></i>
                                <span class="text-sm text-gray-500">{{ now()->format('F Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Per Hari</p>
                            <p class="text-xl font-bold text-purple-600">
                                {{ now()->daysInMonth > 0 ? round($monthlyAttendance / now()->daysInMonth, 1) : 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Session Card -->
            <div class="card group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-14 h-14 {{ $activeSession ? 'bg-gradient-to-br from-green-100 to-green-50' : 'bg-gradient-to-br from-yellow-100 to-yellow-50' }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i
                                class="fas {{ $activeSession ? 'fa-check text-green-600' : 'fa-clock text-yellow-600' }} text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="{{ $activeSession ? 'badge badge-success' : 'badge badge-warning' }}">
                                <i class="fas {{ $activeSession ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                                {{ $activeSession ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Sesi Aktif</h3>
                    @if ($activeSession)
                        <div>
                            <p class="text-2xl font-bold text-gray-800 mb-1">{{ $activeSession->nama_sesi }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($activeSession->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($activeSession->jam_selesai)->format('H:i') }}
                                </div>
                                <a href="{{ route('admin.sessions') }}" class="text-primary-color text-sm hover:underline">
                                    <i class="fas fa-cog mr-1"></i>Kelola
                                </a>
                            </div>
                        </div>
                    @else
                        <div>
                            <p class="text-lg font-semibold text-yellow-600 mb-2">Tidak ada sesi aktif</p>
                            <a href="{{ route('admin.sessions') }}"
                                class="inline-flex items-center text-primary-color hover:text-primary-dark transition">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Atur sesi sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts & Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Chart -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Kehadiran 7 Hari Terakhir</h3>
                        <span class="text-sm text-gray-500">Per Hari</span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($attendanceByDay as $day)
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium">{{ $day['day'] }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $day['date'] }}</span>
                                    </div>
                                    <span class="font-semibold">{{ $day['count'] }} kehadiran</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-gradient-to-r from-blue-500 to-indigo-500"
                                        style="width: {{ min(($day['count'] / max(collect($attendanceByDay)->max('count'), 1)) * 100, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Total 7 hari: {{ collect($attendanceByDay)->sum('count') }}
                                kehadiran</span>
                            <a href="{{ route('admin.attendance') }}"
                                class="text-primary-color text-sm font-medium hover:underline">
                                <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Top Performers</h3>
                        <span class="text-sm text-gray-500">Bulan Ini</span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($topUsers as $index => $user)
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        @if ($index < 3)
                                            <div
                                                class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-800">{{ $user->absensi_count }} <span
                                            class="text-sm font-normal text-gray-500">hari</span></p>
                                    <div class="flex items-center gap-1 text-green-600 text-sm">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Tepat waktu</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($topUsers->isEmpty())
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 mb-4">Belum ada data kehadiran bulan ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Aksi Cepat</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.users.create') }}"
                        class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:border-blue-300 transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition">Tambah User</p>
                                <p class="text-sm text-gray-500">Buat akun user baru</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.attendance') }}"
                        class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 hover:border-green-300 transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-clipboard-list text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-green-600 transition">Lihat
                                    Kehadiran</p>
                                <p class="text-sm text-gray-500">Data absensi terkini</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.sessions') }}"
                        class="group p-6 bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl border border-purple-100 hover:border-purple-300 transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-cog text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-purple-600 transition">Kelola Sesi
                                </p>
                                <p class="text-sm text-gray-500">Atur waktu absensi</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Additional Actions Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <a href="{{ route('admin.users.import.form') }}"
                        class="group p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-100 hover:border-amber-300 transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-import text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-amber-600 transition">Import Users
                                </p>
                                <p class="text-sm text-gray-500">Upload file CSV/Excel</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}"
                        class="group p-6 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl border border-rose-100 hover:border-rose-300 transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-bar text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-rose-600 transition">Laporan</p>
                                <p class="text-sm text-gray-500">Analitik & statistik</p>
                            </div>
                        </div>
                    </a>

                    <button onclick="exportData('excel')"
                        class="group p-6 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl border border-teal-100 hover:border-teal-300 transition-all hover:shadow-lg text-left">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-export text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-teal-600 transition">Export Data</p>
                                <p class="text-sm text-gray-500">Download laporan Excel</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="#" class="text-primary-color text-sm font-medium hover:underline">
                        Lihat semua
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">User baru ditambahkan</p>
                            <p class="text-sm text-gray-500">John Doe ditambahkan sebagai user</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-gray-500">10 menit lalu</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">Absensi masuk</p>
                            <p class="text-sm text-gray-500">Jane Smith melakukan absensi masuk</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-gray-500">1 jam lalu</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">Sesi diperbarui</p>
                            <p class="text-sm text-gray-500">Sesi pagi diupdate menjadi aktif</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-gray-500">2 jam lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize chart if needed
            document.addEventListener('DOMContentLoaded', function() {
                // You can add Chart.js initialization here if needed
                console.log('Dashboard charts initialized');
            });
        </script>
    @endpush
@endsection
