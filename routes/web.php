<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SesiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/absen/masuk', [DashboardController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [DashboardController::class, 'absenPulang'])->name('absen.pulang');
    Route::post('/upload-bukti', [DashboardController::class, 'uploadBukti'])->name('upload.bukti');
    
    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('sesi', SesiController::class);
        Route::post('sesi/{sesi}/toggle', [SesiController::class, 'toggleAktif'])->name('sesi.toggle');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Import Users
    Route::get('/users/import/template', [AdminController::class, 'downloadTemplate'])->name('users.import.template');
    Route::get('/users/import', [AdminController::class, 'importUsersForm'])->name('users.import.form');
    Route::post('/users/import', [AdminController::class, 'importUsers'])->name('users.import');
    // Attendance Management
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('attendance');
    Route::get('/attendance/{absensi}', [AdminController::class, 'attendanceDetail'])->name('attendance.detail');
    Route::put('/attendance/{absensi}', [AdminController::class, 'updateAttendance'])->name('attendance.update');
    
    // Export Data
    Route::post('/export', [AdminController::class, 'exportAttendance'])->name('export');
    
    // Session Management
    Route::get('/sessions', [AdminController::class, 'sessions'])->name('sessions');
    Route::get('/sessions/create', [AdminController::class, 'createSession'])->name('sessions.create');
    Route::post('/sessions', [AdminController::class, 'storeSession'])->name('sessions.store');
    Route::get('/sessions/{session}/edit', [AdminController::class, 'editSession'])->name('sessions.edit');
    Route::put('/sessions/{session}', [AdminController::class, 'updateSession'])->name('sessions.update');
    Route::post('/sessions/{session}/toggle', [AdminController::class, 'toggleSession'])->name('sessions.toggle');
    Route::delete('/sessions/{session}', [AdminController::class, 'deleteSession'])->name('sessions.delete');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});