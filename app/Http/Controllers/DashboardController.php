<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\SesiAbsensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $user = Auth::user();
        $today = Carbon::today();
        $waktuSekarang = Carbon::now();

        // Ambil sesi aktif untuk hari ini
        $sesiHariIni = SesiAbsensi::aktif()->first();

        // Cek absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Cek apakah bisa absen masuk berdasarkan waktu sesi
        $bisaAbsenMasuk = false;
        $pesanAbsenMasuk = '';

        if ($sesiHariIni && !$absensiHariIni) {
            $bisaAbsenMasuk = $sesiHariIni->isWaktuAbsenMasuk($waktuSekarang);

            // Tambahkan pesan informasi
            $jamMulai = Carbon::parse($sesiHariIni->jam_mulai)->format('H:i');
            $jamSelesai = Carbon::parse($sesiHariIni->jam_selesai)->format('H:i');

            if ($sesiHariIni->toleransi_keterlambatan) {
                $toleransi = Carbon::parse($sesiHariIni->toleransi_keterlambatan)->format('H:i');
                $pesanAbsenMasuk = "Absen masuk: {$jamMulai} - {$toleransi} (toleransi sampai {$toleransi})";
            } else {
                $pesanAbsenMasuk = "Absen masuk: {$jamMulai} - {$jamSelesai}";
            }
        }

        // Cek apakah bisa absen pulang
        // Update bagian cek absen pulang di controller index()
        // Cek apakah bisa absen pulang
        $bisaAbsenPulang = false;
        $pesanAbsenPulang = '';
        $alasanTidakBisaPulang = '';

        if ($absensiHariIni && $absensiHariIni->jam_masuk && !$absensiHariIni->jam_pulang) {
            if ($sesiHariIni) {
                if ($sesiHariIni->isBisaAbsenPulang($waktuSekarang)) {
                    $bisaAbsenPulang = true;

                    if ($sesiHariIni->maksimal_jam_pulang) {
                        $maksimal = Carbon::parse($sesiHariIni->maksimal_jam_pulang)->format('H:i');
                        $pesanAbsenPulang = "Absen pulang sampai: {$maksimal}";
                    }
                } else {
                    // Tentukan alasan tidak bisa absen pulang
                    if ($sesiHariIni->isMelewatiMaksimalPulang($waktuSekarang)) {
                        $maksimal = Carbon::parse($sesiHariIni->maksimal_jam_pulang)->format('H:i');
                        $alasanTidakBisaPulang = "Waktu absen pulang sudah berakhir (maksimal {$maksimal})";
                    } else {
                        $mulai = Carbon::parse($sesiHariIni->jam_selesai)->format('H:i');
                        $alasanTidakBisaPulang = "Belum waktu absen pulang (mulai {$mulai})";
                    }
                }
            } else {
                // Jika tidak ada sesi, tetap bisa absen pulang
                $bisaAbsenPulang = true;
            }
        }

        // Riwayat absensi 7 hari terakhir
        $riwayatAbsensi = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->limit(7)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'sesiHariIni',
            'absensiHariIni',
            'bisaAbsenMasuk',
            'bisaAbsenPulang',
            'riwayatAbsensi',
            'pesanAbsenMasuk',
            'pesanAbsenPulang'
        ));
    }

    public function absenMasuk(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::today();
            $now = Carbon::now();

            // 1. Validasi sudah absen hari ini
            $existing = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'Anda sudah absen masuk hari ini.');
            }

            // 2. Cek sesi aktif
            $sesi = SesiAbsensi::aktif()->first();
            if (!$sesi) {
                return redirect()->back()->with('error', 'Tidak ada sesi absen aktif.');
            }

            // 3. Cek apakah tanggal valid (harus hari ini)
            if (!$now->isSameDay($today)) {
                return redirect()->back()->with(
                    'error',
                    'Tidak bisa absen untuk hari sebelumnya. Absen hanya berlaku untuk hari ini.'
                );
            }

            // 4. Cek apakah dalam waktu absen masuk (termasuk toleransi)
            if (!$sesi->isWaktuAbsenMasuk($now)) {
                $jamMulai = Carbon::parse($sesi->jam_mulai)->format('H:i');
                $jamSelesai = Carbon::parse($sesi->jam_selesai)->format('H:i');

                if ($sesi->toleransi_keterlambatan) {
                    $toleransi = Carbon::parse($sesi->toleransi_keterlambatan)->format('H:i');
                    return redirect()->back()->with(
                        'error',
                        "Waktu absen masuk sudah berakhir. Absen masuk hanya dari {$jamMulai} sampai {$toleransi}"
                    );
                } else {
                    return redirect()->back()->with(
                        'error',
                        "Waktu absen masuk sudah berakhir. Absen masuk hanya dari {$jamMulai} sampai {$jamSelesai}"
                    );
                }
            }

            // 5. Tentukan status berdasarkan waktu
            $status = $sesi->getStatusAbsen($now);

            if ($status === 'tidak_bisa_absen') {
                return redirect()->back()->with('error', 'Waktu absen sudah habis.');
            }

            // 6. Siapkan data absensi
            $absensiData = [
                'user_id' => $user->id,
                'sesi_absensi_id' => $sesi->id,
                'tanggal' => $today,
                'jam_masuk' => $now->format('H:i:s'),
                'status_masuk' => $status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ];

            // 7. Tambahkan lokasi jika ada
            if ($request->has('latitude') && $request->has('longitude')) {
                $absensiData['lokasi_masuk'] = $request->latitude . ',' . $request->longitude;
            }

            // 8. Simpan absensi
            Absensi::create($absensiData);

            $pesanStatus = $status === 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat';
            return redirect()->back()->with('success', "Absen masuk berhasil. Status: {$pesanStatus}");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function absenPulang(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::today();
            $now = Carbon::now();

            // 1. Cari absensi hari ini
            $absensi = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$absensi) {
                return redirect()->back()->with('error', 'Anda belum absen masuk hari ini.');
            }

            if ($absensi->jam_pulang) {
                return redirect()->back()->with('error', 'Anda sudah absen pulang hari ini.');
            }

            // 2. Cek sesi aktif untuk validasi waktu maksimal pulang
            $sesi = SesiAbsensi::aktif()->first();

            // 3. Validasi maksimal jam pulang jika ada sesi
            if ($sesi && $sesi->maksimal_jam_pulang) {
                $maksimalPulang = $today->copy()->setTimeFrom(
                    Carbon::parse($sesi->maksimal_jam_pulang)
                );

                // Cek apakah sudah melewati waktu maksimal absen pulang
                if ($now->greaterThan($maksimalPulang)) {
                    $formatWaktu = Carbon::parse($sesi->maksimal_jam_pulang)->format('H:i');
                    return redirect()->back()->with(
                        'error',
                        "Waktu absen pulang sudah berakhir. Maksimal absen pulang sampai pukul {$formatWaktu}"
                    );
                }

                // Cek apakah sudah memasuki waktu absen pulang
                $mulaiPulang = $today->copy()->setTimeFrom(
                    Carbon::parse($sesi->jam_selesai)
                );

                if ($now->lessThan($mulaiPulang)) {
                    $formatMulai = Carbon::parse($sesi->jam_selesai)->format('H:i');
                    return redirect()->back()->with(
                        'error',
                        "Belum waktu absen pulang. Absen pulang bisa dilakukan setelah pukul {$formatMulai}"
                    );
                }
            }

            // 4. Validasi minimal bekerja (opsional - bisa diaktifkan jika diperlukan)
            if ($absensi->jam_masuk) {
                $jamMasuk = Carbon::parse($absensi->jam_masuk);
                $minimalPulang = $jamMasuk->copy()->addHours(1); // Minimal 1 jam kerja

                if ($now->lessThan($minimalPulang)) {
                    $sisaWaktu = $minimalPulang->diffInMinutes($now);

                    return redirect()->back()->with(
                        'error',
                        "Minimal bekerja 1 jam sebelum absen pulang. " .
                            "Anda baru bekerja " . $jamMasuk->diffInMinutes($now) . " menit. " .
                            "Silakan coba lagi dalam {$sisaWaktu} menit (" .
                            $minimalPulang->format('H:i') . ")"
                    );
                }

                // Hitung lama kerja
                $lamaKerja = $jamMasuk->diff($now)->format('%H:%I');
            }

            // 5. Tentukan status pulang
            // Untuk WFA, biasanya tidak ada status "cepat", semua "tepat_waktu"
            $statusPulang = 'tepat_waktu';

            // 6. Data update
            $updateData = [
                'jam_pulang' => $now->format('H:i:s'),
                'status_pulang' => $statusPulang,
                'lama_kerja' => $lamaKerja ?? null,
            ];

            // 7. Tambahkan lokasi untuk pulang jika ada
            if ($request->has('latitude') && $request->has('longitude')) {
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $updateData['lokasi_pulang'] = $latitude . ',' . $longitude;
            }

            // 8. Update absensi
            $absensi->update($updateData);

            // 9. Kirim notifikasi sukses dengan info waktu kerja
            $pesan = 'Absen pulang berhasil.';
            if (isset($lamaKerja)) {
                $pesan .= " Lama kerja: {$lamaKerja}";
            }

            return redirect()->back()->with('success', $pesan);
        } catch (\Exception $e) {
            \Log::error('Absen pulang error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function uploadBukti(Request $request)
    {
        try {
            $request->validate([
                'bukti_pekerjaan' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Max 5MB
                'catatan' => 'nullable|string|max:1000'
            ]);

            $user = Auth::user();
            $today = Carbon::today();
            $now = Carbon::now();

            // Cari absensi hari ini
            $absensi = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$absensi) {
                return redirect()->back()->with('error', 'Anda belum melakukan absensi hari ini.');
            }

            // Cek apakah sudah pernah upload bukti
            if ($absensi->bukti_diupload) {
                return redirect()->back()->with('error', 'Anda sudah mengupload bukti pekerjaan hari ini.');
            }

            // Validasi jam upload (maksimal jam 21:00)
            if ($now->format('H:i') > '21:00') {
                return redirect()->back()->with('error', 'Upload bukti pekerjaan maksimal pukul 21:00 WIB.');
            }

            // Upload file
            if ($request->hasFile('bukti_pekerjaan')) {
                $file = $request->file('bukti_pekerjaan');

                // Generate nama file yang unik
                $filename = 'bukti_' . $user->id . '_' . $today->format('Ymd') . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Simpan file di storage
                $path = $file->storeAs('bukti_pekerjaan', $filename, 'public');

                // Update data absensi
                $absensi->update([
                    'bukti_pekerjaan' => $path,
                    'bukti_diupload' => true,
                    'jam_upload_bukti' => $now->format('H:i:s'),
                    'catatan' => $request->catatan
                ]);

                return redirect()->back()->with('success', 'Bukti pekerjaan berhasil diupload.');
            }

            return redirect()->back()->with('error', 'Gagal mengupload file.');
        } catch (\Exception $e) {
            Log::error('Upload bukti error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat upload: ' . $e->getMessage());
        }
    }
}
