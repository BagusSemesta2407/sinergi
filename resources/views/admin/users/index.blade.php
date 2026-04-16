@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-description', 'Kelola data pengguna sistem')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
                <p class="text-gray-600">Total {{ $users->total() }} pengguna terdaftar</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.import.form') }}" class="btn btn-outline group">
                    <i class="fas fa-file-import text-amber-500 group-hover:text-amber-600"></i>
                    <span>Import Users</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary group">
                    <i class="fas fa-user-plus"></i>
                    <span>Tambah User Baru</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card">
                <div class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Users</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $users->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">User Aktif</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $users->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-shield text-purple-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Admin</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $users->where('role', 'admin')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-clock text-amber-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Baru Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card">
            <div class="p-6">
                <form action="{{ route('admin.users') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari User</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama atau email..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <div class="absolute left-3 top-3 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select name="role"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        @if (request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.users') }}" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Grid -->
        @if ($users->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($users as $user)
                    <div class="card group hover:shadow-lg transition-all duration-300">
                        <div class="p-6">
                            <!-- User Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        @if ($user->role == 'admin')
                                            <div
                                                class="absolute -top-1 -right-1 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-crown text-white text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- User Details -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Role:</span>
                                    <span
                                        class="font-medium {{ $user->role == 'admin' ? 'text-purple-600' : 'text-blue-600' }}">
                                        {{ $user->role == 'admin' ? 'Administrator' : 'User' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Status:</span>
                                    <span
                                        class="font-medium {{ $user->status == 'active' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $user->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Bergabung:</span>
                                    <span class="text-gray-600">{{ $user->created_at->format('d M Y') }}</span>
                                </div>

                                @if ($user->absensi_count > 0)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Kehadiran:</span>
                                        <span class="font-medium text-green-600">{{ $user->absensi_count }} hari</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="flex-1 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium text-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>

                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST"
                                        class="flex-1"
                                        onsubmit="return confirmAction('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                            <i class="fas fa-trash mr-2"></i>
                                            Hapus
                                        </button>
                                    </form>
                                @endif

                                <a href="#"
                                    class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium text-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    Detail
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
                        <i class="fas fa-users text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Data User</h3>
                    <p class="text-gray-500 mb-6">
                        @if (request()->hasAny(['search', 'role', 'status']))
                            Tidak ada user yang sesuai dengan filter pencarian Anda
                        @else
                            Mulai dengan menambahkan user pertama
                        @endif
                    </p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary inline-flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Tambah User Pertama
                    </a>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }}
                    user
                </div>

                <div class="flex items-center gap-2">
                    @if ($users->onFirstPage())
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-4 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                        @if ($page == $users->currentPage())
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

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
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

    @push('styles')
        <style>
            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
        </style>
    @endpush
@endsection
