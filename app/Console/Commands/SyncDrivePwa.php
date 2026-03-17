<?php

namespace App\Console\Commands;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDrivePwa extends Command
{
    protected $signature = 'drive:sync-pwa';
    protected $description = 'Mapping file laporan PWA ke Google Drive';

    protected $fileMap = []; // nama file => fileId

    public function handle()
    {
        $this->info("Mulai scan Google Drive...");

        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/saplarin-drive_1.json'));
        $client->addScope(Drive::DRIVE_READONLY);
        $drive = new Drive($client);

        $rootFolderId = '17XYV-WPVhgJ_WV_05hPMhZ3tCaEd--Zt'; // folder laporanpwa di drive

        // scan seluruh folder secara rekursif dan buat mapping nama file
        $this->scanFolder($drive, $rootFolderId);

        $this->info("Scan selesai, total file: ".count($this->fileMap));

        $data = DB::table('saplarin_laporan_pwa')
            ->whereNotNull('laporan_pwa_file')
            ->get();

        foreach ($data as $row) {
            $path = $row->laporan_pwa_file;

            // hilangkan 'assets/' prefix
            $path = str_replace('assets/', '', $path);

            // ambil nama file
            $parts = explode('/', $path);
            if(count($parts) < 4) continue;

            $fileName = $parts[3];

            if(isset($this->fileMap[$fileName])) {
                $link = "https://drive.google.com/file/d/".$this->fileMap[$fileName]."/view";

                DB::table('saplarin_laporan_pwa')
                    ->where('laporan_pwa_id',$row->laporan_pwa_id)
                    ->update([
                        'laporan_pwa_file' => $link
                    ]);

                $this->info("Update berhasil: $fileName -> $link");
            } else {
                $this->warn("File tidak ditemukan di Drive: $fileName");
            }
        }

        $this->info("Selesai mapping file laporan PWA");
    }

    // fungsi rekursif untuk scan folder di Google Drive
    protected function scanFolder(Drive $drive, $folderId)
    {
        $pageToken = null;

        do {
            $response = $drive->files->listFiles([
                'q' => "'$folderId' in parents and trashed=false",
                'fields' => 'nextPageToken, files(id, name, mimeType)',
                'pageToken' => $pageToken
            ]);

            foreach($response->getFiles() as $file) {
                if($file->mimeType === 'application/vnd.google-apps.folder') {
                    // rekursif scan subfolder
                    $this->scanFolder($drive, $file->id);
                } else {
                    // simpan mapping nama file -> id
                    $this->fileMap[$file->name] = $file->id;
                }
            }

            $pageToken = $response->getNextPageToken();
        } while($pageToken != null);
    }
}