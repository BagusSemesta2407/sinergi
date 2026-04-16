<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin WFA</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }
        
        /* Sidebar Styling */
        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            width: var(--sidebar-width);
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        @media (min-width: 1024px) {
            .sidebar.collapsed {
                transform: translateX(0);
            }
            .sidebar-toggle {
                display: none;
            }
        }
        
        .sidebar-item {
            position: relative;
            transition: all 0.2s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }
        
        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.15);
        }
        
        .sidebar-item.active::before {
            transform: scaleY(1);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @media (min-width: 1024px) {
            .main-content {
                margin-left: var(--sidebar-width);
            }
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.5);
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            border-color: rgba(59, 130, 246, 0.2);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: #64748b;
        }
        
        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(59, 130, 246, 0.05);
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            padding: 16px 20px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }
        
        .data-table tbody tr {
            transition: all 0.2s ease;
        }
        
        .data-table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.002);
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            gap: 4px;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .badge-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Progress bars */
        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        /* Loading spinner */
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card {
                border-radius: 12px;
            }
            
            .btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full">
    <!-- Mobile Sidebar Toggle -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="sidebarToggle" class="sidebar-toggle w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center text-gray-700 hover:text-primary-color transition">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-check text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">WFA Admin</h1>
                    <p class="text-blue-200 text-sm">Work From Anywhere</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-item flex items-center gap-3 px-4 py-3 text-gray-300 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-blue-400"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.users') }}" 
               class="sidebar-item flex items-center gap-3 px-4 py-3 text-gray-300 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 text-green-400"></i>
                <span class="font-medium">Manajemen User</span>
            </a>

            <a href="{{ route('admin.attendance') }}" 
               class="sidebar-item flex items-center gap-3 px-4 py-3 text-gray-300 {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check w-5 text-purple-400"></i>
                <span class="font-medium">Data Kehadiran</span>
            </a>

            <a href="{{ route('admin.sessions') }}" 
               class="sidebar-item flex items-center gap-3 px-4 py-3 text-gray-300 {{ request()->routeIs('admin.sessions*') ? 'active' : '' }}">
                <i class="fas fa-clock w-5 text-yellow-400"></i>
                <span class="font-medium">Sesi Absensi</span>
            </a>

            <a href="{{ route('admin.reports') }}" 
               class="sidebar-item flex items-center gap-3 px-4 py-3 text-gray-300 {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-5 text-red-400"></i>
                <span class="font-medium">Laporan & Analitik</span>
            </a>
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-800">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-blue-300 text-xs">Administrator</p>
                </div>
                <button id="userMenuToggle" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            
            <!-- User Dropdown -->
            <div id="userMenu" class="hidden bg-gray-800 rounded-lg p-2 space-y-1 mt-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-gray-300 hover:bg-gray-700 rounded transition">
                    <i class="fas fa-arrow-left w-4"></i>
                    <span class="text-sm">Kembali ke User View</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-red-300 hover:bg-gray-700 rounded transition text-left">
                        <i class="fas fa-sign-out-alt w-4"></i>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Header -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-200">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        @yield('page-title', 'Dashboard')
                    </h2>
                    <p class="text-gray-600 text-sm">
                        @yield('page-description', 'Sistem Administrasi Absensi WFA')
                    </p>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Date & Time -->
                    <div class="hidden md:block text-right">
                        <p class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</p>
                        <p id="admin-time" class="font-bold text-gray-800 text-lg">{{ now()->format('H:i:s') }}</p>
                    </div>

                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notificationBtn" class="relative p-2 text-gray-600 hover:text-primary-color transition">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-40">
                            <div class="p-4 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                                <p class="text-sm text-gray-500">Anda memiliki 3 notifikasi baru</p>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <!-- Notification items would go here -->
                                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition">
                                    <div class="flex gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user-check text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">User baru terdaftar</p>
                                            <p class="text-xs text-gray-500">2 menit yang lalu</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-100">
                                <a href="#" class="text-primary-color text-sm font-medium hover:underline">
                                    Lihat semua notifikasi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Menu -->
                    <div class="hidden md:block">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i>
                                <span class="hidden lg:inline">Tambah User</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Notifications -->
        <div class="px-6 pt-6">
            @if (session('success'))
                <div class="mb-6 fade-in">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" 
                                    class="text-green-600 hover:text-green-800 transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 fade-in">
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-circle text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" 
                                    class="text-red-600 hover:text-red-800 transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <main class="p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="px-6 py-4 border-t border-gray-200 bg-white">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-600 text-sm">
                    &copy; {{ date('Y') }} Absensi WFA - Admin Panel v2.0
                </p>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">
                        {{ config('app.name') }}
                    </span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-server mr-1"></i>
                        Laravel {{ app()->version() }}
                    </span>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Main Script -->
    <script>
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin Panel initialized');
            
            // Sidebar Toggle for Mobile
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    sidebarOverlay.classList.toggle('hidden');
                });
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.add('collapsed');
                    sidebarOverlay.classList.add('hidden');
                });
            }
            
            // User Menu Toggle
            const userMenuToggle = document.getElementById('userMenuToggle');
            const userMenu = document.getElementById('userMenu');
            
            if (userMenuToggle && userMenu) {
                userMenuToggle.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
                
                // Close user menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuToggle.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
            
            // Notification Dropdown
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (notificationBtn && notificationDropdown) {
                notificationBtn.addEventListener('click', function(event) {
                    event.stopPropagation();
                    notificationDropdown.classList.toggle('hidden');
                });
                
                // Close notification dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!notificationBtn.contains(event.target) && !notificationDropdown.contains(event.target)) {
                        notificationDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Update time
            function updateTime() {
                const now = new Date();
                const timeElement = document.getElementById('admin-time');
                if (timeElement) {
                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    timeElement.textContent = `${hours}:${minutes}:${seconds}`;
                }
            }
            
            // Initialize time
            updateTime();
            setInterval(updateTime, 1000);
            
            // Auto-hide notifications after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('[class*="bg-gradient-to-r"]').forEach(notification => {
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(-20px)';
                        setTimeout(() => notification.remove(), 300);
                    }, 5000);
                });
            }, 1000);
            
            // Initialize DataTables with responsive feature
            if ($.fn.DataTable) {
                $('.data-table').DataTable({
                    responsive: true,
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                    },
                    pageLength: 25,
                    order: [[0, "desc"]],
                    dom: '<"flex flex-col md:flex-row md:items-center justify-between mb-4"<"mb-4 md:mb-0"l><"flex gap-2"f>>rt<"flex flex-col md:flex-row md:items-center justify-between mt-4"<"mb-4 md:mb-0"i><"flex gap-2"p>>',
                    drawCallback: function(settings) {
                        // Re-initialize tooltips or other plugins after table redraw
                        console.log('Table redrawn');
                    }
                });
            }
            
            // Smooth scroll to top function
            window.scrollToTop = function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            };
            
            // Add scroll to top button
            const scrollToTopBtn = document.createElement('button');
            scrollToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
            scrollToTopBtn.className = 'fixed bottom-6 right-6 w-12 h-12 bg-primary-color text-white rounded-full shadow-lg flex items-center justify-center hover:bg-primary-dark transition transform hover:-translate-y-1 z-40 hidden';
            scrollToTopBtn.id = 'scrollToTopBtn';
            document.body.appendChild(scrollToTopBtn);
            
            // Show/hide scroll to top button
            window.addEventListener('scroll', function() {
                const btn = document.getElementById('scrollToTopBtn');
                if (window.scrollY > 300) {
                    btn.classList.remove('hidden');
                } else {
                    btn.classList.add('hidden');
                }
            });
            
            // Scroll to top button click handler
            document.getElementById('scrollToTopBtn').addEventListener('click', scrollToTop);
            
            // Confirmation dialogs
            window.confirmAction = function(message, callback) {
                if (confirm(message)) {
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            };
            
            // Loading state for buttons
            document.addEventListener('submit', function(event) {
                const form = event.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                
                if (submitBtn && !form.classList.contains('prevent-loading')) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                    submitBtn.disabled = true;
                }
            });
            
            // Add animation to cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, observerOptions);
            
            // Observe all cards
            document.querySelectorAll('.card').forEach(card => {
                observer.observe(card);
            });
            
            // Print functionality
            window.printPage = function() {
                window.print();
            };
            
            // Export functionality
            window.exportData = function(format) {
                alert(`Export data dalam format ${format} akan segera diimplementasikan.`);
            };
        });
        
        // Global error handler
        window.addEventListener('error', function(event) {
            console.error('Global error:', event.error);
        });
        
        // Handle page visibility changes
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                console.log('Page is now visible');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>