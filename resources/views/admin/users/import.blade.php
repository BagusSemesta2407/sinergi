@extends('layouts.admin')

@section('title', 'Import Users')
@section('page-title', 'Import Users')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800">Import Users dari File</h3>
                <p class="text-gray-600">Upload file Excel atau CSV untuk menambahkan atau update multiple users sekaligus
                </p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-semibold">{{ session('success') }}</p>
                            @if (session('import_stats'))
                                @php $stats = session('import_stats'); @endphp
                                <div class="mt-2 grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-green-600">{{ $stats['imported'] ?? 0 }}</p>
                                        <p class="text-xs text-green-500">Baru</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-blue-600">{{ $stats['updated'] ?? 0 }}</p>
                                        <p class="text-xs text-blue-500">Diupdate</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-red-600">{{ $stats['errors'] ?? 0 }}</p>
                                        <p class="text-xs text-red-500">Error</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Display -->
            @if (session('validation_errors') || session('import_errors'))
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 font-semibold mb-2">
                                @if (session('validation_errors'))
                                    Terdapat {{ count(session('validation_errors')) }} error validasi:
                                @elseif(session('import_errors'))
                                    Terdapat {{ count(session('import_errors')) }} error saat import:
                                @endif
                            </p>

                            <div class="max-h-60 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead>
                                        <tr class="bg-yellow-100">
                                            <th class="px-3 py-2 text-left">Baris</th>
                                            <th class="px-3 py-2 text-left">Email</th>
                                            <th class="px-3 py-2 text-left">Error</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-yellow-100">
                                        @if (session('validation_errors'))
                                            @foreach (session('validation_errors') as $error)
                                                <tr>
                                                    <td class="px-3 py-2 font-mono">{{ $error['row'] }}</td>
                                                    <td class="px-3 py-2">{{ $error['values'][1] ?? 'N/A' }}</td>
                                                    <td class="px-3 py-2 text-red-600">{{ implode(', ', $error['errors']) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif(session('import_errors'))
                                            @foreach (session('import_errors') as $error)
                                                <tr>
                                                    <td class="px-3 py-2 font-mono">{{ $error['row'] }}</td>
                                                    <td class="px-3 py-2">{{ $error['email'] }}</td>
                                                    <td class="px-3 py-2 text-red-600">{{ $error['error'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Format Info -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 font-semibold mb-2">Format file yang didukung:</p>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li><i class="fas fa-file-excel mr-2"></i>Excel (.xlsx, .xls) - <span
                                    class="font-bold">Disarankan</span></li>
                            <li><i class="fas fa-file-csv mr-2"></i>CSV (Comma Separated Values)</li>
                        </ul>
                        <p class="text-sm text-blue-700 mt-3">
                            Kolom wajib: <code class="bg-blue-100 px-2 py-1 rounded">name, email</code><br>
                            Kolom opsional: <code class="bg-blue-100 px-2 py-1 rounded">password, role, phone, department,
                                position</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Download Template -->
            <div class="mb-6">
                <div class="flex gap-3">
                    <a href="{{ route('admin.users.import.template') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-download mr-2"></i>Download Template Excel
                    </a>
                    <a href="#" onclick="showSampleData()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-eye mr-2"></i>Lihat Contoh Data
                    </a>
                </div>
                <p class="text-sm text-gray-500 mt-2">Gunakan template ini untuk memastikan format benar</p>
            </div>

            <!-- Import Form -->
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf

                <div class="space-y-6">
                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih File <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input id="file" name="file" type="file" class="sr-only"
                                            accept=".csv,.xlsx,.xls" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">Excel atau CSV hingga 10MB</p>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview -->
                    <div id="filePreview" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File yang akan diupload:</label>
                        <div class="bg-gray-50 p-4 rounded-lg flex items-center justify-between">
                            <div>
                                <p id="fileName" class="text-sm text-gray-800 font-medium"></p>
                                <p id="fileSize" class="text-xs text-gray-500 mt-1"></p>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Import Options -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Opsi Import:</h4>
                        <div class="space-y-4">
                            <!-- Action Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Import:</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative">
                                        <input type="radio" name="action" value="both" class="sr-only peer"
                                            checked>
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition text-center">
                                            <i class="fas fa-sync-alt text-blue-600 mb-1"></i>
                                            <p class="text-sm font-medium">Insert & Update</p>
                                            <p class="text-xs text-gray-500">Buat baru & update yang ada</p>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="action" value="insert" class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition text-center">
                                            <i class="fas fa-plus-circle text-green-600 mb-1"></i>
                                            <p class="text-sm font-medium">Insert Only</p>
                                            <p class="text-xs text-gray-500">Hanya tambah user baru</p>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="action" value="update" class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition text-center">
                                            <i class="fas fa-edit text-yellow-600 mb-1"></i>
                                            <p class="text-sm font-medium">Update Only</p>
                                            <p class="text-xs text-gray-500">Hanya update user yang ada</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Other Options -->
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="skip_errors" name="skip_errors" type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                                    <label for="skip_errors" class="ml-2 block text-sm text-gray-700">
                                        Lewati baris dengan error dan lanjutkan import
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="send_email" name="send_email" type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="send_email" class="ml-2 block text-sm text-gray-700">
                                        Kirim email notifikasi ke user baru
                                    </label>
                                </div>
                                @if (config('queue.default') !== 'sync')
                                    <div class="flex items-center">
                                        <input id="sync" name="sync" type="checkbox"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="sync" class="ml-2 block text-sm text-gray-700">
                                            Proses langsung (tanpa queue)
                                            <span class="text-xs text-gray-500">Untuk file kecil (&lt;100 data)</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar (hidden by default) -->
                    <div id="progressContainer" class="hidden">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Sedang memproses...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p id="progressMessage" class="text-xs text-gray-500 mt-2 text-center"></p>
                    </div>

                    <!-- Button Actions -->
                    <div class="flex gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.users') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex-1 flex items-center justify-center">
                            <i class="fas fa-upload mr-2"></i>
                            <span id="submitText">Mulai Import</span>
                            <span id="loadingSpinner" class="hidden ml-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sample Data Modal -->
    <div id="sampleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Contoh Data Import</h3>
                <button onclick="hideSampleData()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                password</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">role
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                phone</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">John Doe</td>
                            <td class="px-4 py-3 text-sm text-gray-900">john@example.com</td>
                            <td class="px-4 py-3 text-sm text-gray-900">password123</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">user</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">081234567890</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">Jane Smith</td>
                            <td class="px-4 py-3 text-sm text-gray-900">jane@example.com</td>
                            <td class="px-4 py-3 text-sm text-gray-900">jane123</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">admin</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">081234567891</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">Bob Wilson</td>
                            <td class="px-4 py-3 text-sm text-gray-900">bob@example.com</td>
                            <td class="px-4 py-3 text-sm text-gray-900">bobpass123</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">manager</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">081234567892</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Catatan:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Kolom <code>name</code> dan
                            <code>email</code> wajib diisi</li>
                        <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Jika password tidak diisi, akan dibuat
                            random</li>
                        <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Role default adalah "user"</li>
                        <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Email duplikat akan diupdate</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // File preview
        document.getElementById('file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');

            if (file) {
                preview.classList.remove('hidden');
                fileName.textContent = file.name;
                fileSize.textContent = `Size: ${(file.size / 1024).toFixed(2)} KB`;
            }
        });

        function clearFile() {
            document.getElementById('file').value = '';
            document.getElementById('filePreview').classList.add('hidden');
        }

        // Sample data modal
        function showSampleData() {
            document.getElementById('sampleModal').classList.remove('hidden');
            document.getElementById('sampleModal').classList.add('flex');
        }

        function hideSampleData() {
            document.getElementById('sampleModal').classList.add('hidden');
            document.getElementById('sampleModal').classList.remove('flex');
        }

        // Form submission with progress
        document.getElementById('importForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const progressMessage = document.getElementById('progressMessage');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            loadingSpinner.classList.remove('hidden');

            // Show progress bar
            progressContainer.classList.remove('hidden');

            // Simulate progress (in real app, you'd use WebSocket or polling)
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 10;
                if (progress > 90) progress = 90;

                progressBar.style.width = `${progress}%`;
                progressPercent.textContent = `${Math.round(progress)}%`;

                if (progress < 30) {
                    progressMessage.textContent = 'Membaca file...';
                } else if (progress < 60) {
                    progressMessage.textContent = 'Memvalidasi data...';
                } else {
                    progressMessage.textContent = 'Menyimpan data...';
                }
            }, 500);

            // Clear interval on page unload (if user navigates away)
            window.addEventListener('beforeunload', function() {
                clearInterval(interval);
            });

            // For demo purposes, clear interval after 5 seconds
            setTimeout(() => clearInterval(interval), 5000);
        });

        // Prevent closing modal when clicking inside
        document.getElementById('sampleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideSampleData();
            }
        });
    </script>
@endpush
