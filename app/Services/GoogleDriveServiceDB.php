<?php

namespace App\Services;

class GoogleDriveServiceDB
{
    protected $service;

    public function setCredential($jsonPath)
    {
        $client = new \Google\Client();
        $client->setAuthConfig($jsonPath);
        $client->addScope(\Google\Service\Drive::DRIVE_READONLY);

        $this->service = new \Google\Service\Drive($client);
    }

    public function findFileByKeyword($keyword, $folderId)
{
    try {
            $query = "'" . $folderId . "' in parents and trashed = false";

        $results = $this->service->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name, webViewLink)',
                'pageSize' => 100,
        ]);

        $files = $results->getFiles();

            foreach ($files as $file) {
                if (str_contains(strtolower($file->getName()), strtolower($keyword))) {
                    return [
                        'status' => 1,
                        'file_id' => $file->getId(),
                        'file_name' => $file->getName(),
                        'file_url' => $file->getWebViewLink(),
                    ];
                }
        }

        return [
            'status' => 0,
            'message' => 'File tidak ditemukan',
                'keyword' => $keyword,
                'total_file_dibaca' => count($files),
        ];
    } catch (\Exception $e) {
        return [
            'status' => 0,
            'message' => $e->getMessage(),
        ];
    }
}
}