<?php

namespace Database\Seeders;

use App\Models\SesiAbsensi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345'),
            'status' =>'active',
            'role' => 'admin'
        ]);

        // Create regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('12345'),
            'status' =>'active',
            'role' => 'user'
        ]);

        User::create([
            'name' => 'Tes Doe',
            'email' => 'tes@example.com',
            'password' => Hash::make('12345'),
            'status' =>'active',
            'role' => 'user'
        ]);

        // Create default session
        SesiAbsensi::create([
            'nama_sesi' => 'Work From Home (WFH)',
            'jam_mulai' => '05:30', //jam 06.00
            'jam_selesai' => '07:30', //jam 07.30
            'toleransi_keterlambatan' => '10:00', // Tambahan: toleransi 1.5 jam, jam 12.00
            'maksimal_jam_pulang' => '21:00',
            'aktif' => true,
            'keterangan' => 'Absen masuk WFA pagi hari'
        ]);
    }
}
