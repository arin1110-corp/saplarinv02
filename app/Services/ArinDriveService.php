<?php

namespace App\Services;

use App\Models\ModelDriveFolder;
use Illuminate\Support\Facades\Http;

class ArinDriveService
{
    public function upload($file, string $folderPrefix, string $filename, ?string $referenceId = null): string
    {
        $folder = ModelDriveFolder::where('folder_prefix', $folderPrefix)
            ->where('folder_status', 1)
            ->first();

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
}