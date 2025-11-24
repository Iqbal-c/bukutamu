<?php

namespace Database\Seeders;

use App\Models\Tamu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin user
        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@bukutamu.test',
            'password' => bcrypt('admin123'), // password: admin123
        ]);

        // 2. 10 data tamu dummy (SESUAI KOLOM YANG BENAR-BENAR ADA)
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
                'alamat'              => $i[1],                 // ← sesuai tabel
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