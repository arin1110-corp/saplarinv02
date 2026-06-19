<?php

namespace App\Console\Commands;

use App\Models\ModelDriveFolder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SyncLocalFilesToArinDrive extends Command
{
    protected $signature = 'arindrive:sync-local {--dry-run} {--delete}';

    protected $description = 'Upload file lokal SAPLARIN ke folder Google Drive sesuai saplarin_drive_folder melalui ArinDrive API';

    private int $success = 0;
    private int $failed = 0;
    private int $skipped = 0;

    public function handle()
    {
        if (!env('ARINDRIVE_URL') || !env('ARINDRIVE_TOKEN')) {
            $this->error('ARINDRIVE_URL / ARINDRIVE_TOKEN belum diisi di .env');
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        $delete = $this->option('delete');

        $this->info('Mulai sinkron file lokal ke Google Drive via ArinDrive...');
        $this->line('Mode dry-run: ' . ($dryRun ? 'YA' : 'TIDAK'));
        $this->line('Hapus file lokal setelah sukses: ' . ($delete ? 'YA' : 'TIDAK'));
        $this->newLine();

        $targets = [
            [
                'table' => 'saplarin_bbm_pengajuan',
                'pk' => 'bbm_id',
                'uid' => 'bbm_uid',
                'fields' => [
                    'bbm_spt_file' => 'bbm_spt',
                    'bbm_acc_pimpinan_file' => 'bbm_acc',
                    'bbm_laporan_nota_file' => 'bbm_nota',
                ],
            ],
            [
                'table' => 'saplarin_laporan_aktivitas_bukti',
                'pk' => 'bukti_id',
                'uid' => null,
                'fields' => [
                    'bukti_file' => 'laporan_aktivitas',
                ],
            ],
            [
                'table' => 'saplarin_kinerja_progress_bukti',
                'pk' => 'bukti_id',
                'uid' => null,
                'fields' => [
                    'bukti_file' => 'kinerja',
                ],
            ],
            [
                'table' => 'saplarin_prioritas_bukti_file',
                'pk' => 'file_id',
                'uid' => null,
                'fields' => [
                    'file_path' => 'prioritas',
                ],
            ],
            [
                'table' => 'sadarin_program_prioritas_capaian_file',
                'pk' => 'file_id',
                'uid' => null,
                'fields' => [
                    'file_path' => 'program_prioritas',
                ],
            ],
            [
                'table' => 'saplarin_spj_realisasi',
                'pk' => 'spj_id',
                'uid' => 'spj_uid',
                'fields' => [
                    'spj_file' => 'spj',
                ],
            ],
        ];

        foreach ($targets as $target) {
            $this->processTable($target, $dryRun, $delete);
        }

        $this->newLine();
        $this->info('Selesai.');
        $this->line("Berhasil : {$this->success}");
        $this->line("Skip     : {$this->skipped}");
        $this->line("Gagal    : {$this->failed}");

        return self::SUCCESS;
    }

    private function processTable(array $target, bool $dryRun, bool $delete): void
    {
        $table = $target['table'];

        if (!DB::getSchemaBuilder()->hasTable($table)) {
            $this->warn("Skip table {$table}: tabel tidak ditemukan");
            return;
        }

        $this->info("Cek tabel: {$table}");

        $rows = DB::table($table)->get();

        foreach ($rows as $row) {
            foreach ($target['fields'] as $field => $folderPrefix) {
                if (!property_exists($row, $field)) {
                    continue;
                }

                $value = $row->{$field};

                if (!$value) {
                    $this->skipped++;
                    continue;
                }

                if (str_starts_with($value, 'https://drive.google.com')) {
                    $this->skipped++;
                    $this->line("SKIP sudah Google Drive: {$table}.{$field}");
                    continue;
                }

                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    $this->skipped++;
                    $this->line("SKIP sudah URL lain: {$table}.{$field} => {$value}");
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

                $relativePath = ltrim(parse_url($value, PHP_URL_PATH) ?: $value, '/');
                $fullPath = public_path($relativePath);

                if (!File::exists($fullPath)) {
                    $this->failed++;
                    $this->error("File tidak ditemukan: {$relativePath}");
                    continue;
                }

                $referenceId = $target['uid'] && property_exists($row, $target['uid'])
                    ? $row->{$target['uid']}
                    : ($table . '-' . $row->{$target['pk']});

                $filename = $this->makeFilename(
                    $folderPrefix,
                    $referenceId,
                    basename($relativePath)
                );

                $this->line("Upload: {$table}.{$field} => {$relativePath} | folder={$folderPrefix}");

                if ($dryRun) {
                    $this->skipped++;
                    continue;
                }

                try {
                    $uploaded = $this->uploadToDriveFolder(
                        $fullPath,
                        $filename,
                        $folder->folder_drive_id,
                        $folderPrefix,
                        $referenceId
                    );

                    DB::table($table)
                        ->where($target['pk'], $row->{$target['pk']})
                        ->update([
                            $field => $uploaded['url'],
                        ]);

                    if ($delete) {
                        File::delete($fullPath);
                    }

                    $this->success++;
                    $this->info("OK: {$uploaded['url']}");
                } catch (\Throwable $e) {
                    $this->failed++;
                    $this->error("Gagal upload {$relativePath}: " . $e->getMessage());
                }
            }
        }
    }

    private function uploadToDriveFolder(
        string $fullPath,
        string $filename,
        string $folderId,
        string $folderPrefix,
        string $referenceId
    ): array {
        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->timeout(300)
            ->attach(
                'file',
                fopen($fullPath, 'r'),
            $filename
            )
            ->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/upload-drive', [
                'folder_id' => $folderId,
                'filename' => $filename,
                'source_app' => 'saplarin',
            'folder' => $folderPrefix,
            'reference_id' => $referenceId,
            ]);

        if (!$response->successful()) {
            throw new \Exception($response->body());
        }

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? 'Upload ArinDrive gagal.');
        }

        return $result['data'];
    }

    private function makeFilename(string $folderPrefix, string $referenceId, string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $safeReference = preg_replace('/[^A-Za-z0-9_\-]/', '_', $referenceId);
        $safePrefix = preg_replace('/[^A-Za-z0-9_\-]/', '_', strtoupper($folderPrefix));

        return $safeReference . '_' . $safePrefix . '_' . date('Ymd_His') . '.' . $extension;
    }
}