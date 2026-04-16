@extends('layouts.admin')

@section('title', 'Edit Sesi')
@section('page-title', 'Edit Sesi: ' . $session->nama_sesi)

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.sessions') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Sesi</span>
            </a>
        </div>

        <div class="card">
            <div class="p-8">
                <!-- Form Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Edit Sesi</h2>
                            <p class="text-gray-600">Perbarui informasi sesi absensi</p>
                        </div>
                    </div>

                    <!-- Session Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Dibuat pada</p>
                                <p class="font-medium text-gray-800">{{ $session->created_at->format('d F Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Terakhir diupdate</p>
                                <p class="font-medium text-gray-800">{{ $session->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.sessions.update', $session) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Nama Sesi -->
                        <div>
                            <label for="nama_sesi" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Sesi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="nama_sesi" name="nama_sesi"
                                    value="{{ old('nama_sesi', $session->nama_sesi) }}" required
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                          @error('nama_sesi') border-red-500 @enderror">
                                <div class="absolute left-4 top-3 text-gray-400">
                                    <i class="fas fa-heading"></i>
                                </div>
                            </div>
                            @error('nama_sesi')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Mulai & Selesai -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="time" id="jam_mulai" name="jam_mulai"
                                        value="{{ old('jam_mulai', \Carbon\Carbon::parse($session->jam_mulai)->format('H:i')) }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                              @error('jam_mulai') border-red-500 @enderror">
                                    <div class="absolute left-4 top-3 text-gray-400">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                </div>
                                @error('jam_mulai')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="time" id="jam_selesai" name="jam_selesai"
                                        value="{{ old('jam_selesai', \Carbon\Carbon::parse($session->jam_selesai)->format('H:i')) }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                              @error('jam_selesai') border-red-500 @enderror">
                                    <div class="absolute left-4 top-3 text-gray-400">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                </div>
                                @error('jam_selesai')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Toleransi Keterlambatan -->
                        <div>
                            <label for="toleransi_keterlambatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Toleransi Keterlambatan (Opsional)
                            </label>
                            <div class="relative">
                                <input type="time" id="toleransi_keterlambatan" name="toleransi_keterlambatan"
                                    value="{{ old('toleransi_keterlambatan', $session->toleransi_keterlambatan ? \Carbon\Carbon::parse($session->toleransi_keterlambatan)->format('H:i') : '') }}"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                          @error('toleransi_keterlambatan') border-red-500 @enderror">
                                <div class="absolute left-4 top-3 text-gray-400">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                            @error('toleransi_keterlambatan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Batas akhir absen masuk dengan status "terlambat"
                            </p>
                        </div>

                        <!-- Maksimal Jam Pulang -->
                        <div>
                            <label for="maksimal_jam_pulang" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Jam Pulang (Opsional)
                            </label>
                            <div class="relative">
                                <input type="time" id="maksimal_jam_pulang" name="maksimal_jam_pulang"
                                    value="{{ old('maksimal_jam_pulang', $session->maksimal_jam_pulang ? \Carbon\Carbon::parse($session->maksimal_jam_pulang)->format('H:i') : '') }}"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                          @error('maksimal_jam_pulang') border-red-500 @enderror">
                                <div class="absolute left-4 top-3 text-gray-400">
                                    <i class="fas fa-stopwatch"></i>
                                </div>
                            </div>
                            @error('maksimal_jam_pulang')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Batas akhir absen pulang
                            </p>
                        </div>

                        <!-- Duration Preview -->
                        <div id="durationPreview" class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">Durasi Absen Masuk:</div>
                                    <div class="font-semibold text-blue-600" id="durationText">
                                        @php
                                            $start = \Carbon\Carbon::parse($session->jam_mulai);
                                            $end = \Carbon\Carbon::parse($session->jam_selesai);
                                            $diff = $start->diff($end);
                                        @endphp
                                        {{ $diff->h }} jam {{ $diff->i }} menit
                                    </div>
                                </div>
                                
                                @if($session->toleransi_keterlambatan)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Rentang Toleransi:</span>
                                        <span class="font-medium text-amber-600">
                                            {{ \Carbon\Carbon::parse($session->jam_selesai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($session->toleransi_keterlambatan)->format('H:i') }}
                                            @php
                                                $toleransiDiff = \Carbon\Carbon::parse($session->jam_selesai)
                                                    ->diff(\Carbon\Carbon::parse($session->toleransi_keterlambatan));
                                            @endphp
                                            ({{ $toleransiDiff->h }}j {{ $toleransiDiff->i }}m)
                                        </span>
                                    </div>
                                @endif
                                
                                @if($session->maksimal_jam_pulang)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Maksimal Absen Pulang:</span>
                                        <span class="font-medium text-green-600">
                                            {{ \Carbon\Carbon::parse($session->maksimal_jam_pulang)->format('H:i') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan (Opsional)
                            </label>
                            <div class="relative">
                                <textarea id="keterangan" name="keterangan" rows="3"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                             @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $session->keterangan) }}</textarea>
                                <div class="absolute left-4 top-3 text-gray-400">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                            </div>
                            @error('keterangan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="aktif" id="aktif" value="1" 
                                       class="sr-only peer"
                                       {{ old('aktif', $session->aktif) ? 'checked' : '' }}>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                            after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                            peer-checked:bg-blue-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    Jadikan sebagai sesi aktif saat ini
                                </span>
                            </label>
                            @if($session->aktif)
                                <p class="mt-2 text-sm text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Saat ini sesi ini aktif
                                </p>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="flex gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.sessions') }}" class="flex-1 btn btn-outline">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="flex-1 btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Update Sesi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        @if ($session->absensi()->count() == 0)
            <div class="card mt-6 border border-red-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-red-700 mb-4">Zona Bahaya</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Sesi ini belum pernah digunakan untuk absensi. Anda dapat menghapusnya jika tidak diperlukan lagi.
                    </p>
                    <form action="{{ route('admin.sessions.delete', $session) }}" method="POST"
                        onsubmit="return confirmAction('Apakah Anda yakin ingin menghapus sesi ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn btn-outline border-red-300 text-red-600 hover:bg-red-50 hover:border-red-400">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Sesi
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card mt-6 border border-yellow-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-yellow-700 mb-4">Informasi Penting</h3>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-2">
                                Sesi ini telah digunakan <span class="font-bold">{{ $session->absensi()->count() }}</span> kali untuk absensi.
                            </p>
                            <p class="text-sm text-gray-600">
                                Perubahan waktu sesi (termasuk toleransi dan maksimal pulang) akan mempengaruhi:
                            </p>
                            <ul class="list-disc list-inside text-sm text-gray-600 mt-2 ml-4">
                                <li>Absensi yang akan datang</li>
                                <li>Validasi waktu absen masuk dan pulang</li>
                                <li>Status terlambat/tidak bisa absen</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const jamMulai = document.getElementById('jam_mulai');
                const jamSelesai = document.getElementById('jam_selesai');
                const toleransiInput = document.getElementById('toleransi_keterlambatan');
                const maksimalPulangInput = document.getElementById('maksimal_jam_pulang');
                const durationPreview = document.getElementById('durationPreview');
                const durationText = document.getElementById('durationText');

                function calculateDuration() {
                    if (jamMulai.value && jamSelesai.value) {
                        const start = new Date(`2000-01-01T${jamMulai.value}`);
                        const end = new Date(`2000-01-01T${jamSelesai.value}`);

                        if (end <= start) {
                            durationPreview.classList.remove('bg-blue-50', 'border-blue-100');
                            durationPreview.classList.add('bg-red-50', 'border-red-100');
                            durationText.innerHTML = '<span class="text-red-600">Waktu selesai harus setelah waktu mulai</span>';
                            return;
                        }

                        const diffMs = end - start;
                        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                        const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                        let duration = '';
                        if (diffHours > 0) {
                            duration += `${diffHours} jam `;
                        }
                        if (diffMinutes > 0) {
                            duration += `${diffMinutes} menit`;
                        }

                        durationText.textContent = duration || '0 menit';
                        durationPreview.classList.remove('bg-red-50', 'border-red-100');
                        durationPreview.classList.add('bg-blue-50', 'border-blue-100');
                    }
                }

                jamMulai.addEventListener('change', calculateDuration);
                jamSelesai.addEventListener('change', calculateDuration);
                toleransiInput.addEventListener('change', calculateDuration);
                maksimalPulangInput.addEventListener('change', calculateDuration);
            });
        </script>
    @endpush
@endsection