<?php

namespace App\Console\Commands;

use App\Models\ModelDriveFolder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MoveDriveFilesToFolders extends Command
{
    protected $signature = 'arindrive:move-drive-files {--dry-run}';

    protected $description = 'Pindahkan file Google Drive yang sudah tersimpan di DB ke folder tujuan sesuai saplarin_drive_folder';

    private int $success = 0;
    private int $failed = 0;
    private int $skipped = 0;

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $targets = [
            [
                'table' => 'saplarin_bbm_pengajuan',
                'pk' => 'bbm_id',
                'fields' => [
                    'bbm_spt_file' => 'bbm_spt',
                    'bbm_acc_pimpinan_file' => 'bbm_acc',
                    'bbm_laporan_nota_file' => 'bbm_nota',
                ],
            ],
            [
                'table' => 'saplarin_laporan_aktivitas_bukti',
                'pk' => 'bukti_id',
                'fields' => [
                    'bukti_file' => 'laporan_aktivitas',
                ],
            ],
            [
                'table' => 'saplarin_kinerja_progress_bukti',
                'pk' => 'bukti_id',
                'fields' => [
                    'bukti_file' => 'kinerja',
                ],
            ],
            [
                'table' => 'saplarin_prioritas_bukti_file',
                'pk' => 'file_id',
                'fields' => [
                    'file_path' => 'prioritas',
                ],
            ],
            [
                'table' => 'sadarin_program_prioritas_capaian_file',
                'pk' => 'file_id',
                'fields' => [
                    'file_path' => 'program_prioritas',
                ],
            ],
            [
                'table' => 'saplarin_spj_realisasi',
                'pk' => 'spj_id',
                'fields' => [
                    'spj_file' => 'spj',
                ],
            ],
        ];

        $this->info('Mulai pindah file Google Drive ke folder masing-masing...');
        $this->line('Dry run: ' . ($dryRun ? 'YA' : 'TIDAK'));
        $this->newLine();

        foreach ($targets as $target) {
            $this->processTable($target, $dryRun);
        }

        $this->newLine();
        $this->info('Selesai.');
        $this->line("Berhasil : {$this->success}");
        $this->line("Skip     : {$this->skipped}");
        $this->line("Gagal    : {$this->failed}");

        return self::SUCCESS;
    }

    private function processTable(array $target, bool $dryRun): void
    {
        if (!DB::getSchemaBuilder()->hasTable($target['table'])) {
            $this->warn("Tabel tidak ditemukan: {$target['table']}");
            return;
        }

        $this->info("Cek tabel: {$target['table']}");

        $rows = DB::table($target['table'])->get();

        foreach ($rows as $row) {
            foreach ($target['fields'] as $field => $folderPrefix) {
                if (!property_exists($row, $field)) {
                    continue;
                }

                $url = $row->{$field};

                if (!$url) {
                    $this->skipped++;
                    $this->line("SKIP kosong: {$target['table']}.{$field} ID {$row->{$target['pk']}}");
                    continue;
                }

                if (!str_contains($url, 'drive.google.com')) {
                    $this->skipped++;
                    $this->line("SKIP bukan link Google Drive: {$target['table']}.{$field} ID {$row->{$target['pk']}} => {$url}");
                    continue;
                }

                $fileId = $this->extractGoogleFileId($url);

                if (!$fileId) {
                    $this->failed++;
                    $this->error("Gagal ambil file ID: {$url}");
                    continue;
                }

                $folder = ModelDriveFolder::where('folder_prefix', $folderPrefix)
                    ->where('folder_status', 1)
                    ->first();

                if (!$folder) {
                    $this->failed++;
                    $this->error("Folder prefix belum diatur: {$folderPrefix}");
                    continue;
                }

                $this->line("Move {$target['table']}.{$field} ID {$row->{$target['pk']}} => {$folderPrefix}");

                if ($dryRun) {
                    $this->skipped++;
                    continue;
                }

                try {
                    $this->moveDriveFile($fileId, $folder->folder_drive_id);

                    $this->success++;
                    $this->info("OK: {$fileId}");
                } catch (\Throwable $e) {
                    $this->failed++;
                    $this->error("Gagal move {$fileId}: " . $e->getMessage());
                }
            }
        }
    }

    private function moveDriveFile(string $googleFileId, string $folderId): void
    {
        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->timeout(120)
            ->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/move-drive-file', [
                'google_file_id' => $googleFileId,
                'folder_id' => $folderId,
            ]);

        $result = $response->json();

        if (!$response->successful() || !($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? $response->body());
        }
    }

    private function extractGoogleFileId(string $url): ?string
    {
        if (preg_match('/\/file\/d\/([^\/]+)/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/id=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/open\?id=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}