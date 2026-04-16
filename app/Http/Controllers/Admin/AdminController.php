<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use App\Imports\UserImport;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $todayAttendance = Absensi::whereDate('tanggal', Carbon::today())->count();
        $activeSession = SesiAbsensi::where('aktif', true)->first();

        // Statistik bulan ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyAttendance = Absensi::whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->count();

        // User dengan absensi terbanyak bulan ini
        $topUsers = User::where('role', 'user')
            ->withCount(['absensi' => function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('tanggal', $currentYear)
                    ->whereMonth('tanggal', $currentMonth);
            }])
            ->orderBy('absensi_count', 'desc')
            ->limit(5)
            ->get();

        // Data untuk chart
        $attendanceByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Absensi::whereDate('tanggal', $date)->count();
            $attendanceByDay[] = [
                'day' => $date->format('D'),
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'todayAttendance',
            'activeSession',
            'monthlyAttendance',
            'topUsers',
            'attendanceByDay'
        ));
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query();

        // Search - PERBAIKAN: gunakan where dengan lebih baik
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status - PERBAIKAN
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', 'active'); // atau 'active' tergantung kolom
            } elseif ($request->status == 'inactive') {
                $query->where('status', 'inactive'); // atau 'inactive'
            }
        }

        // Urutkan dan paginate dengan mempertahankan parameter
        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->all()); // ✅ PENTING: ini menjaga parameter saat pagination

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        // Jangan biarkan admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus.');
    }

    // Attendance Management
    public function attendance(Request $request)
    {
        $query = Absensi::with('user', 'sesiAbsensi');

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        } elseif ($request->has('date')) {
            $query->whereDate('tanggal', $request->date);
        } else {
            // Default: 30 hari terakhir
            $query->where('tanggal', '>=', Carbon::today()->subDays(30));
        }

        // Filter berdasarkan user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan status
        if ($request->has('status_masuk')) {
            $query->where('status_masuk', $request->status_masuk);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $attendance = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $users = User::where('role', 'user')->get();

        return view('admin.attendance.index', compact('attendance', 'users'));
    }

    public function attendanceDetail(Absensi $absensi)
    {
        // Get address for coordinates if available
        $locationDetails = [];

        if ($absensi->lokasi_masuk && strpos($absensi->lokasi_masuk, ',') !== false) {
            $coords = explode(',', $absensi->lokasi_masuk);
            if (count($coords) >= 2) {
                $lat = trim($coords[0]);
                $lng = trim($coords[1]);
                if (is_numeric($lat) && is_numeric($lng)) {
                    // Cache or get address from coordinates
                    $locationDetails['masuk'] = [
                        'coordinates' => [$lat, $lng],
                        'formatted' => number_format($lat, 6) . ', ' . number_format($lng, 6)
                    ];
                }
            }
        }

        if ($absensi->lokasi_pulang && strpos($absensi->lokasi_pulang, ',') !== false) {
            $coords = explode(',', $absensi->lokasi_pulang);
            if (count($coords) >= 2) {
                $lat = trim($coords[0]);
                $lng = trim($coords[1]);
                if (is_numeric($lat) && is_numeric($lng)) {
                    $locationDetails['pulang'] = [
                        'coordinates' => [$lat, $lng],
                        'formatted' => number_format($lat, 6) . ', ' . number_format($lng, 6)
                    ];
                }
            }
        }

        return view('admin.attendance.detail', compact('absensi', 'locationDetails'));
    }

    public function updateAttendance(Request $request, Absensi $absensi)
    {
        $request->validate([
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i|after:jam_masuk',
            'status_masuk' => 'required|in:tepat_waktu,terlambat,tidak_absen',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $absensi->update([
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'status_masuk' => $request->status_masuk,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->back()
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    // Export Data
    public function exportAttendance(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:excel,pdf',
        ]);

        $fileName = 'absensi-' . date('Y-m-d') . '.' .
            ($request->format == 'excel' ? 'xlsx' : 'pdf');

        if ($request->format == 'excel') {
            return Excel::download(new AbsensiExport(
                $request->start_date,
                $request->end_date,
                $request->user_id
            ), $fileName);
        }

        // Untuk PDF, Anda bisa menggunakan library seperti dompdf
        // return (new AbsensiExport($request->start_date, $request->end_date))->download($fileName);

        return redirect()->back()
            ->with('error', 'Export PDF belum tersedia.');
    }

    // Import Users
    public function importUsersForm()
    {
        return view('admin.users.import');
    }

    /**
     * Proses import (VERSI SIMPEL)
     */
    public function importUsers(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // Ambil parameter skip errors dari form
            $skipErrors = $request->has('skip_errors');

            // Buat instance import
            $import = new UserImport($skipErrors);

            // Proses import
            Excel::import($import, $request->file('file'));

            // Ambil hasil
            $imported = $import->getImportedCount();
            $updated = $import->getUpdatedCount();
            $errors = $import->getErrors();

            // Buat pesan
            $message = "Import selesai! ";
            if ($imported > 0) $message .= "$imported user baru. ";
            if ($updated > 0) $message .= "$updated user diupdate. ";
            if (count($errors) > 0) $message .= count($errors) . " error.";

            // Redirect dengan hasil
            return redirect()->route('admin.users')
                ->with('success', $message)
                ->with('import_stats', [
                    'imported' => $imported,
                    'updated' => $updated,
                    'errors' => count($errors)
                ])
                ->with('import_errors', $errors);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/user_import_template.xlsx');

        // Generate template jika belum ada
        if (!file_exists($templatePath)) {
            $this->generateTemplate();
        }

        return response()->download($templatePath, 'user_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function generateTemplate()
    {
        // Buat template Excel
        Excel::store(new class implements \Maatwebsite\Excel\Concerns\FromArray {
            public function array(): array
            {
                return [
                    // Header
                    ['name', 'email', 'password', 'role', 'phone', 'department', 'position', 'notes'],

                    // Contoh data
                    ['John Doe', 'john@example.com', 'password123', 'user', '081234567890', 'IT Department', 'Developer', 'Contoh user biasa'],
                    ['Jane Smith', 'jane@example.com', 'password123', 'admin', '081234567891', 'HR Department', 'Manager', 'Contoh admin'],
                    ['Bob Wilson', 'bob@example.com', 'bobpass123', 'manager', '081234567892', 'Marketing', 'Supervisor', 'Contoh manager'],

                    // Panduan
                    ['', '', '', '', '', '', '', ''],
                    ['PANDUAN:', '', '', '', '', '', '', ''],
                    ['1. Kolom wajib: name, email', '', '', '', '', '', '', ''],
                    ['2. Role: user/admin/manager', '', '', '', '', '', '', ''],
                    ['3. Password akan di-hash otomatis', '', '', '', '', '', '', ''],
                    ['4. Format email harus valid', '', '', '', '', '', '', ''],
                    ['5. Hapus baris panduan sebelum upload', '', '', '', '', '', '', ''],
                ];
            }
        }, 'templates/user_import_template.xlsx', 'local');
    }
    // Session Management (Sesi Absensi)
    public function sessions()
    {
        $sessions = SesiAbsensi::orderBy('aktif', 'desc')
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.sessions.index', compact('sessions'));
    }

    public function createSession()
    {
        return view('admin.sessions.create');
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'nama_sesi' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'toleransi_keterlambatan' => 'nullable|date_format:H:i|after:jam_selesai',
            'maksimal_jam_pulang' => 'nullable|date_format:H:i|after:jam_selesai',
            'keterangan' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        // Validasi tambahan: toleransi harus setelah jam selesai
        if ($request->toleransi_keterlambatan) {
            $jamSelesai = Carbon::parse($request->jam_selesai);
            $toleransi = Carbon::parse($request->toleransi_keterlambatan);

            if ($toleransi->lessThanOrEqualTo($jamSelesai)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Waktu toleransi harus setelah jam selesai.');
            }
        }

        // Validasi: maksimal jam pulang harus setelah jam selesai
        if ($request->maksimal_jam_pulang) {
            $jamSelesai = Carbon::parse($request->jam_selesai);
            $maksimalPulang = Carbon::parse($request->maksimal_jam_pulang);

            if ($maksimalPulang->lessThanOrEqualTo($jamSelesai)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maksimal jam pulang harus setelah jam selesai.');
            }
        }

        // Jika sesi ini akan diaktifkan, nonaktifkan yang lain
        if ($request->aktif) {
            SesiAbsensi::where('aktif', true)->update(['aktif' => false]);
        }

        SesiAbsensi::create($request->all());

        return redirect()->route('admin.sessions')
            ->with('success', 'Sesi berhasil ditambahkan.');
    }

    public function editSession(SesiAbsensi $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    public function updateSession(Request $request, SesiAbsensi $session)
    {
        $request->validate([
            'nama_sesi' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'toleransi_keterlambatan' => 'nullable|date_format:H:i|after:jam_selesai',
            'maksimal_jam_pulang' => 'nullable|date_format:H:i|after:jam_selesai',
            'keterangan' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        // Validasi tambahan: toleransi harus setelah jam selesai
        if ($request->toleransi_keterlambatan) {
            $jamSelesai = Carbon::parse($request->jam_selesai);
            $toleransi = Carbon::parse($request->toleransi_keterlambatan);

            if ($toleransi->lessThanOrEqualTo($jamSelesai)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Waktu toleransi harus setelah jam selesai.');
            }
        }

        // Validasi: maksimal jam pulang harus setelah jam selesai
        if ($request->maksimal_jam_pulang) {
            $jamSelesai = Carbon::parse($request->jam_selesai);
            $maksimalPulang = Carbon::parse($request->maksimal_jam_pulang);

            if ($maksimalPulang->lessThanOrEqualTo($jamSelesai)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Maksimal jam pulang harus setelah jam selesai.');
            }
        }

        // Jika sesi ini akan diaktifkan, nonaktifkan yang lain
        if ($request->aktif) {
            SesiAbsensi::where('aktif', true)
                ->where('id', '!=', $session->id)
                ->update(['aktif' => false]);
        }

        $session->update($request->all());

        return redirect()->route('admin.sessions')
            ->with('success', 'Sesi berhasil diperbarui.');
    }

    public function toggleSession(SesiAbsensi $session)
    {
        if ($session->aktif) {
            $session->update(['aktif' => false]);
            $message = 'Sesi dinonaktifkan.';
        } else {
            // Nonaktifkan semua sesi lain
            SesiAbsensi::where('aktif', true)->update(['aktif' => false]);
            $session->update(['aktif' => true]);
            $message = 'Sesi diaktifkan.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function deleteSession(SesiAbsensi $session)
    {
        // Cek apakah sesi digunakan
        $used = Absensi::where('sesi_absensi_id', $session->id)->exists();

        if ($used) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus sesi yang sudah digunakan.');
        }

        $session->delete();

        return redirect()->route('admin.sessions')
            ->with('success', 'Sesi berhasil dihapus.');
    }

    // Reports & Analytics
    public function reports()
    {
        // Statistik per bulan - sekarang return sebagai collection
        $monthlyStats = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('Y-m');

            $total = Absensi::whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->count();

            $ontime = Absensi::whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->where('status_masuk', 'tepat_waktu')
                ->count();

            $late = Absensi::whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->where('status_masuk', 'terlambat')
                ->count();

            $monthlyStats->push([
                'month' => $date->format('M Y'),
                'month_raw' => $date->format('Y-m'),
                'total' => $total,
                'ontime' => $ontime,
                'late' => $late,
                'percentage' => $total > 0 ? round(($ontime / $total) * 100, 1) : 0
            ]);
        }

        // User performance - pastikan return sebagai collection
        $userPerformance = User::where('role', 'user')
            ->withCount(['absensi' => function ($query) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;
                $query->whereYear('tanggal', $currentYear)
                    ->whereMonth('tanggal', $currentMonth)
                    ->where('status_masuk', 'tepat_waktu');
            }])
            ->withCount(['absensi as total_absensi' => function ($query) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;
                $query->whereYear('tanggal', $currentYear)
                    ->whereMonth('tanggal', $currentMonth);
            }])
            ->having('total_absensi', '>', 0)
            ->orderByDesc('absensi_count')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                $user->performance = $user->total_absensi > 0 ?
                    round(($user->absensi_count / $user->total_absensi) * 100, 1) : 0;
                return $user;
            });

        return view('admin.reports.index', compact('monthlyStats', 'userPerformance'));
    }
}
