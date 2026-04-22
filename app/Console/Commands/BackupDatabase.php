<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database otomatis ke file SQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai backup database...');

        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
        $backupPath = storage_path('app/backups');

        // Buat direktori backup jika belum ada
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $fullPath = $backupPath . '/' . $filename;

        // Perintah mysqldump
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbName),
            escapeshellarg($fullPath)
        );

        // Hapus karakter escape untuk password kosong
        if (empty($dbPass)) {
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );
        }

        $output = null;
        $exitCode = null;
        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            // Hapus backup lama (pertahankan 7 hari terakhir)
            $files = File::files($backupPath);
            $cutoffTime = now()->subDays(7);
            foreach ($files as $file) {
                if (filemtime($file) < $cutoffTime->timestamp) {
                    File::delete($file);
                }
            }

            $this->info('✓ Backup database berhasil! File: ' . $filename);
            return Command::SUCCESS;
        } else {
            $this->error('✗ Gagal backup database!');
            $this->error('Output: ' . implode("\n", $output));
            return Command::FAILURE;
        }
    }
}
