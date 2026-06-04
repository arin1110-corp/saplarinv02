<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveServiceDB
{
    private $service;

    public function __construct(Drive $service)
    {
        $this->service = $service;
    }

    public function findFileByKeyword($keyword, $folderId)
{
    try {
        $query = "'" . $folderId . "' in parents and trashed = false and name contains '" . $keyword . "'";

        $results = $this->service->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name, webViewLink)',
            'pageSize' => 1,
        ]);

        $files = $results->getFiles();

        if (count($files) > 0) {
            $file = $files[0];

            return [
                'status' => 1,
                'file_id' => $file->getId(),
                'file_name' => $file->getName(),
                'file_url' => $file->getWebViewLink(),
            ];
        }

        return [
            'status' => 0,
            'message' => 'File tidak ditemukan',
        ];
    } catch (\Exception $e) {
        return [
            'status' => 0,
            'message' => $e->getMessage(),
        ];
    }
}
}