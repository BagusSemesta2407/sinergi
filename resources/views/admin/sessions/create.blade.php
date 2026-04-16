@extends('layouts.admin')

@section('title', 'Tambah Sesi')
@section('page-title', 'Tambah Sesi Baru')
@section('page-description', 'Buat jadwal waktu absensi baru')

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
                            <h2 class="text-2xl font-bold text-gray-800">Tambah Sesi Baru</h2>
                            <p class="text-gray-600">Isi form untuk membuat jadwal absensi</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.sessions.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Nama Sesi -->
                        <div>
                            <label for="nama_sesi" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Sesi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="nama_sesi" name="nama_sesi" value="{{ old('nama_sesi') }}"
                                    required placeholder="Contoh: Sesi Pagi, Shift 1, Kerja Normal"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                          @error('nama_sesi') border-red-500 @enderror">
                                <div class="absolute left-4 top-3 text-gray-400">
                                    <i class="fas fa-heading"></i>
                                </div>
                            </div>
                            @error('nama_sesi')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">Berikan nama yang mudah diingat untuk sesi ini</p>
                        </div>

                        <!-- Jam Mulai & Selesai -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}"
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
                                        value="{{ old('jam_selesai') }}" required
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
                                <span class="text-xs text-blue-600 font-normal ml-2">
                                    <i class="fas fa-info-circle"></i> Batas akhir absen masuk
                                </span>
                            </label>
                            <div class="relative">
                                <input type="time" id="toleransi_keterlambatan" name="toleransi_keterlambatan"
                                    value="{{ old('toleransi_keterlambatan') }}"
                                    placeholder="Contoh: 09:00"
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
                                Batas akhir absen masuk dengan status "terlambat". Jika dikosongkan, absen hanya sampai jam selesai.
                            </p>
                        </div>

                        <!-- Maksimal Jam Pulang -->
                        <div>
                            <label for="maksimal_jam_pulang" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Jam Pulang (Opsional)
                                <span class="text-xs text-blue-600 font-normal ml-2">
                                    <i class="fas fa-info-circle"></i> Batas akhir absen pulang
                                </span>
                            </label>
                            <div class="relative">
                                <input type="time" id="maksimal_jam_pulang" name="maksimal_jam_pulang"
                                    value="{{ old('maksimal_jam_pulang') }}"
                                    placeholder="Contoh: 17:00"
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
                                Batas akhir absen pulang. Jika dikosongkan, absen pulang bisa dilakukan kapan saja setelah jam selesai.
                            </p>
                        </div>

                        <!-- Duration Preview -->
                        <div id="durationPreview" class="hidden p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">Durasi Absen Masuk:</div>
                                    <div class="font-semibold text-blue-600" id="durationText"></div>
                                </div>
                                <div id="toleransiInfo" class="hidden">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Rentang Toleransi:</span>
                                        <span class="font-medium text-amber-600" id="toleransiRange"></span>
                                    </div>
                                </div>
                                <div id="maksimalPulangInfo" class="hidden">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Maksimal Absen Pulang:</span>
                                        <span class="font-medium text-green-600" id="maksimalPulangTime"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan (Opsional)
                            </label>
                            <div class="relative">
                                <textarea id="keterangan" name="keterangan" rows="3"
                                    placeholder="Tambahkan catatan atau deskripsi tentang sesi ini..."
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                             @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                                <div class="absolute left-4 top-3 text-gray-400">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                            </div>
                            @error('keterangan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">Deskripsi singkat tentang sesi ini (opsional)</p>
                        </div>

                        <!-- Status Aktif -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="aktif" id="aktif" value="1" 
                                       class="sr-only peer"
                                       {{ old('aktif') ? 'checked' : '' }}>
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
                            <p class="mt-2 text-sm text-gray-500">
                                Jika dicentang, sesi ini akan menjadi sesi aktif dan menonaktifkan sesi aktif sebelumnya.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.sessions') }}" class="flex-1 btn btn-outline">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="flex-1 btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Sesi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="card mt-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tips Membuat Sesi</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-green-600 text-xs"></i>
                        </div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Toleransi Keterlambatan:</span> 
                            Batas akhir untuk absen masuk dengan status "terlambat". Contoh: jam selesai 07:30, toleransi 09:00
                        </p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-green-600 text-xs"></i>
                        </div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Maksimal Jam Pulang:</span> 
                            Batas akhir untuk absen pulang. Jika kosong, user bisa absen pulang kapan saja setelah jam selesai.
                        </p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-green-600 text-xs"></i>
                        </div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Sesi Aktif:</span> 
                            Hanya satu sesi yang bisa aktif dalam satu waktu. Sesi aktif yang digunakan user untuk absen.
                        </p>
                    </div>
                </div>
            </div>
        </div>
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
                const toleransiInfo = document.getElementById('toleransiInfo');
                const toleransiRange = document.getElementById('toleransiRange');
                const maksimalPulangInfo = document.getElementById('maksimalPulangInfo');
                const maksimalPulangTime = document.getElementById('maksimalPulangTime');

                function formatTime(date) {
                    return date.toLocaleTimeString('id-ID', { 
                        hour: '2-digit', 
                        minute: '2-digit',
                        hour12: false 
                    });
                }

                function calculateDuration() {
                    if (jamMulai.value && jamSelesai.value) {
                        const start = new Date(`2000-01-01T${jamMulai.value}`);
                        const end = new Date(`2000-01-01T${jamSelesai.value}`);

                        if (end <= start) {
                            durationPreview.classList.remove('bg-blue-50', 'border-blue-100');
                            durationPreview.classList.add('bg-red-50', 'border-red-100');
                            durationText.innerHTML = '<span class="text-red-600">Waktu selesai harus setelah waktu mulai</span>';
                            toleransiInfo.classList.add('hidden');
                            maksimalPulangInfo.classList.add('hidden');
                            durationPreview.classList.remove('hidden');
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
                        durationPreview.classList.remove('bg-red-50', 'border-red-100', 'hidden');
                        durationPreview.classList.add('bg-blue-50', 'border-blue-100');

                        // Toleransi informasi
                        if (toleransiInput.value) {
                            const toleransi = new Date(`2000-01-01T${toleransiInput.value}`);
                            if (toleransi > end) {
                                const toleransiDiff = toleransi - end;
                                const toleransiHours = Math.floor(toleransiDiff / (1000 * 60 * 60));
                                const toleransiMinutes = Math.floor((toleransiDiff % (1000 * 60 * 60)) / (1000 * 60));
                                
                                toleransiRange.textContent = `${formatTime(end)} - ${formatTime(toleransi)} (${toleransiHours}j ${toleransiMinutes}m)`;
                                toleransiInfo.classList.remove('hidden');
                            } else {
                                toleransiInfo.classList.add('hidden');
                            }
                        } else {
                            toleransiInfo.classList.add('hidden');
                        }

                        // Maksimal pulang informasi
                        if (maksimalPulangInput.value) {
                            const maksimalPulang = new Date(`2000-01-01T${maksimalPulangInput.value}`);
                            if (maksimalPulang > end) {
                                maksimalPulangTime.textContent = formatTime(maksimalPulang);
                                maksimalPulangInfo.classList.remove('hidden');
                            } else {
                                maksimalPulangInfo.classList.add('hidden');
                            }
                        } else {
                            maksimalPulangInfo.classList.add('hidden');
                        }

                        durationPreview.classList.remove('hidden');
                    } else {
                        durationPreview.classList.add('hidden');
                    }
                }

                jamMulai.addEventListener('change', calculateDuration);
                jamSelesai.addEventListener('change', calculateDuration);
                toleransiInput.addEventListener('change', calculateDuration);
                maksimalPulangInput.addEventListener('change', calculateDuration);

                // Calculate on page load if values exist
                if (jamMulai.value || jamSelesai.value) {
                    calculateDuration();
                }
            });
        </script>
    @endpush
@endsection