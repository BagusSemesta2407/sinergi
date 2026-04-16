<?php

namespace App\Models;

use App\Helpers\LocationHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sesi_absensi_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status_masuk', 'status_pulang', 'bukti_pekerjaan', 'bukti_diupload', 'jam_upload_bukti', 'catatan', 'lokasi_masuk', 'lokasi_pulang', 'ip_address', 'user_agent'];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i',
        'jam_pulang' => 'datetime:H:i',
        'jam_upload_bukti' => 'datetime:H:i',
        'bukti_diupload' => 'boolean'
    ];

    // protected $appends = ['status_label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function sesiAbsensi()
    {
        return $this->belongsTo(SesiAbsensi::class);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            'cepat' => 'Cepat',
            'tidak_absen' => 'Tidak Absen'
        ];

        return $labels[$this->status_masuk] ?? $this->status_masuk;
    }

    public function isHariIni()
    {
        return $this->tanggal->isToday();
    }

    public function canUploadBukti()
    {
        // Jika sudah upload, tidak bisa upload lagi
        if ($this->bukti_diupload) {
            return false;
        }

        $now = Carbon::now();
        $batasWaktu = Carbon::createFromTime(21, 0, 0); // Jam 21:00

        // Cek apakah sekarang masih sebelum jam 21:00
        return $now->lessThan($batasWaktu);
    }

    public function getBuktiUrlAttribute()
    {
        if ($this->bukti_pekerjaan) {
            return asset('storage/' . $this->bukti_pekerjaan);
        }
        return null;
    }

    public function getParsedLocationMasukAttribute()
    {
        return LocationHelper::parseCoordinates($this->lokasi_masuk);
    }

    public function getParsedLocationPulangAttribute()
    {
        return LocationHelper::parseCoordinates($this->lokasi_pulang);
    }

    public function getDistanceBetweenLocationsAttribute()
    {
        if ($this->lokasi_masuk && $this->lokasi_pulang) {
            $distance = LocationHelper::getDistance($this->lokasi_masuk, $this->lokasi_pulang);
            return LocationHelper::formatDistance($distance);
        }
        return null;
    }

    public function getFormattedLocationMasukAttribute()
    {
        if (!$this->lokasi_masuk) return null;

        $parsed = $this->parsed_location_masuk;
        if (isset($parsed['is_address'])) {
            return $parsed['address'];
        }
        return $parsed['formatted'] ?? $this->lokasi_masuk;
    }

    public function getFormattedLocationPulangAttribute()
    {
        if (!$this->lokasi_pulang) return null;

        $parsed = $this->parsed_location_pulang;
        if (isset($parsed['is_address'])) {
            return $parsed['address'];
        }
        return $parsed['formatted'] ?? $this->lokasi_pulang;
    }

    // Tambahkan di $appends
    protected $appends = [
        'status_label',
        'parsed_location_masuk',
        'parsed_location_pulang',
        'formatted_location_masuk',
        'formatted_location_pulang',
        'distance_between_locations'
    ];
}
