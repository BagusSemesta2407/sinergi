@extends('layouts.admin')

@section('title', 'Detail Kehadiran')
@section('page-title', 'Detail Kehadiran')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.attendance') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Data Kehadiran
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-2xl">{{ substr($absensi->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $absensi->user->name }}</h2>
                            <p class="text-gray-600">{{ $absensi->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-800">{{ $absensi->tanggal->format('d M Y') }}</div>
                        <div class="text-gray-600">{{ $absensi->tanggal->format('l') }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-6">
                <!-- Status Badge -->
                <div class="mb-8">
                    @php
                        $statusColors = [
                            'tepat_waktu' => 'bg-green-100 text-green-800 border-green-200',
                            'terlambat' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'cepat' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'tidak_absen' => 'bg-red-100 text-red-800 border-red-200'
                        ];
                    @endphp
                    <div class="inline-flex items-center px-4 py-2 rounded-lg border {{ $statusColors[$absensi->status_masuk] }}">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="font-medium">Status: {{ $absensi->status_label }}</span>
                    </div>
                </div>

                <!-- Maps Section -->
                @if($absensi->lokasi_masuk || $absensi->lokasi_pulang)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Lokasi Absensi</h3>
                        
                        <!-- Tabs untuk Multiple Maps -->
                        @if($absensi->lokasi_masuk && $absensi->lokasi_pulang)
                            <div class="border-b border-gray-200 mb-4">
                                <nav class="-mb-px flex space-x-8">
                                    <button onclick="showMapTab('masuk')" 
                                            id="tab-masuk"
                                            class="map-tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-blue-500 text-blue-600">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Lokasi Masuk
                                    </button>
                                    
                                    <button onclick="showMapTab('pulang')" 
                                            id="tab-pulang"
                                            class="map-tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Lokasi Pulang
                                    </button>
                                    
                                    <button onclick="showMapTab('both')" 
                                            id="tab-both"
                                            class="map-tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        <i class="fas fa-exchange-alt mr-2"></i>Kedua Lokasi
                                    </button>
                                </nav>
                            </div>
                        @endif
                        
                        <!-- Tab Content -->
                        <div class="space-y-6">
                            <!-- Single Map for Masuk -->
                            @if($absensi->lokasi_masuk && !$absensi->lokasi_pulang)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                        Lokasi Masuk
                                    </h4>
                                    <div id="mapMasuk" class="h-96 rounded-lg border border-gray-300"></div>
                                    <p class="text-sm text-gray-600 mt-2 p-2 bg-gray-50 rounded">
                                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                        {{ $absensi->lokasi_masuk }}
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Single Map for Pulang -->
                            @if($absensi->lokasi_pulang && !$absensi->lokasi_masuk)
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                        Lokasi Pulang
                                    </h4>
                                    <div id="mapPulang" class="h-96 rounded-lg border border-gray-300"></div>
                                    <p class="text-sm text-gray-600 mt-2 p-2 bg-gray-50 rounded">
                                        <i class="fas fa-info-circle mr-1 text-green-500"></i>
                                        {{ $absensi->lokasi_pulang }}
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Tabbed Maps -->
                            @if($absensi->lokasi_masuk && $absensi->lokasi_pulang)
                                <div id="map-content-masuk" class="map-content">
                                    <div id="mapMasuk" class="h-96 rounded-lg border border-gray-300"></div>
                                    <p class="text-sm text-gray-600 mt-2 p-2 bg-gray-50 rounded">
                                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                                        {{ $absensi->lokasi_masuk }}
                                    </p>
                                </div>
                                
                                <div id="map-content-pulang" class="map-content hidden">
                                    <div id="mapPulang" class="h-96 rounded-lg border border-gray-300"></div>
                                    <p class="text-sm text-gray-600 mt-2 p-2 bg-gray-50 rounded">
                                        <i class="fas fa-info-circle mr-1 text-green-500"></i>
                                        {{ $absensi->lokasi_pulang }}
                                    </p>
                                </div>
                                
                                <div id="map-content-both" class="map-content hidden">
                                    <div id="mapBoth" class="h-96 rounded-lg border border-gray-300"></div>
                                    <div class="grid grid-cols-2 gap-4 mt-2">
                                        <div class="p-2 bg-blue-50 rounded">
                                            <p class="text-sm font-medium text-blue-600">
                                                <i class="fas fa-sign-in-alt mr-1"></i>Lokasi Masuk
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $absensi->lokasi_masuk }}</p>
                                        </div>
                                        <div class="p-2 bg-green-50 rounded">
                                            <p class="text-sm font-medium text-green-600">
                                                <i class="fas fa-sign-out-alt mr-1"></i>Lokasi Pulang
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $absensi->lokasi_pulang }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if(!$absensi->lokasi_masuk && !$absensi->lokasi_pulang)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                                <i class="fas fa-map-marked-alt text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Data lokasi tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Attendance Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Time Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Waktu</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Jam Masuk</span>
                                <span class="font-medium text-gray-800">
                                    {{ $absensi->jam_masuk ? $absensi->jam_masuk->format('H:i') : '-' }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Jam Pulang</span>
                                <span class="font-medium text-gray-800">
                                    {{ $absensi->jam_pulang ? $absensi->jam_pulang->format('H:i') : 'Belum pulang' }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Sesi Absensi</span>
                                <span class="font-medium text-gray-800">
                                    {{ $absensi->sesiAbsensi->nama_sesi ?? '-' }}
                                    @if($absensi->sesiAbsensi)
                                        <span class="text-gray-500 text-sm">
                                            ({{ $absensi->sesiAbsensi->jam_mulai->format('H:i') }} - {{ $absensi->sesiAbsensi->jam_selesai->format('H:i') }})
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Location & Device Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Lain</h3>
                        
                        <div class="space-y-3">
                            @if($absensi->lokasi_masuk)
                                <div>
                                    <span class="text-gray-600 block mb-1">Koordinat Masuk</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $absensi->lokasi_masuk }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($absensi->lokasi_pulang)
                                <div>
                                    <span class="text-gray-600 block mb-1">Koordinat Pulang</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $absensi->lokasi_pulang }}
                                    </span>
                                </div>
                            @endif
                            
                            <div>
                                <span class="text-gray-600 block mb-1">IP Address</span>
                                <span class="font-medium text-gray-800">
                                    {{ $absensi->ip_address ?? '-' }}
                                </span>
                            </div>
                            
                            @if($absensi->user_agent)
                                <div>
                                    <span class="text-gray-600 block mb-1">Device & Browser</span>
                                    <span class="font-medium text-gray-800 text-sm">
                                        {{ $absensi->user_agent }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proof of Work -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Bukti Pekerjaan</h3>
                    
                    @if($absensi->bukti_diupload)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center text-green-700 mb-1">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span class="font-medium">Bukti sudah diupload</span>
                                    </div>
                                    <p class="text-sm text-green-600">
                                        Waktu upload: {{ $absensi->jam_upload_bukti->format('H:i') }}
                                    </p>
                                </div>
                                
                                @if($absensi->bukti_pekerjaan)
                                    <a href="{{ $absensi->bukti_url }}" 
                                       target="_blank"
                                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                @endif
                            </div>
                            
                            @if($absensi->catatan)
                                <div class="mt-4 p-3 bg-white rounded border">
                                    <p class="text-sm text-gray-700">{{ $absensi->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-medium">Belum upload bukti pekerjaan</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Admin Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Catatan Admin</h3>
                    
                    <form action="{{ route('admin.attendance.update', $absensi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <!-- Time Adjustment -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                                    <input type="time" 
                                           name="jam_masuk" 
                                           value="{{ old('jam_masuk', $absensi->jam_masuk ? $absensi->jam_masuk->format('H:i') : '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pulang</label>
                                    <input type="time" 
                                           name="jam_pulang" 
                                           value="{{ old('jam_pulang', $absensi->jam_pulang ? $absensi->jam_pulang->format('H:i') : '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status_masuk" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="tepat_waktu" {{ old('status_masuk', $absensi->status_masuk) == 'tepat_waktu' ? 'selected' : '' }}>
                                        Tepat Waktu
                                    </option>
                                    <option value="terlambat" {{ old('status_masuk', $absensi->status_masuk) == 'terlambat' ? 'selected' : '' }}>
                                        Terlambat
                                    </option>
                                    <option value="tidak_absen" {{ old('status_masuk', $absensi->status_masuk) == 'tidak_absen' ? 'selected' : '' }}>
                                        Tidak Absen
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Admin Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                                <textarea name="catatan_admin" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                          placeholder="Tambahkan catatan jika perlu">{{ old('catatan_admin', $absensi->catatan_admin) }}</textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Leaflet Map Styling */
    .leaflet-container {
        height: 100%;
        width: 100%;
        border-radius: 0.5rem;
        z-index: 1;
    }
    
    .leaflet-popup-content {
        margin: 12px 15px !important;
        font-family: 'Inter', sans-serif;
    }
    
    .leaflet-popup-content-wrapper {
        border-radius: 0.5rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    
    /* Custom markers */
    .custom-marker-blue {
        background-color: #3B82F6;
        border: 3px solid white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    .custom-marker-green {
        background-color: #10B981;
        border: 3px solid white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    /* Map tab styling */
    .map-tab {
        transition: all 0.2s ease;
    }
    
    .map-content {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Legend for combined map */
    .map-legend {
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        font-size: 12px;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Parse location string to get coordinates
        function parseLocation(locationString) {
            if (!locationString) return null;
            
            // Format: "latitude, longitude" atau "latitude,longitude"
            const parts = locationString.split(',');
            if (parts.length >= 2) {
                const lat = parseFloat(parts[0].trim());
                const lng = parseFloat(parts[1].trim());
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    return [lat, lng];
                }
            }
            return null;
        }
        
        // Get address from coordinates using Nominatim (reverse geocoding)
        function getAddressFromCoords(lat, lng, callback) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        callback(data.display_name);
                    } else {
                        callback(null);
                    }
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                    callback(null);
                });
        }
        
        // Create single OpenStreetMap
        function createOSMMap(elementId, location, title, color) {
            const mapElement = document.getElementById(elementId);
            if (!mapElement || !location) return null;
            
            const coordinates = parseLocation(location);
            
            // Clear previous content
            mapElement.innerHTML = '<div class="h-full flex items-center justify-center bg-gray-100"><i class="fas fa-spinner fa-spin text-blue-500"></i></div>';
            
            if (!coordinates) {
                // If not coordinates, try to geocode address
                setTimeout(() => {
                    geocodeAddress(location, elementId, title, color);
                }, 100);
                return null;
            }
            
            try {
                // Create map
                const map = L.map(elementId).setView(coordinates, 15);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19,
                }).addTo(map);
                
                // Create custom icon
                const iconHtml = `<div class="custom-marker-${color === '#3B82F6' ? 'blue' : 'green'}" style="background-color: ${color};"></div>`;
                const customIcon = L.divIcon({
                    html: iconHtml,
                    iconSize: [20, 20],
                    className: 'custom-marker'
                });
                
                // Add marker
                const marker = L.marker(coordinates, { 
                    icon: customIcon,
                    title: title
                }).addTo(map);
                
                // Get address for popup
                getAddressFromCoords(coordinates[0], coordinates[1], (address) => {
                    const popupContent = address ? 
                        `<strong>${title}</strong><br>
                         <small>${address}</small><br>
                         <small class="text-gray-500">Koordinat: ${coordinates[0].toFixed(6)}, ${coordinates[1].toFixed(6)}</small>` :
                        `<strong>${title}</strong><br>
                         <small>Koordinat: ${coordinates[0].toFixed(6)}, ${coordinates[1].toFixed(6)}</small>`;
                    
                    marker.bindPopup(popupContent).openPopup();
                });
                
                // Add scale control
                L.control.scale({ imperial: false }).addTo(map);
                
                return map;
                
            } catch (error) {
                console.error('Error creating OSM map:', error);
                mapElement.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center bg-gray-100 p-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                        <p class="text-red-600 text-sm text-center">Error memuat peta</p>
                    </div>
                `;
                return null;
            }
        }
        
        // Geocode address using Nominatim
        function geocodeAddress(address, elementId, title, color) {
            if (!address) return;
            
            const mapElement = document.getElementById(elementId);
            
            // Show loading
            mapElement.innerHTML = '<div class="h-full flex items-center justify-center bg-gray-100"><i class="fas fa-spinner fa-spin text-blue-500"></i></div>';
            
            // Use Nominatim API (OpenStreetMap geocoding)
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        
                        const map = L.map(elementId).setView([lat, lon], 15);
                        
                        // Add tiles
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        
                        // Custom icon
                        const iconHtml = `<div class="custom-marker-${color === '#3B82F6' ? 'blue' : 'green'}" style="background-color: ${color};"></div>`;
                        const customIcon = L.divIcon({
                            html: iconHtml,
                            iconSize: [20, 20]
                        });
                        
                        // Add marker
                        L.marker([lat, lon], { 
                            icon: customIcon,
                            title: title
                        })
                        .addTo(map)
                        .bindPopup(`
                            <strong>${title}</strong><br>
                            <small>${address}</small><br>
                            <small class="text-gray-500">Koordinat: ${lat.toFixed(6)}, ${lon.toFixed(6)}</small>
                        `)
                        .openPopup();
                        
                        // Add scale
                        L.control.scale({ imperial: false }).addTo(map);
                        
                    } else {
                        mapElement.innerHTML = `
                            <div class="h-full flex flex-col items-center justify-center bg-gray-100 p-4">
                                <i class="fas fa-map-marker-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="text-gray-500 text-sm text-center">Alamat tidak ditemukan di peta</p>
                                <p class="text-gray-600 text-xs mt-1">${address}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Geocoding error:', error);
                    mapElement.innerHTML = `
                        <div class="h-full flex flex-col items-center justify-center bg-gray-100 p-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                            <p class="text-red-600 text-sm text-center">Error memuat peta</p>
                        </div>
                    `;
                });
        }
        
        // Create combined map showing both locations
        function createCombinedMap(elementId, locationMasuk, locationPulang) {
            const mapElement = document.getElementById(elementId);
            if (!mapElement) return;
            
            const coordsMasuk = parseLocation(locationMasuk);
            const coordsPulang = parseLocation(locationPulang);
            
            if (!coordsMasuk && !coordsPulang) {
                mapElement.innerHTML = '<div class="h-full flex items-center justify-center bg-gray-100 text-gray-500">Tidak ada koordinat yang valid</div>';
                return;
            }
            
            try {
                let map;
                const bounds = L.latLngBounds();
                
                // Initialize map with first available coordinates
                if (coordsMasuk) {
                    map = L.map(elementId).setView(coordsMasuk, 12);
                    bounds.extend(coordsMasuk);
                } else if (coordsPulang) {
                    map = L.map(elementId).setView(coordsPulang, 12);
                    bounds.extend(coordsPulang);
                }
                
                // Add tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                
                // Add masuk marker if exists
                if (coordsMasuk) {
                    const blueIcon = L.divIcon({
                        html: '<div class="custom-marker-blue"></div>',
                        iconSize: [20, 20]
                    });
                    
                    const masukMarker = L.marker(coordsMasuk, {
                        icon: blueIcon,
                        title: 'Lokasi Masuk'
                    }).addTo(map);
                    
                    getAddressFromCoords(coordsMasuk[0], coordsMasuk[1], (address) => {
                        masukMarker.bindPopup(`
                            <strong>📍 Lokasi Masuk</strong><br>
                            <small>${address || 'Lokasi masuk'}</small><br>
                            <small class="text-blue-500">${coordsMasuk[0].toFixed(6)}, ${coordsMasuk[1].toFixed(6)}</small>
                        `);
                    });
                }
                
                // Add pulang marker if exists
                if (coordsPulang) {
                    const greenIcon = L.divIcon({
                        html: '<div class="custom-marker-green"></div>',
                        iconSize: [20, 20]
                    });
                    
                    const pulangMarker = L.marker(coordsPulang, {
                        icon: greenIcon,
                        title: 'Lokasi Pulang'
                    }).addTo(map);
                    
                    getAddressFromCoords(coordsPulang[0], coordsPulang[1], (address) => {
                        pulangMarker.bindPopup(`
                            <strong>📍 Lokasi Pulang</strong><br>
                            <small>${address || 'Lokasi pulang'}</small><br>
                            <small class="text-green-500">${coordsPulang[0].toFixed(6)}, ${coordsPulang[1].toFixed(6)}</small>
                        `);
                    });
                    
                    if (coordsMasuk) {
                        bounds.extend(coordsPulang);
                    }
                }
                
                // Fit bounds if we have both markers
                if (coordsMasuk && coordsPulang) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                    
                    // Add polyline connecting both points
                    L.polyline([coordsMasuk, coordsPulang], {
                        color: '#6B7280',
                        weight: 2,
                        opacity: 0.7,
                        dashArray: '5, 10'
                    }).addTo(map);
                    
                    // Add legend
                    const legend = L.control({ position: 'bottomleft' });
                    legend.onAdd = function(map) {
                        const div = L.DomUtil.create('div', 'map-legend');
                        div.innerHTML = `
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <div style="width: 12px; height: 12px; background-color: #3B82F6; border-radius: 50%; margin-right: 8px;"></div>
                                <span style="font-size: 12px;">Lokasi Masuk</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 12px; height: 12px; background-color: #10B981; border-radius: 50%; margin-right: 8px;"></div>
                                <span style="font-size: 12px;">Lokasi Pulang</span>
                            </div>
                        `;
                        return div;
                    };
                    legend.addTo(map);
                }
                
                // Add scale control
                L.control.scale({ imperial: false }).addTo(map);
                
            } catch (error) {
                console.error('Error creating combined map:', error);
                mapElement.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center bg-gray-100 p-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                        <p class="text-red-600 text-sm text-center">Error memuat peta</p>
                    </div>
                `;
            }
        }
        
        // Tab switching function
        window.showMapTab = function(tabName) {
            // Hide all map contents
            document.querySelectorAll('.map-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active style from all tab buttons
            document.querySelectorAll('.map-tab').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Show selected tab content
            document.getElementById(`map-content-${tabName}`).classList.remove('hidden');
            
            // Add active style to selected tab button
            document.getElementById(`tab-${tabName}`).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            document.getElementById(`tab-${tabName}`).classList.add('border-blue-500', 'text-blue-600');
            
            // Initialize map if needed
            if (tabName === 'masuk') {
                setTimeout(() => {
                    if (!window.masukMap) {
                        window.masukMap = createOSMMap('mapMasuk', '{{ $absensi->lokasi_masuk }}', 'Lokasi Masuk', '#3B82F6');
                    }
                }, 100);
            } else if (tabName === 'pulang') {
                setTimeout(() => {
                    if (!window.pulangMap) {
                        window.pulangMap = createOSMMap('mapPulang', '{{ $absensi->lokasi_pulang }}', 'Lokasi Pulang', '#10B981');
                    }
                }, 100);
            } else if (tabName === 'both') {
                setTimeout(() => {
                    createCombinedMap('mapBoth', '{{ $absensi->lokasi_masuk }}', '{{ $absensi->lokasi_pulang }}');
                }, 100);
            }
        };
        
        // Initialize maps based on available locations
        @if($absensi->lokasi_masuk && $absensi->lokasi_pulang)
            // If both locations exist, initialize masuk map first
            setTimeout(() => {
                window.masukMap = createOSMMap('mapMasuk', '{{ $absensi->lokasi_masuk }}', 'Lokasi Masuk', '#3B82F6');
            }, 500);
        @elseif($absensi->lokasi_masuk)
            // Only masuk location
            setTimeout(() => {
                createOSMMap('mapMasuk', '{{ $absensi->lokasi_masuk }}', 'Lokasi Masuk', '#3B82F6');
            }, 500);
        @elseif($absensi->lokasi_pulang)
            // Only pulang location
            setTimeout(() => {
                createOSMMap('mapPulang', '{{ $absensi->lokasi_pulang }}', 'Lokasi Pulang', '#10B981');
            }, 500);
        @endif
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.masukMap) window.masukMap.invalidateSize();
                if (window.pulangMap) window.pulangMap.invalidateSize();
            }, 250);
        });
        
        // Helper function to format coordinates
        function formatCoordinates(coordString) {
            if (!coordString) return '';
            const coords = parseLocation(coordString);
            if (coords) {
                return `${coords[0].toFixed(6)}, ${coords[1].toFixed(6)}`;
            }
            return coordString;
        }
    });
</script>
@endpush