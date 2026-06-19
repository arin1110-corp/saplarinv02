<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SyncLocalFilesToArinDrive extends Command
{
    protected $signature = 'arindrive:sync-local {--dry-run} {--delete}';

    protected $description = 'Upload semua file lokal SAPLARIN ke ArinDrive lalu update path database menjadi URL ArinDrive';

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

        $this->info('Mulai sinkron file lokal ke ArinDrive...');
        $this->line('Mode dry-run: ' . ($dryRun ? 'YA' : 'TIDAK'));
        $this->line('Hapus file lokal setelah sukses: ' . ($delete ? 'YA' : 'TIDAK'));
        $this->newLine();

        $targets = [
            [
                'table' => 'saplarin_bbm_pengajuan',
                'pk' => 'bbm_id',
                'uid' => 'bbm_uid',
                'fields' => [
                    'bbm_spt_file' => 'bbm/spt',
                    'bbm_acc_pimpinan_file' => 'bbm/acc-pimpinan',
                    'bbm_laporan_nota_file' => 'bbm/nota',
                ],
            ],
            [
                'table' => 'saplarin_laporan_aktivitas_bukti',
                'pk' => 'bukti_id',
                'uid' => null,
                'fields' => [
                    'bukti_file' => 'laporan-aktivitas',
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
                    'file_path' => 'program-prioritas',
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
        $this->info("Selesai.");
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
            foreach ($target['fields'] as $field => $folder) {
                if (!property_exists($row, $field)) {
                    continue;
                }

                $value = $row->{$field};

                if (!$value) {
                    $this->skipped++;
                    continue;
                }

                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    $this->skipped++;
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

                $jenis = str_replace(['/', '_'], '-', $folder);

                $this->line("Upload: {$table}.{$field} => {$relativePath}");

                if ($dryRun) {
                    $this->skipped++;
                    continue;
                }

                try {
                    $uploaded = $this->uploadToArinDrive(
                        $fullPath,
                        basename($relativePath),
                        $folder,
                        $referenceId,
                        $jenis
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

    private function uploadToArinDrive(string $fullPath, string $originalName, string $folder, string $referenceId, string $jenis): array
    {
        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->timeout(120)
            ->attach(
                'file',
                fopen($fullPath, 'r'),
            $originalName
            )
            ->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/upload', [
                'group' => env('ARINDRIVE_GROUP', 'kantor'),
                'source_app' => 'saplarin',
            'folder' => $folder,
            'reference_id' => $referenceId,
            'jenis' => $jenis,
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
}