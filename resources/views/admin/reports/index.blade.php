@extends('layouts.admin')

@section('title', 'Laporan & Analitik')
@section('page-title', 'Laporan & Analitik')
@section('page-description', 'Statistik dan analisis data kehadiran')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan & Analitik</h1>
                <p class="text-gray-600">Analisis data dan statistik kehadiran</p>
            </div>

            <div class="flex gap-3">
                <button onclick="showExportModal()" class="btn btn-outline group">
                    <i class="fas fa-file-export text-green-500 group-hover:text-green-600"></i>
                    <span>Export Laporan</span>
                </button>

                <button onclick="printPage()" class="btn btn-primary group">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Kehadiran</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ $monthlyStats->sum('total') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-green-600 text-sm">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>6 bulan terakhir</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Rata-rata Kehadiran</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ $monthlyStats->count() > 0 ? round($monthlyStats->avg('total'), 1) : 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-green-600 text-sm">
                        <i class="fas fa-chart-bar mr-1"></i>
                        <span>Per bulan</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Tepat Waktu</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ $monthlyStats->sum('ontime') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-purple-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-green-600 text-sm">
                        <i class="fas fa-clock mr-1"></i>
                        <span>6 bulan terakhir</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Persentase Tepat Waktu</p>
                            <p class="text-3xl font-bold text-gray-800">
                                @php
                                    $total = $monthlyStats->sum('total');
                                    $ontime = $monthlyStats->sum('ontime');
                                    $percentage = $total > 0 ? round(($ontime / $total) * 100, 1) : 0;
                                @endphp
                                {{ $percentage }}%
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-percentage text-amber-600"></i>
                        </div>
                    </div>
                    <div
                        class="flex items-center {{ $percentage >= 90 ? 'text-green-600' : ($percentage >= 80 ? 'text-amber-600' : 'text-red-600') }} text-sm">
                        @if ($percentage >= 90)
                            <i class="fas fa-thumbs-up mr-1"></i>
                            <span>Excellent</span>
                        @elseif($percentage >= 80)
                            <i class="fas fa-check mr-1"></i>
                            <span>Good</span>
                        @else
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <span>Needs Improvement</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics Chart -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Statistik 6 Bulan Terakhir</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Tepat Waktu</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Terlambat</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="pb-3 text-left text-sm font-semibold text-gray-700">Bulan</th>
                                <th class="pb-3 text-left text-sm font-semibold text-gray-700">Total</th>
                                <th class="pb-3 text-left text-sm font-semibold text-gray-700">Tepat Waktu</th>
                                <th class="pb-3 text-left text-sm font-semibold text-gray-700">Terlambat</th>
                                <th class="pb-3 text-left text-sm font-semibold text-gray-700">Persentase</th>
                                <th class="pb-3 text-left text-sm font-semibold text-gray-700">Trend</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($monthlyStats as $index => $stat)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4">
                                        <p class="font-medium text-gray-800">{{ $stat['month'] }}</p>
                                    </td>
                                    <td class="py-4">
                                        <p class="text-gray-700">{{ $stat['total'] }}</p>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-green-600 font-medium">{{ $stat['ontime'] }}</span>
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full"
                                                    style="width: {{ $stat['total'] > 0 ? ($stat['ontime'] / $stat['total']) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-600 font-medium">{{ $stat['late'] }}</span>
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-amber-500 h-2 rounded-full"
                                                    style="width: {{ $stat['total'] > 0 ? ($stat['late'] / $stat['total']) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-semibold {{ $stat['percentage'] >= 90 ? 'text-green-600' : ($stat['percentage'] >= 80 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ $stat['percentage'] }}%
                                            </span>
                                            @if ($index > 0)
                                                @php
                                                    $prev = $monthlyStats[$index - 1]['percentage'] ?? 0;
                                                    $diff = $stat['percentage'] - $prev;
                                                @endphp
                                                <span class="text-xs {{ $diff >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                                    <i class="fas fa-arrow-{{ $diff >= 0 ? 'up' : 'down' }} mr-1"></i>
                                                    {{ abs($diff) }}%
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        @if ($stat['percentage'] >= 90)
                                            <span class="badge badge-success">
                                                <i class="fas fa-thumbs-up mr-1"></i>
                                                Excellent
                                            </span>
                                        @elseif($stat['percentage'] >= 80)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-check mr-1"></i>
                                                Good
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Needs Improvement
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Total 6 Bulan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $monthlyStats->sum('total') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Rata-rata Bulanan</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $monthlyStats->count() > 0 ? round($monthlyStats->avg('total'), 1) : 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Trend</p>
                            @php
                                $firstMonth = $monthlyStats->first();
                                $lastMonth = $monthlyStats->last();
                                $trend = $lastMonth && $firstMonth ? $lastMonth['total'] - $firstMonth['total'] : 0;
                            @endphp
                            <p class="text-2xl font-bold {{ $trend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trend >= 0 ? '+' : '' }}{{ $trend }}
                                <span class="text-sm font-normal text-gray-500">dari bulan pertama</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- User Performance -->
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Top Performers</h3>
                        <span class="text-sm text-gray-500">Bulan {{ now()->format('F Y') }}</span>
                    </div>

                    <div class="space-y-4">
                        @forelse ($userPerformance as $index => $user)
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
                                                class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
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
                                    <p
                                        class="text-2xl font-bold {{ $user->performance >= 90 ? 'text-green-600' : ($user->performance >= 80 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ $user->performance }}%
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $user->absensi_count }} dari {{ $user->total_absensi }} hari
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-chart-line text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-500">Belum ada data performa bulan ini</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($userPerformance->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    Menampilkan {{ $userPerformance->count() }} user terbaik
                                </span>
                                <a href="{{ route('admin.users') }}"
                                    class="text-primary-color text-sm font-medium hover:underline">
                                    Lihat semua user
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Reports -->
            <div class="space-y-6">
                <!-- Daily Report -->
                <div class="card">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Laporan Harian</h4>
                                <p class="text-sm text-gray-600">Data kehadiran hari ini</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.attendance') }}?date={{ now()->format('Y-m-d') }}"
                            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Weekly Report -->
                <div class="card">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-bar text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Laporan Mingguan</h4>
                                <p class="text-sm text-gray-600">Statistik 7 hari terakhir</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.attendance') }}?start_date={{ now()->subDays(7)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}"
                            class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                            Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Monthly Export -->
                <div class="card">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-export text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Export Bulanan</h4>
                                <p class="text-sm text-gray-600">Export data {{ now()->format('F Y') }}</p>
                            </div>
                        </div>
                        <button onclick="showExportModal()"
                            class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium">
                            Export Data <i class="fas fa-download ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Trends & Insights -->
        <div class="card">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Insight & Analisis</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow">
                            <i class="fas fa-trending-up text-blue-600 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">Bulan Terbaik</p>
                        @php
                            $bestMonth = $monthlyStats->sortByDesc('percentage')->first();
                        @endphp
                        <p class="text-xl font-bold text-gray-800">{{ $bestMonth['month'] ?? '-' }}</p>
                        <p class="text-sm text-green-600 mt-1">{{ $bestMonth['percentage'] ?? 0 }}% tepat waktu</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow">
                            <i class="fas fa-users text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">Partisipasi Rata-rata</p>
                        <p class="text-xl font-bold text-gray-800">
                            @php
                                $totalUsers = App\Models\User::where('role', 'user')->count();
                                $avgAttendance = $monthlyStats->avg('total');
                                $participation =
                                    $totalUsers > 0
                                        ? round(($avgAttendance / ($totalUsers * $monthlyStats->count())) * 100, 1)
                                        : 0;
                            @endphp
                            {{ $participation }}%
                        </p>
                        <p class="text-sm text-gray-500 mt-1">User aktif</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow">
                            <i class="fas fa-exclamation-triangle text-amber-600 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">Keterlambatan</p>
                        <p class="text-xl font-bold text-gray-800">{{ $monthlyStats->sum('late') }}</p>
                        <p class="text-sm text-amber-600 mt-1">Total 6 bulan</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow">
                            <i class="fas fa-chart-pie text-purple-600 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">Distribusi</p>
                        <p class="text-xl font-bold text-gray-800">
                            @php
                                $total = $monthlyStats->sum('total');
                                $latePercentage =
                                    $total > 0 ? round(($monthlyStats->sum('late') / $total) * 100, 1) : 0;
                            @endphp
                            {{ 100 - $latePercentage }}:{{ $latePercentage }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Tepat:Terlambat</p>
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-4">Rekomendasi</h4>
                    <div class="space-y-3">
                        @php
                            $avgPercentage = $monthlyStats->avg('percentage');
                        @endphp

                        @if ($avgPercentage >= 90)
                            <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-thumbs-up text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800">Performansi Excellent!</p>
                                    <p class="text-sm text-green-600">Tim Anda menunjukkan konsistensi yang sangat baik
                                        dalam ketepatan waktu.</p>
                                </div>
                            </div>
                        @elseif($avgPercentage >= 80)
                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check-circle text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-blue-800">Performansi Baik</p>
                                    <p class="text-sm text-blue-600">Pertahankan konsistensi tim Anda. Ada ruang untuk
                                        peningkatan kecil.</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3 p-3 bg-amber-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-lightbulb text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-amber-800">Perlu Perbaikan</p>
                                    <p class="text-sm text-amber-600">Pertimbangkan untuk meninjau kebijakan waktu kerja
                                        atau memberikan reminder kepada tim.</p>
                                </div>
                            </div>
                        @endif

                        @if ($monthlyStats->sum('late') > 50)
                            <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg">
                                <div
                                    class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-red-800">Tingkat Keterlambatan Tinggi</p>
                                    <p class="text-sm text-red-600">{{ $monthlyStats->sum('late') }} kasus keterlambatan
                                        dalam 6 bulan terakhir. Pertimbangkan intervensi.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Export Laporan</h3>
                <button onclick="hideExportModal()"
                    class="w-10 h-10 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.export') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Laporan</label>
                    <select name="report_period" id="reportPeriod"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="monthly">Bulan Ini</option>
                        <option value="last_month">Bulan Lalu</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>

                <div id="customDateRange" class="hidden grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format File</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative">
                            <input type="radio" name="format" value="excel" class="sr-only peer" checked>
                            <div
                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-excel text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Excel</p>
                                        <p class="text-sm text-gray-500">.xlsx format</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="format" value="pdf" class="sr-only peer">
                            <div
                                class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">PDF</p>
                                        <p class="text-sm text-gray-500">.pdf format</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="hideExportModal()" class="flex-1 btn btn-outline">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 btn btn-primary">
                        <i class="fas fa-download mr-2"></i>
                        Export Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function showExportModal() {
                const modal = document.getElementById('exportModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.querySelector('.transform').classList.remove('scale-95');
                    modal.querySelector('.transform').classList.add('scale-100');
                }, 10);
            }

            function hideExportModal() {
                const modal = document.getElementById('exportModal');
                modal.querySelector('.transform').classList.remove('scale-100');
                modal.querySelector('.transform').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            // Toggle custom date range
            document.getElementById('reportPeriod').addEventListener('change', function(e) {
                const customRange = document.getElementById('customDateRange');
                if (e.target.value === 'custom') {
                    customRange.classList.remove('hidden');
                } else {
                    customRange.classList.add('hidden');
                }
            });

            // Close modal on background click
            document.getElementById('exportModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideExportModal();
                }
            });

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('exportModal').classList.contains('hidden')) {
                    hideExportModal();
                }
            });
        </script>
    @endpush
@endsection
