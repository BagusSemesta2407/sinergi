@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-lg">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Edit Data User</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Note about password -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Untuk mengubah password user, gunakan fitur reset password di dashboard user.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Button Actions -->
                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('admin.users') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex-1">
                            <i class="fas fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection