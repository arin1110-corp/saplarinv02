<?php

namespace App\Console\Commands;

use App\Models\ModelBBM;
use App\Services\GoogleDriveServiceDB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncBBMViaDB extends Command
{
    protected $signature = 'sync:bbm-db {jenis}';
    protected $description = 'Sinkronisasi file BBM ke Google Drive berdasarkan UID dan hapus file lokal SAPLARIN';

    public function handle(GoogleDriveServiceDB $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));

        if (!in_array($jenis, ['spt', 'acc', 'nota'])) {
            $this->error('Jenis tidak valid. Gunakan: spt, acc, atau nota');
            return Command::FAILURE;
        }

        $this->info("Mulai sinkronisasi BBM jenis {$jenis}...");

        $json = DB::table('saplarin_json')
            ->where('json_key', 'bbm_drive')
            ->where('json_status', 1)
            ->first();

        if (!$json) {
            $this->error('Config bbm_drive tidak ditemukan di tabel saplarin_json.');
            return Command::FAILURE;
        }

        $config = json_decode($json->json_value, true);

        $folderId = $config['folder_bbm'] ?? null;

        if (!$folderId) {
            $this->error('folder_bbm tidak ditemukan pada json_value.');
            return Command::FAILURE;
        }

        $query = ModelBBM::query()->whereNotNull('bbm_uid');

        if ($jenis === 'spt') {
            $query->whereNotNull('bbm_spt_file')->where('bbm_spt_sync', 0);
        }

        if ($jenis === 'acc') {
            $query->whereNotNull('bbm_acc_pimpinan_file')->where('bbm_acc_pimpinan_sync', 0);
        }

        if ($jenis === 'nota') {
            $query->whereNotNull('bbm_laporan_nota_file')->where('bbm_laporan_nota_sync', 0);
        }

        $query->chunk(50, function ($rows) use ($googleDrive, $folderId, $jenis) {
            foreach ($rows as $row) {
                $uid = $row->bbm_uid;

                if (!$uid) {
                    continue;
                }

                $keyword = $this->getKeyword($uid, $jenis);

                $this->info("Mencari file Drive: {$keyword}");

                usleep(300000);

                $result = $googleDrive->findFileByKeyword($keyword, $folderId);

                if (($result['status'] ?? 0) == 1) {
                    DB::transaction(function () use ($row, $result, $jenis, $uid) {
                        if ($jenis === 'spt') {
                            $oldFile = $row->bbm_spt_file;

                            $row->update([
                                'bbm_spt_file' => $result['file_url'],
                                'bbm_spt_sync' => 1,
                            ]);

                            $this->hapusFileLokal($oldFile, $uid, 'SPT');
                        }

                        if ($jenis === 'acc') {
                            $oldFile = $row->bbm_acc_pimpinan_file;

                            $row->update([
                                'bbm_acc_pimpinan_file' => $result['file_url'],
                                'bbm_acc_pimpinan_sync' => 1,
                            ]);

                            $this->hapusFileLokal($oldFile, $uid, 'ACC Pimpinan');
                        }

                        if ($jenis === 'nota') {
                            $oldFile = $row->bbm_laporan_nota_file;

                            $row->update([
                                'bbm_laporan_nota_file' => $result['file_url'],
                                'bbm_laporan_nota_sync' => 1,
                            ]);

                            $this->hapusFileLokal($oldFile, $uid, 'Nota');
                        }
                    });

                    $this->info("{$uid} → Sinkron berhasil: {$result['file_name']}");
                } else {
                    $this->warn("{$uid} → File belum ditemukan di Drive");
                }
            }
        });

        $this->info("Sinkronisasi BBM {$jenis} selesai.");

        return Command::SUCCESS;
    }

    private function getKeyword($uid, $jenis)
    {
        if ($jenis === 'spt') {
            return $uid . '-spt';
        }

        if ($jenis === 'acc') {
            return $uid . '-acc-pimpinan';
        }

        if ($jenis === 'nota') {
            return $uid . '-nota';
        }

        return $uid;
    }

    private function hapusFileLokal($oldFile, $uid, $label)
    {
        if (!$oldFile) {
            return;
        }

        if (str_starts_with($oldFile, 'http')) {
            return;
        }

        $relativePath = parse_url($oldFile, PHP_URL_PATH);
        $relativePath = ltrim($relativePath, '/');

        $localPath = public_path($relativePath);

        if (file_exists($localPath)) {
            if (unlink($localPath)) {
                $this->info("{$uid} → File lokal {$label} dihapus");
            } else {
                $this->warn("{$uid} → Gagal hapus file lokal {$label}");
            }
        } else {
            $this->warn("{$uid} → File lokal {$label} tidak ditemukan: {$localPath}");
        }
    }
}