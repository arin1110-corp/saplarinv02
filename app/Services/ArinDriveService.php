<?php

namespace App\Services;

use App\Models\ModelDriveFolder;
use Illuminate\Support\Facades\Http;

class ArinDriveService
{
    /*
    |--------------------------------------------------------------------------
    | UPLOAD BIASA
    |--------------------------------------------------------------------------
    |
    | JANGAN DIUBAH
    |
    */

    public function upload($file, string $folderPrefix, string $filename, ?string $referenceId = null): string
    {
        $folder = ModelDriveFolder::where('folder_prefix', $folderPrefix)->where('folder_status', 1)->first();

        if (!$folder) {
            throw new \Exception("Folder {$folderPrefix} belum diatur.");
        }

        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->timeout(300)
            ->attach('file', fopen($file->getRealPath(), 'r'), $filename)
            ->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/upload-drive', [
                'folder_id' => $folder->folder_drive_id,

                'filename' => $filename,

                'source_app' => 'saplarin',

                'folder' => $folderPrefix,

                'reference_id' => $referenceId,
            ]);

        $result = $response->json();

        if (!$response->successful() || !($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? $response->body());
        }

        return $result['data']['url'];
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD SPJ
    |--------------------------------------------------------------------------
    |
    | KHUSUS SPJ
    |
    */

    public function uploadSPJ($file, string $folderPrefix, string $filename, ?string $referenceId = null): string
    {
        /*
        |--------------------------------------------------------------------------
        | CARI FOLDER DARI MASTER ARINDRIVE
        |--------------------------------------------------------------------------
        */

        $folder = ModelDriveFolder::where('folder_prefix', $folderPrefix)->where('folder_status', 1)->first();

        if (!$folder) {
            throw new \Exception("Folder {$folderPrefix} belum diatur.");
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN FOLDER ID TERISI
        |--------------------------------------------------------------------------
        */

        if (empty($folder->folder_drive_id)) {
            throw new \Exception("Folder {$folderPrefix} belum memiliki Google Drive Folder ID.");
        }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD KE ARINDRIVE
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken(env('ARINDRIVE_TOKEN'))
            ->timeout(300)
            ->attach('file', fopen($file->getRealPath(), 'r'), $filename)
            ->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/upload-drive-spj', [
                /*
                    |--------------------------------------------------------------------------
                    | INI FOLDER GOOGLE DRIVE TUJUAN
                    |--------------------------------------------------------------------------
                    */

                'folder_id' => $folder->folder_drive_id,

                'filename' => $filename,

                'source_app' => 'saplarin',

                'folder' => $folderPrefix,

                'reference_id' => $referenceId,
            ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        $result = $response->json();

        if (!$response->successful() || !($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? $response->body());
        }

        /*
        |--------------------------------------------------------------------------
        | GOOGLE FILE ID
        |--------------------------------------------------------------------------
        |
        | Prioritaskan google_file_id.
        |
        | Dengan begitu SAPLARIN tidak menyimpan:
        |
        | arindrive.saplarin.site/xxxxx
        |
        | tetapi:
        |
        | drive.google.com/file/xxxxx/view
        |
        */

        $googleFileId = $result['data']['google_file_id'] ?? null;

        if ($googleFileId) {
            return 'https://drive.google.com/file/d/' . $googleFileId . '/view';
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        |
        | Kalau endpoint lama belum mengembalikan
        | google_file_id, gunakan URL yang diberikan.
        |
        */

        $url = $result['data']['url'] ?? null;

        if ($url) {
            return $url;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE TIDAK LENGKAP
        |--------------------------------------------------------------------------
        */

        throw new \Exception('Upload SPJ berhasil tetapi URL file tidak ditemukan dari ArinDrive.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(string $referenceId): bool
    {
        $response = Http::withToken(env('ARINDRIVE_TOKEN'))->post(rtrim(env('ARINDRIVE_URL'), '/') . '/api/delete-drive', [
            'reference_id' => $referenceId,
        ]);

        $result = $response->json();

        if (!$response->successful() || !($result['success'] ?? false)) {
            throw new \Exception($result['message'] ?? $response->body());
        }

        return true;
    }
}