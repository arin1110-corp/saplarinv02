<?php

namespace App\Console\Commands;

use App\Models\ModelDriveFolder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MoveAllDriveLinksToFolders extends Command
{
    protected $signature = 'arindrive:move-all-links {--dry-run}';

    protected $description = 'Pindahkan file link ArinDrive/Google Drive ke folder tujuan dan update DB menjadi link Google Drive';

    private int $success = 0;
    private int $failed = 0;
    private int $skipped = 0;

    public function handle()
    {
        if (!env('ARINDRIVE_URL') || !env('ARINDRIVE_TOKEN')) {
            $this->error('ARINDRIVE_URL / ARINDRIVE_TOKEN belum diisi.');
            return self::FAILURE;
        }

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

        $this->info('Mulai pindahkan semua link ke folder Google Drive tujuan...');
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
                    continue;
                }

                if (!str_contains($url, 'drive.google.com') && !str_contains($url, 'arindrive.saplarin.site')) {
                    $this->skipped++;
                    $this->line("SKIP bukan URL drive: {$target['table']}.{$field} ID {$row->{$target['pk']}}");
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

                try {
                    $resolved = $this->resolveFile($url);

                    if (!$resolved['google_file_id']) {
                        $this->failed++;
                        $this->error("Google file ID tidak ditemukan: {$url}");
                        continue;
                    }

                    $this->line("MOVE {$target['table']}.{$field} ID {$row->{$target['pk']}} => {$folderPrefix}");

                    if ($dryRun) {
                        $this->skipped++;
                        continue;
                    }

                    $moved = $this->moveFile($resolved['google_file_id'], $folder->folder_drive_id);

                    DB::table($target['table'])
                        ->where($target['pk'], $row->{$target['pk']})
                        ->update([
                            $field => $moved['url'] ?? $resolved['google_url'],
                        ]);

                    $this->success++;
                    $this->info('OK: ' . ($moved['url'] ?? $resolved['google_url']));
                } catch (\Throwable $e) {
                    $this->failed++;
                    $this->error("Gagal proses {$url}: " . $e->getMessage());
                }
            }
        }
    }

    private function resolveFile(string $url): array
    {
        if (str_contains($url, 'drive.google.com')) {
            return [
                'google_file_id' => $this->extractGoogleFileId($url),
                'google_url' => $url,
            ];
        }

        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->timeout(120)
            ->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/resolve-file', [
                'url' => $url,
            ]);

        $result = $response->json();

        if (!$response->successful() || !($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? $response->body());
        }

        return [
            'google_file_id' => $result['data']['google_file_id'],
            'google_url' => $result['data']['google_url'],
        ];
    }

    private function moveFile(string $googleFileId, string $folderId): array
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

        return $result['data'];
    }

    private function extractGoogleFileId(string $url): ?string
    {
        if (preg_match('/\/file\/d\/([^\/]+)/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/id=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}