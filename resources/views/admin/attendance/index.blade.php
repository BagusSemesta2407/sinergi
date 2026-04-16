@extends('layouts.admin')

@section('title', 'Data Kehadiran')
@section('page-title', 'Data Kehadiran')
@section('page-description', 'Riwayat absensi pengguna')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Kehadiran</h1>
            <p class="text-gray-600">Riwayat absensi dan presensi karyawan</p>
        </div>
        
        <div class="flex gap-3">
            <button onclick="showExportModal()" 
                    class="btn btn-outline group">
                <i class="fas fa-file-export text-green-500 group-hover:text-green-600"></i>
                <span>Export Data</span>
            </button>
            
            <a href="#" 
               class="btn btn-primary group">
                <i class="fas fa-print"></i>
                <span>Print Laporan</span>
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.attendance') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date Range -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="date" 
                                       name="start_date" 
                                       value="{{ request('start_date') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <div class="absolute left-3 top-3 text-gray-400">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="date" 
                                       name="end_date" 
                                       value="{{ request('end_date') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <div class="absolute left-3 top-3 text-gray-400">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                        <div class="relative">
                            <select name="user_id" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-3 top-3 text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="relative">
                            <select name="status_masuk" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none">
                                <option value="">Semua Status</option>
                                <option value="tepat_waktu" {{ request('status_masuk') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                                <option value="terlambat" {{ request('status_masuk') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="tidak_absen" {{ request('status_masuk') == 'tidak_absen' ? 'selected' : '' }}>Tidak Absen</option>
                            </select>
                            <div class="absolute left-3 top-3 text-gray-400">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama user atau keterangan..."
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <div class="absolute left-4 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3">
                    @if(request()->hasAny(['start_date', 'end_date', 'user_id', 'status_masuk', 'search']))
                        <a href="{{ route('admin.attendance') }}" 
                           class="btn btn-outline">
                            <i class="fas fa-times"></i>
                            Reset Filter
                        </a>
                    @endif
                    
                    <button type="submit" 
                            class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Data</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $attendance->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-database text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Tepat Waktu</p>
                        <p class="text-2xl font-bold text-green-600">{{ $attendance->where('status_masuk', 'tepat_waktu')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Terlambat</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $attendance->where('status_masuk', 'terlambat')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-amber-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Tidak Absen</p>
                        <p class="text-2xl font-bold text-red-600">{{ $attendance->where('status_masuk', 'tidak_absen')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="space-y-4">
        @if($attendance->count() > 0)
            <!-- List Header -->
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Kehadiran</h3>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $attendance->count() }} dari {{ $attendance->total() }} data
                </div>
            </div>

            <!-- Attendance Cards -->
            <div class="space-y-4">
                @foreach ($attendance as $absensi)
                    <div class="card hover:shadow-lg transition-all duration-300">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <!-- Left Section -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($absensi->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $absensi->user->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $absensi->user->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Tanggal</p>
                                            <p class="font-medium text-gray-800">
                                                {{ $absensi->tanggal->format('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $absensi->tanggal->format('l') }}</p>
                                        </div>
                                        
                                        <div>
                                            <p class="text-xs text-gray-500">Waktu</p>
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-sign-in-alt text-blue-500 text-xs"></i>
                                                    <span class="font-medium text-gray-800">{{ $absensi->jam_masuk ? $absensi->jam_masuk->format('H:i') : '-' }}</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-sign-out-alt text-green-500 text-xs"></i>
                                                    <span class="font-medium text-gray-800">{{ $absensi->jam_pulang ? $absensi->jam_pulang->format('H:i') : 'Belum' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <p class="text-xs text-gray-500">Sesi</p>
                                            <p class="font-medium text-gray-800">{{ $absensi->sesiAbsensi->nama_sesi ?? '-' }}</p>
                                            @if($absensi->sesiAbsensi)
                                                <p class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($absensi->sesiAbsensi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($absensi->sesiAbsensi->jam_selesai)->format('H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <p class="text-xs text-gray-500">Status</p>
                                            @php
                                                $statusColors = [
                                                    'tepat_waktu' => 'bg-green-100 text-green-800',
                                                    'terlambat' => 'bg-yellow-100 text-yellow-800',
                                                    'cepat' => 'bg-blue-100 text-blue-800',
                                                    'tidak_absen' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$absensi->status_masuk] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $absensi->status_label }}
                                            </span>
                                            
                                            <div class="mt-1">
                                                @if($absensi->bukti_diupload)
                                                    <span class="inline-flex items-center text-xs text-green-600">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Sudah upload bukti
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center text-xs text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Belum upload
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Section - Actions -->
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.attendance.detail', $absensi) }}" 
                                       class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="#" 
                                       class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-100 transition"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($absensi->bukti_pekerjaan)
                                        <a href="{{ $absensi->bukti_url }}" 
                                           target="_blank"
                                           class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center hover:bg-purple-100 transition"
                                           title="Download Bukti">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            @if($absensi->catatan)
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                                        {{ $absensi->catatan }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="card">
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clipboard-list text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Data Kehadiran</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request()->hasAny(['start_date', 'end_date', 'user_id', 'status_masuk', 'search']))
                            Tidak ada data kehadiran yang sesuai dengan filter pencarian Anda
                        @else
                            Belum ada data kehadiran yang tercatat
                        @endif
                    </p>
                    @if(request()->hasAny(['start_date', 'end_date', 'user_id', 'status_masuk', 'search']))
                        <a href="{{ route('admin.attendance') }}" 
                           class="btn btn-outline">
                            <i class="fas fa-times mr-2"></i>
                            Reset Filter
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($attendance->hasPages())
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $attendance->firstItem() ?? 0 }} - {{ $attendance->lastItem() ?? 0 }} dari {{ $attendance->total() }} data
            </div>
            
            <div class="flex items-center gap-2">
                @if($attendance->onFirstPage())
                    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $attendance->previousPageUrl() }}" 
                       class="px-4 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif
                
                @foreach ($attendance->getUrlRange(max(1, $attendance->currentPage() - 2), min($attendance->lastPage(), $attendance->currentPage() + 2)) as $page => $url)
                    @if($page == $attendance->currentPage())
                        <span class="px-4 py-2 bg-primary-color text-white rounded-lg font-medium">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" 
                           class="px-4 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
                
                @if($attendance->hasMorePages())
                    <a href="{{ $attendance->nextPageUrl() }}" 
                       class="px-4 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Export Data</h3>
            <button onclick="hideExportModal()" 
                    class="w-10 h-10 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.export') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format File</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative">
                        <input type="radio" name="format" value="excel" class="sr-only peer" checked>
                        <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
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
                        <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition">
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User (Opsional)</label>
                <select name="user_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="hideExportModal()" class="flex-1 btn btn-outline">
                    Batal
                </button>
                <button type="submit" class="flex-1 btn btn-primary">
                    <i class="fas fa-download mr-2"></i>
                    Export Data
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