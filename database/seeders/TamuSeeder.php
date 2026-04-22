<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tamu;
use Carbon\Carbon;

class TamuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftarNama = [
            'Andi Pratama', 'Budi Santoso', 'Citra Dewi', 'Dedi Supriatna', 'Eka Putra',
            'Fajar Nugroho', 'Gita Permata', 'Hendra Wijaya', 'Intan Permata', 'Joko Susilo',
            'Kartika Sari', 'Lukman Hakim', 'Maya Putri', 'Nurul Huda', 'Oki Setiawan',
            'Putri Ayu', 'Rizki Pratama', 'Siti Aminah', 'Teguh Permana', 'Umi Kalsum',
            'Vicky Saputra', 'Wulan Sari', 'Yudi Permana', 'Zulkarnaen', 'Agus Salim',
            'Bambang Suparman', 'Dewi Lestari', 'Firman Wijaya', 'Gusti Ayu', 'Hasan Basri'
        ];

        $daftarAlamat = [
            'Jakarta Selatan', 'Bandung, Jawa Barat', 'Surabaya, Jawa Timur', 'Yogyakarta', 'Semarang, Jawa Tengah',
            'Medan, Sumatera Utara', 'Palembang, Sumatera Selatan', 'Makassar, Sulawesi Selatan', 'Denpasar, Bali', 'Pekanbaru, Riau',
            'Padang, Sumatera Barat', 'Malang, Jawa Timur', 'Solo, Jawa Tengah', 'Tangerang, Banten', 'Bekasi, Jawa Barat',
            'Depok, Jawa Barat', 'Cirebon, Jawa Barat', 'Purwokerto, Jawa Tengah', 'Jember, Jawa Timur', 'Pontianak, Kalimantan Barat',
            'Banjarmasin, Kalimantan Selatan', 'Samarinda, Kalimantan Timur', 'Manado, Sulawesi Utara', 'Kendari, Sulawesi Tenggara', 'Ambon, Maluku'
        ];

        $daftarKeperluan = [
            'Bertamu ke kantor', 'Studi banding', 'Meeting dengan manajemen', 'Rapat koordinasi',
            'Pengiriman dokumen', 'Pengambilan dokumen', 'Survei lokasi', 'Presentasi proyek',
            'Interview kerja', 'Kunjungan industri', 'Pelatihan dan workshop', 'Audit internal',
            'Rapat rutin', 'Diskusi proyek', 'Kunjungan silaturahmi', 'Pemberian penghargaan'
        ];

        $totalData = 50;
        $this->command->info('Membuat ' . $totalData . ' data tamu dummy...');

        for ($i = 0; $i < $totalData; $i++) {
            $nama = $daftarNama[array_rand($daftarNama)];
            $alamat = $daftarAlamat[array_rand($daftarAlamat)];
            $keperluan = $daftarKeperluan[array_rand($daftarKeperluan)];
            
            // No HP acak
            $noHp = '08' . rand(100000000, 999999999);
            
            // Tanggal acak 3 bulan terakhir
            $tanggal = Carbon::now()->subDays(rand(0, 90));
            
            Tamu::create([
                'nama' => $nama,
                'alamat' => $alamat,
                'no_hp' => $noHp,
                'keperluan_kunjungan' => $keperluan,
                'paraf' => null,
                'foto' => null,
                'ip_address' => '192.168.1.' . rand(1, 254),
                'created_at' => $tanggal,
                'updated_at' => $tanggal
            ]);
            
            $this->command->info(($i + 1) . '. ' . $nama);
        }
        
        $this->command->info('');
        $this->command->info('✅ Selesai! ' . $totalData . ' data tamu dummy berhasil dibuat!');
    }
}
