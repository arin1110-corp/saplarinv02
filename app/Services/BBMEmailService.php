<?php

namespace App\Services;

use App\Models\ModelBBM;
use App\Models\ModelUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BBMEmailService
{
    public function kirimKePengaju(ModelBBM $bbm, string $subject, string $body): void
    {
        if (!$bbm->bbm_pengaju_email) {
            return;
        }

        Mail::raw($body, function ($message) use ($bbm, $subject) {
            $message->to($bbm->bbm_pengaju_email)
                ->subject($subject);
        });
    }

    public function kirimKeAdminBBM(string $subject, string $body): void
    {
        $adminEmails = $this->getEmailAdminBBM();

        foreach ($adminEmails as $email) {
            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)
                    ->subject($subject);
            });
        }
    }

    private function getEmailAdminBBM(): array
    {
        $adminUids = ModelUser::whereIn('user_role', [
                'Admin BBM',
            ])
            ->pluck('user_uid')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        if (empty($adminUids)) {
            return [];
        }

        $response = Http::get(env('SADARIN_API') . '/pegawai');

        if (!$response->ok()) {
            return [];
        }

        $pegawai = collect($response->json()['data'] ?? []);

        return $pegawai
            ->whereIn('id', $adminUids)
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
}