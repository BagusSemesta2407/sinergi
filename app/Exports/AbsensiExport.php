<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $userId;
    
    public function __construct($startDate = null, $endDate = null, $userId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userId = $userId;
    }
    
    public function collection()
    {
        $query = Absensi::with('user', 'sesiAbsensi');
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }
        
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }
    
    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status Masuk',
            'Status Pulang',
            'Lokasi Masuk',
            'Lokasi Pulang',
            'Bukti Upload',
            'Catatan',
            'IP Address',
            'Waktu Absen',
            'Waktu Update'
        ];
    }
    
    public function map($absensi): array
    {
        return [
            $absensi->user->name,
            $absensi->user->email,
            $absensi->tanggal->format('d/m/Y'),
            $absensi->jam_masuk ?? '-',
            $absensi->jam_pulang ?? '-',
            $this->getStatusLabel($absensi->status_masuk),
            $this->getStatusLabel($absensi->status_pulang),
            $absensi->lokasi_masuk ?? '-',
            $absensi->lokasi_pulang ?? '-',
            $absensi->bukti_diupload ? 'Ya' : 'Tidak',
            $absensi->catatan ?? '-',
            $absensi->ip_address ?? '-',
            $absensi->created_at->format('d/m/Y H:i:s'),
            $absensi->updated_at->format('d/m/Y H:i:s'),
        ];
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            'cepat' => 'Cepat',
            'tidak_absen' => 'Tidak Absen'
        ];
        
        return $labels[$status] ?? $status;
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Set column widths
            'A' => ['width' => 25],
            'B' => ['width' => 30],
            'C' => ['width' => 15],
            'D' => ['width' => 15],
            'E' => ['width' => 15],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 20],
            'I' => ['width' => 20],
            'J' => ['width' => 15],
            'K' => ['width' => 30],
            'L' => ['width' => 20],
            'M' => ['width' => 20],
            'N' => ['width' => 20],
        ];
    }
}