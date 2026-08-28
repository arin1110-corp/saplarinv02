<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ModelSPJRealisasi;

class FixSpjDriveUrl extends Command
{
    protected $signature = 'spj:fix-drive-url
                            {--dry-run : Hanya melihat perubahan tanpa update database}';

    protected $description = 'Mengubah URL SPJ ArinDrive menjadi URL Google Drive berdasarkan reference_id';

    public function handle(): int
    {
        $this->info('==============================================');
        $this->info(' MIGRASI URL SPJ ARINDRIVE -> GOOGLE DRIVE');
        $this->info('==============================================');
        $this->newLine();

        $dryRun = $this->option('dry-run');

        $spjs = ModelSPJRealisasi::query()->whereNotNull('spj_file')->where('spj_file', '!=', '')->orderBy('spj_id')->get();

        if ($spjs->isEmpty()) {
            $this->warn('Tidak ada data SPJ yang memiliki file.');

            return self::SUCCESS;
        }

        $this->info("Total SPJ: {$spjs->count()}");
        $this->newLine();

        $berhasil = 0;
        $dilewati = 0;
        $gagal = 0;

        foreach ($spjs as $spj) {
            $this->line("SPJ {$spj->spj_uid}");

            /*
            |--------------------------------------------------------------------------
            | HANYA PROSES LINK ARINDRIVE
            |--------------------------------------------------------------------------
            */

            if (!str_contains($spj->spj_file, 'arindrive')) {
                $this->comment('  - Bukan URL ArinDrive, dilewati.');

                $dilewati++;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CARI DRIVE FILE BERDASARKAN REFERENCE ID
            |--------------------------------------------------------------------------
            */

            $driveFile = DB::connection('arindrive')->table('drive_files')->where('reference_id', $spj->spj_uid)->where('source_app', 'saplarin')->orderByDesc('id')->first();

            if (!$driveFile) {
                $this->error('  X DriveFile tidak ditemukan.');

                $gagal++;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | GOOGLE FILE ID
            |--------------------------------------------------------------------------
            */

            if (empty($driveFile->google_file_id)) {
                $this->error('  X google_file_id kosong.');

                $gagal++;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | URL GOOGLE DRIVE
            |--------------------------------------------------------------------------
            */

            $googleUrl = 'https://drive.google.com/file/d/' . $driveFile->google_file_id . '/view';

            $this->line('  ArinDrive : ' . $spj->spj_file);

            $this->line('  Google    : ' . $googleUrl);

            $this->line('  Drive     : ' . $driveFile->drive_account_id);

            /*
            |--------------------------------------------------------------------------
            | UPDATE SAPLARIN
            |--------------------------------------------------------------------------
            */

            if (!$dryRun) {
                $spj->spj_file = $googleUrl;

                $spj->save();

                $this->info('  ✓ URL berhasil diperbarui.');
            } else {
                $this->comment('  [DRY RUN] Tidak ada perubahan.');
            }

            $berhasil++;

            $this->newLine();
        }

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->info('==============================================');
        $this->info(' HASIL MIGRASI');
        $this->info('==============================================');

        $this->line("Berhasil : {$berhasil}");

        $this->line("Dilewati : {$dilewati}");

        $this->line("Gagal    : {$gagal}");

        if ($dryRun) {
            $this->newLine();

            $this->warn('DRY RUN aktif. Tidak ada data yang diubah.');

            $this->info('Jika hasil sudah benar, jalankan tanpa --dry-run.');
        }

        return self::SUCCESS;
    }
}