<?php

namespace App\Services;

use App\Models\ModelSHS;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class SHSEmailService
{
    /**
     * Mengirim email catatan perbaikan kepada operator SHS.
     */
    public function kirimKeOperator(ModelSHS $shs, string $subject, string $body): bool
    {
        $email = $this->getEmailOperator($shs);

        if (!$email) {
            Log::warning('Email perbaikan SHS tidak dikirim karena email operator tidak ditemukan.', [
                'shs_uid' => $shs->shs_uid,
                'shs_operator_id' => $shs->shs_operator_id,
            ]);

            return false;
        }

        try {
            $html = View::make('emails.shs.minta-perbaikan', [
                'namaOperator' => $shs->shs_operator_nama,
                'tahun' => $shs->shs_tahun,
                'unit' => $shs->shs_unit_nama,
                'kodeKelompok' => $shs->shs_kode_kelompok,
                'kelompokBarang' => $shs->shs_kelompok_barang,
                'barang' => $shs->shs_barang,
                'merek' => $shs->shs_merek,
                'tipe' => $shs->shs_tipe,
                'spesifikasi' => $shs->shs_spesifikasi,
                'satuan' => $shs->shs_satuan,
                'tkdn' => $shs->shs_tkdn,
                'harga' => $shs->shs_harga,
                'dasarUsulan' => $shs->shs_dasar_usulan,
                'keterangan' => $shs->shs_keterangan,
                'catatan' => $shs->shs_catatan_admin,
                'urlAplikasi' => rtrim((string) config('app.url'), '/'),
            ])->render();

            Mail::html($html, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email perbaikan SHS.', [
                'shs_uid' => $shs->shs_uid,
                'email_tujuan' => $email,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Mengambil email operator dari API SADARIN.
     */
    private function getEmailOperator(ModelSHS $shs): ?string
    {
        if (!$shs->shs_operator_id) {
            return null;
        }

        $baseUrl = rtrim((string) env('SADARIN_API'), '/');

        if ($baseUrl === '') {
            Log::warning('SADARIN_API belum dikonfigurasi untuk pengiriman email SHS.');

            return null;
        }

        try {
            $response = Http::timeout(10)->get($baseUrl . '/pegawai');

            if (!$response->successful()) {
                Log::warning('Gagal mengambil data pegawai dari API SADARIN.', [
                    'http_status' => $response->status(),
                    'shs_operator_id' => $shs->shs_operator_id,
                ]);

                return null;
            }

            $pegawai = collect($response->json()['data'] ?? []);

            $operator = $pegawai->first(function ($item) use ($shs) {
                return (string) ($item['id'] ?? '') === (string) $shs->shs_operator_id;
            });

            $email = trim((string) ($operator['email'] ?? ''));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::warning('Alamat email operator SHS tidak ditemukan atau tidak valid.', [
                    'shs_uid' => $shs->shs_uid,
                    'shs_operator_id' => $shs->shs_operator_id,
                ]);

                return null;
            }

            return $email;
        } catch (\Throwable $e) {
            Log::error('Terjadi kesalahan saat mengambil email operator SHS.', [
                'shs_uid' => $shs->shs_uid,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}