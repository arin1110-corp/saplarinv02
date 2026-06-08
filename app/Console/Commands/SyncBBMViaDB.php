<?php

namespace App\Console\Commands;

use App\Models\ModelBBM;
use App\Models\ModelDriveFolder;
use App\Services\GoogleDriveServiceDB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncBBMViaDB extends Command
{
    protected $signature = 'sync:bbm-db {uid?}';

    protected $description = 'Sinkronisasi semua file BBM berdasarkan UID ke Google Drive';

    public function handle(GoogleDriveServiceDB $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $uidArg = $this->argument('uid');

        $folder = ModelDriveFolder::with('json')
            ->where('folder_prefix', 'bbm')
            ->where('folder_status', 1)
            ->first();

        if (!$folder) {
            $this->error('Folder Drive dengan prefix bbm tidak ditemukan.');
            return Command::FAILURE;
        }

        if (!$folder->json) {
            $this->error('JSON Credential untuk folder BBM tidak ditemukan.');
            return Command::FAILURE;
        }

        $jsonPath = storage_path('app/' . $folder->json->json_file);
        $folderId = $folder->folder_drive_id;

        if (!file_exists($jsonPath)) {
            $this->error('File JSON credential tidak ditemukan: ' . $jsonPath);
            return Command::FAILURE;
        }

        $googleDrive->setCredential($jsonPath);

        $query = ModelBBM::query()
            ->where('bbm_status_pengajuan', 'Pengajuan Diterima');

        if ($uidArg) {
            $query->where('bbm_uid', $uidArg);
        }

        $query->chunk(50, function ($rows) use ($googleDrive, $folderId) {
            foreach ($rows as $bbm) {
                $this->info('Sinkron UID: ' . $bbm->bbm_uid);

                $this->syncFile(
                    $googleDrive,
                    $folderId,
                    $bbm,
                    'spt',
                    'bbm_spt_file',
                    'bbm_spt_sync'
                );

                $this->syncFile(
                    $googleDrive,
                    $folderId,
                    $bbm,
                    'acc-pimpinan',
                    'bbm_acc_pimpinan_file',
                    'bbm_acc_pimpinan_sync'
                );

                if ($bbm->bbm_laporan_nota_file) {
                    $this->syncFile(
                        $googleDrive,
                        $folderId,
                        $bbm,
                        'nota',
                        'bbm_laporan_nota_file',
                        'bbm_laporan_nota_sync'
                    );
                }

                $this->info('Selesai UID: ' . $bbm->bbm_uid);
            }
        });

        $this->info('Sinkron BBM selesai.');

        return Command::SUCCESS;
    }

    private function syncFile($googleDrive, $folderId, $bbm, $jenis, $fieldFile, $fieldSync)
    {
        if (!$bbm->{$fieldFile}) {
            $this->warn($bbm->bbm_uid . " → {$jenis} belum ada file.");
            return;
        }

        if ($bbm->{$fieldSync}) {
            $this->info($bbm->bbm_uid . " → {$jenis} sudah sinkron.");
            return;
        }

        if (str_starts_with($bbm->{$fieldFile}, 'http')) {
            $bbm->update([
                $fieldSync => 1,
            ]);

            $this->info($bbm->bbm_uid . " → {$jenis} sudah berupa URL Drive.");
            return;
        }

        $keyword = $bbm->bbm_uid . '-' . $jenis;

        usleep(300000);

        $result = $googleDrive->findFileByKeyword($keyword, $folderId);

        if (($result['status'] ?? 0) != 1) {
            $this->warn($bbm->bbm_uid . " → {$jenis} belum ditemukan di Drive.");
            return;
        }

        $oldFile = $bbm->{$fieldFile};

        DB::transaction(function () use ($bbm, $fieldFile, $fieldSync, $result, $oldFile, $jenis) {
            $bbm->update([
                $fieldFile => $result['file_url'],
                $fieldSync => 1,
            ]);

            $this->hapusFileLokal($oldFile, $bbm->bbm_uid, strtoupper($jenis));
        });

        $this->info($bbm->bbm_uid . " → {$jenis} sinkron: " . $result['file_name']);
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
            unlink($localPath);
            $this->info("{$uid} → File lokal {$label} dihapus.");
        } else {
            $this->warn("{$uid} → File lokal {$label} tidak ditemukan.");
        }
    }
}