<?php

namespace Database\Seeders;

use App\Models\Tamu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin Users (2 User)
        User::updateOrCreate(
            ['email' => 'admin@bukutamu.test'],
            [
                'name'     => 'Super Admin 1',
                'password' => Hash::make('admin123'),
                'role'     => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin2@bukutamu.test'],
            [
                'name'     => 'Super Admin 2',
                'password' => Hash::make('admin123'),
                'role'     => 'super_admin',
            ]
        );

        // 2. Admin Biasa (3 User)
        $admins = [
            ['Admin Biasa 1', 'admin1@bukutamu.test'],
            ['Admin Biasa 2', 'admin2@bukutamu.test'],
            ['Admin Biasa 3', 'admin3@bukutamu.test'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin[1]],
                [
                    'name'     => $admin[0],
                    'password' => Hash::make('admin123'),
                    'role'     => 'admin',
                ]
            );
        }

        // 3. Data Tamu Dummy
        $data = [
            ['Budi Santoso',       'Jakarta',      'Meeting dengan Direktur'],
            ['Siti Aminah',        'Bandung',      'Studi banding sekolah'],
            ['Ahmad Fauzi',        'Surabaya',     'Koordinasi bantuan pendidikan'],
            ['Rina Wulandari',     'Semarang',     'Penawaran kerjasama bank'],
            ['Dedi Kurniawan',     'Medan',        'Pengamanan acara kantor'],
            ['Larasati Putri',     'Yogyakarta',   'Wawancara skripsi'],
            ['Joko Widodo',        'Solo',         'Survei proyek infrastruktur'],
            ['Nia Ramadhani',      'Makassar',     'Kerjasama rumah sakit'],
            ['Eko Patrio',         'Malang',       'Reses anggota DPR'],
            ['Maya Sari',          'Batam',        'Layanan internet dedicated'],
        ];

        foreach ($data as $i) {
            Tamu::create([
                'nama'                => $i[0],
                'alamat'              => $i[1],
                'no_hp'               => '08' . rand(11,99) . rand(11111111,99999999),
                'keperluan_kunjungan' => $i[2],
                'paraf'               => null,
                'foto'                => null,
                'ip_address'          => '127.0.0.1',
                'created_at'          => now()->subDays(rand(1,30)),
                'updated_at'          => now(),
            ]);
        }
    }
}
