@extends('layouts.admin')

@section('title', 'Sesi Absensi')
@section('page-title', 'Sesi Absensi')
@section('page-description', 'Kelola jadwal waktu absensi')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Sesi Absensi</h1>
                <p class="text-gray-600">Atur jadwal waktu masuk dan pulang untuk absensi</p>
            </div>

            <div>
                <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary group">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Sesi Baru</span>
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-1">Informasi Sesi</h3>
                        <p class="text-sm text-gray-600">
                            Sesi absensi menentukan waktu yang diperbolehkan untuk melakukan absensi masuk dan pulang.
                            User hanya bisa absen dalam rentang waktu yang telah ditentukan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Grid -->
        @if ($sessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($sessions as $session)
                    <div class="card group hover:shadow-lg transition-all duration-300">
                        <div class="p-6">
                            <!-- Session Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $session->nama_sesi }}</h3>
                                        @if ($session->keterangan)
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ $session->keterangan }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Time Display -->
                                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-blue-50 rounded-lg">
                                        <div class="text-center">
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-sign-in-alt text-blue-500 text-sm"></i>
                                                <span
                                                    class="font-mono text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($session->jam_mulai)->format('H:i') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Mulai</p>
                                        </div>

                                        <div class="text-gray-400">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>

                                        <div class="text-center">
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-sign-out-alt text-green-500 text-sm"></i>
                                                <span
                                                    class="font-mono text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($session->jam_selesai)->format('H:i') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Selesai</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.sessions.edit', $session) }}"
                                        class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.sessions.delete', $session) }}" method="POST"
                                        onsubmit="return confirmAction('Apakah Anda yakin ingin menghapus sesi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-9 h-9 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Session Details -->
                            <!-- Update bagian Session Details di index.blade.php -->
                            <!-- Session Details -->
                            <div class="space-y-3">
                                <!-- Duration -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Durasi Masuk:</span>
                                    <span class="font-semibold text-gray-800">
                                        @php
                                            $start = \Carbon\Carbon::parse($session->jam_mulai);
                                            $end = \Carbon\Carbon::parse($session->jam_selesai);
                                            $diff = $start->diff($end);
                                        @endphp
                                        {{ $diff->h }} jam {{ $diff->i }} menit
                                    </span>
                                </div>

                                <!-- Toleransi Keterlambatan -->
                                @if ($session->toleransi_keterlambatan)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Toleransi Terlambat:</span>
                                        <span class="font-semibold text-amber-600">
                                            {{ \Carbon\Carbon::parse($session->toleransi_keterlambatan)->format('H:i') }}
                                            @php
                                                $toleransiDiff = \Carbon\Carbon::parse($session->jam_selesai)->diff(
                                                    \Carbon\Carbon::parse($session->toleransi_keterlambatan),
                                                );
                                            @endphp
                                            <span class="text-xs text-gray-500">(+{{ $toleransiDiff->h }}j
                                                {{ $toleransiDiff->i }}m)</span>
                                        </span>
                                    </div>
                                @endif

                                <!-- Maksimal Jam Pulang -->
                                @if ($session->maksimal_jam_pulang)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Maksimal Pulang:</span>
                                        <span class="font-semibold text-green-600">
                                            {{ \Carbon\Carbon::parse($session->maksimal_jam_pulang)->format('H:i') }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Status Aktif -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Status:</span>
                                    @if ($session->aktif)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>

                                <!-- Created Info -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Dibuat:</span>
                                    <span class="text-gray-600">{{ $session->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 mt-6 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.sessions.edit', $session) }}"
                                    class="flex-1 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium text-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Sesi
                                </a>

                                <a href="{{ route('admin.attendance') }}?sesi_id={{ $session->id }}"
                                    class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium text-center">
                                    <i class="fas fa-list mr-2"></i>
                                    Lihat Absensi
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="card">
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Sesi Absensi</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan membuat sesi absensi pertama Anda</p>
                    <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary inline-flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Buat Sesi Pertama
                    </a>
                </div>
            </div>
        @endif

        <!-- Add New Session Card -->
        @if ($sessions->count() > 0)
            <div class="mt-6">
                <a href="{{ route('admin.sessions.create') }}"
                    class="card group border-2 border-dashed border-gray-300 hover:border-blue-300 hover:bg-blue-50 transition">
                    <div class="p-8 flex flex-col items-center justify-center text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Tambah Sesi Baru</h3>
                        <p class="text-gray-500">Buat jadwal absensi tambahan</p>
                    </div>
                </a>
            </div>
        @endif

        <!-- Session Guidelines -->
        <div class="card">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Panduan Penggunaan Sesi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">Waktu Fleksibel</h4>
                            <p class="text-sm text-gray-600">Atur waktu sesuai kebutuhan kerja tim Anda</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">Untuk Semua User</h4>
                            <p class="text-sm text-gray-600">Sesi berlaku untuk semua user dalam sistem</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">Multi Sesi</h4>
                            <p class="text-sm text-gray-600">Buat beberapa sesi untuk hari yang berbeda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Statistics -->
        @if ($sessions->count() > 0)
            <div class="card">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Statistik Sesi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-6 bg-gray-50 rounded-xl">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-list-alt text-blue-600 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mb-1">Total Sesi</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $sessions->count() }}</p>
                            <p class="text-sm text-gray-500 mt-2">Sesi tersedia</p>
                        </div>

                        <div class="text-center p-6 bg-gray-50 rounded-xl">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-day text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mb-1">Rata-rata Durasi</p>
                            <p class="text-3xl font-bold text-gray-800">
                                @php
                                    $totalMinutes = 0;
                                    foreach ($sessions as $session) {
                                        $start = \Carbon\Carbon::parse($session->jam_mulai);
                                        $end = \Carbon\Carbon::parse($session->jam_selesai);
                                        $totalMinutes += $start->diffInMinutes($end);
                                    }
                                    $averageMinutes = $sessions->count() > 0 ? $totalMinutes / $sessions->count() : 0;
                                    $hours = floor($averageMinutes / 60);
                                    $minutes = $averageMinutes % 60;
                                @endphp
                                {{ $hours }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-sm text-gray-500 mt-2">Jam:Menit</p>
                        </div>

                        <div class="text-center p-6 bg-gray-50 rounded-xl">
                            <div
                                class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-purple-600 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mb-1">Terbaru</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ $sessions->max('created_at')->diffForHumans() }}
                            </p>
                            <p class="text-sm text-gray-500 mt-2">Diupdate</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
