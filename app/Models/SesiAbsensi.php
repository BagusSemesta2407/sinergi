<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesiAbsensi extends Model
{
    use HasFactory;

    // protected $fillable = ['nama_sesi', 'jam_mulai', 'jam_selesai', 'aktif', 'keterangan'];
    protected $fillable = [
        'nama_sesi',
        'jam_mulai',
        'jam_selesai',
        'toleransi_keterlambatan',
        'maksimal_jam_pulang',
        'aktif',
        'keterangan'
    ];

    /**
     * Get all of the absensi for the SesiAbsensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'toleransi_keterlambatan' => 'datetime:H:i',
        'maksimal_jam_pulang' => 'datetime:H:i',
        'aktif' => 'boolean'
    ];

    public function getJamRangeAttribute()
    {
        return date('H:i', strtotime($this->jam_mulai)) . ' - ' . date('H:i', strtotime($this->jam_selesai));
    }

    public function getRentangToleransiAttribute()
    {
        if ($this->toleransi_keterlambatan) {
            return date('H:i', strtotime($this->jam_selesai)) . ' - ' . date('H:i', strtotime($this->toleransi_keterlambatan));
        }
        return null;
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Cek apakah waktu sekarang dalam rentang absen masuk
     * termasuk toleransi keterlambatan
     */
    public function isWaktuAbsenMasuk(Carbon $waktu = null)
    {
        $waktu = $waktu ?? Carbon::now();
        $hariIni = $waktu->copy()->startOfDay();

        $jamMulai = $hariIni->copy()->setTimeFrom(Carbon::parse($this->jam_mulai));
        $jamSelesai = $hariIni->copy()->setTimeFrom(Carbon::parse($this->jam_selesai));

        // Jika ada toleransi, gunakan toleransi sebagai batas akhir
        $batasAkhir = $jamSelesai;
        if ($this->toleransi_keterlambatan) {
            $batasAkhir = $hariIni->copy()->setTimeFrom(Carbon::parse($this->toleransi_keterlambatan));
        }

        return $waktu->between($jamMulai, $batasAkhir);
    }

    /**
     * Cek apakah waktu sekarang dalam rentang absen pulang
     */
    public function isWaktuAbsenPulang(Carbon $waktu = null)
    {
        $waktu = $waktu ?? Carbon::now();
        $hariIni = $waktu->copy()->startOfDay();

        $jamMulai = $hariIni->copy()->setTimeFrom(Carbon::parse($this->jam_selesai));
        $jamSelesai = $hariIni->copy()->endOfDay();

        // Jika ada maksimal jam pulang, gunakan itu sebagai batas
        if ($this->maksimal_jam_pulang) {
            $jamSelesai = $hariIni->copy()->setTimeFrom(Carbon::parse($this->maksimal_jam_pulang));
        }

        return $waktu->between($jamMulai, $jamSelesai);
    }

    /**
     * Tentukan status absen berdasarkan waktu
     */
    public function getStatusAbsen(Carbon $waktu)
    {
        $hariIni = $waktu->copy()->startOfDay();

        $jamSelesai = $hariIni->copy()->setTimeFrom(Carbon::parse($this->jam_selesai));

        // Jika waktu sebelum jam selesai, tepat waktu
        if ($waktu->lessThanOrEqualTo($jamSelesai)) {
            return 'tepat_waktu';
        }

        // Jika ada toleransi dan waktu masih dalam toleransi, terlambat
        if ($this->toleransi_keterlambatan) {
            $batasToleransi = $hariIni->copy()->setTimeFrom(Carbon::parse($this->toleransi_keterlambatan));
            if ($waktu->lessThanOrEqualTo($batasToleransi)) {
                return 'terlambat';
            }
        }

        // Di luar toleransi, tidak bisa absen (akan di-handle oleh controller)
        return 'tidak_bisa_absen';
    }

    // Tambahkan method ini ke model SesiAbsensi
    /**
     * Cek apakah waktu sekarang sudah melewati batas maksimal absen pulang
     */
    public function isMelewatiMaksimalPulang(Carbon $waktu = null)
    {
        $waktu = $waktu ?? Carbon::now();

        if (!$this->maksimal_jam_pulang) {
            return false; // Tidak ada batas maksimal
        }

        $hariIni = $waktu->copy()->startOfDay();
        $batasMaksimal = $hariIni->copy()->setTimeFrom(
            Carbon::parse($this->maksimal_jam_pulang)
        );

        return $waktu->greaterThan($batasMaksimal);
    }

    /**
     * Cek apakah waktu sekarang sudah bisa absen pulang
     */
    public function isBisaAbsenPulang(Carbon $waktu = null)
    {
        $waktu = $waktu ?? Carbon::now();

        // Tidak bisa jika sudah melewati batas maksimal
        if ($this->isMelewatiMaksimalPulang($waktu)) {
            return false;
        }

        $hariIni = $waktu->copy()->startOfDay();
        $mulaiPulang = $hariIni->copy()->setTimeFrom(
            Carbon::parse($this->jam_selesai)
        );

        // Bisa absen pulang jika sudah lewat jam selesai
        return $waktu->greaterThanOrEqualTo($mulaiPulang);
    }
}
