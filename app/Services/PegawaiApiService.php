<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PegawaiApiService
{
    public function getPegawai($id)
    {
        try {

            $response = Http::timeout(10)
                ->get(
                    env('SADARIN_API')
                    . '/pegawaicek/'
                    . $id
                );

            if (!$response->successful()) {
                return null;
            }

            return $response->json('data');

        } catch (\Exception $e) {

            return null;
        }
    }
}