<?php

namespace App\Services;

use App\Models\ModelPermintaanKAK;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KAKEmailService
{
    /**
     * ============================================================
     * KIRIM CATATAN KAK KE PENGAJU
     * ============================================================
     */
    public function kirimKePengaju(ModelPermintaanKAK $kak): void
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER DARI DATABASE SAMPERIN
        |--------------------------------------------------------------------------
        */

        $user = DB::connection('samperin')->table('samperin_user')->where('user_id', $kak->kak_created_by)->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA USER TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            throw new \Exception('Data user SAMPERIN tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL EMAIL
        |--------------------------------------------------------------------------
        */

        $email = $user->user_email ?? null;

        if (!$email) {
            throw new \Exception('Email user SAMPERIN tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | SUBJECT
        |--------------------------------------------------------------------------
        */

        $subject = 'Catatan Admin Permintaan KAK - ' . ($kak->kak_tahun ?? '-');

        /*
        |--------------------------------------------------------------------------
        | HTML EMAIL
        |--------------------------------------------------------------------------
        */

        $html = $this->templateCatatan($kak);

        /*
        |--------------------------------------------------------------------------
        | KIRIM EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::html($html, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    /**
     * ============================================================
     * TEMPLATE EMAIL CATATAN KAK
     * ============================================================
     */
    private function templateCatatan(ModelPermintaanKAK $kak): string
    {
        $nama = e($kak->kak_created_by_nama ?? 'Pegawai');

        $tahun = e($kak->kak_tahun ?? '-');

        $tahapan = e($kak->kak_tahapan ?? '-');

        $unit = e($kak->kak_unit ?? '-');

        $catatan = nl2br(e($kak->kak_catatan_admin ?? '-'));

        return <<<HTML
        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1.0"
            >

            <title>Catatan Admin KAK</title>
        </head>

        <body
            style="
                margin:0;
                padding:0;
                background:#f3f4f6;
                font-family:Arial, Helvetica, sans-serif;
            "
        >

        <table
            width="100%"
            cellpadding="0"
            cellspacing="0"
            border="0"
            style="
                background:#f3f4f6;
                padding:30px 15px;
            "
        >

        <tr>
        <td align="center">

        <table
            width="600"
            cellpadding="0"
            cellspacing="0"
            border="0"
            style="
                width:100%;
                max-width:600px;
                background:#ffffff;
                border-radius:12px;
                overflow:hidden;
            "
        >

        <!-- ========================================================= -->
        <!-- HEADER -->
        <!-- ========================================================= -->

        <tr>
        <td
            style="
                background:#0f172a;
                padding:28px 30px;
                text-align:center;
            "
        >

        <div
            style="
                color:#ffffff;
                font-size:24px;
                font-weight:bold;
                letter-spacing:.5px;
            "
        >
            SAPLARIN
        </div>

        <div
            style="
                margin-top:6px;
                color:#cbd5e1;
                font-size:13px;
            "
        >
            Sistem Administrasi Pelaporan Internal
        </div>

        </td>
        </tr>


        <!-- ========================================================= -->
        <!-- CONTENT -->
        <!-- ========================================================= -->

        <tr>
        <td
            style="
                padding:30px;
            "
        >

        <div
            style="
                font-size:21px;
                font-weight:bold;
                color:#111827;
                margin-bottom:8px;
            "
        >
            Catatan Admin
        </div>

        <div
            style="
                font-size:14px;
                color:#6b7280;
                line-height:1.6;
                margin-bottom:25px;
            "
        >
            Admin telah memberikan catatan terhadap
            Permintaan KAK Anda.
        </div>


        <p
            style="
                margin:0 0 16px 0;
                font-size:14px;
                line-height:1.7;
                color:#374151;
            "
        >
            Yth. <strong>{$nama}</strong>,
        </p>


        <p
            style="
                margin:0 0 22px 0;
                font-size:14px;
                line-height:1.7;
                color:#4b5563;
            "
        >
            Terdapat catatan dari administrator terkait
            Permintaan KAK yang Anda ajukan.
            Berikut detailnya:
        </p>


        <!-- ========================================================= -->
        <!-- DETAIL -->
        <!-- ========================================================= -->

        <table
            width="100%"
            cellpadding="0"
            cellspacing="0"
            border="0"
            style="
                border:1px solid #e5e7eb;
                border-radius:8px;
                overflow:hidden;
                margin-bottom:22px;
            "
        >

        <tr>

        <td
            colspan="2"
            style="
                padding:14px 16px;
                background:#f8fafc;
                border-bottom:1px solid #e5e7eb;
                font-size:14px;
                font-weight:bold;
                color:#111827;
            "
        >
            Detail Permintaan KAK
        </td>

        </tr>


        <tr>

        <td
            width="35%"
            style="
                padding:11px 16px;
                font-size:13px;
                color:#6b7280;
                border-bottom:1px solid #f1f5f9;
            "
        >
            Tahun
        </td>

        <td
            style="
                padding:11px 16px;
                font-size:13px;
                font-weight:bold;
                color:#111827;
                border-bottom:1px solid #f1f5f9;
            "
        >
            {$tahun}
        </td>

        </tr>


        <tr>

        <td
            style="
                padding:11px 16px;
                font-size:13px;
                color:#6b7280;
                border-bottom:1px solid #f1f5f9;
            "
        >
            Tahapan
        </td>

        <td
            style="
                padding:11px 16px;
                font-size:13px;
                color:#111827;
                border-bottom:1px solid #f1f5f9;
            "
        >
            {$tahapan}
        </td>

        </tr>


        <tr>

        <td
            style="
                padding:11px 16px;
                font-size:13px;
                color:#6b7280;
            "
        >
            Unit
        </td>

        <td
            style="
                padding:11px 16px;
                font-size:13px;
                color:#111827;
            "
        >
            {$unit}
        </td>

        </tr>

        </table>


        <!-- ========================================================= -->
        <!-- CATATAN -->
        <!-- ========================================================= -->

        <div
            style="
                background:#fff7ed;
                border:1px solid #fed7aa;
                border-left:4px solid #f97316;
                border-radius:8px;
                padding:18px;
                margin-bottom:24px;
            "
        >

        <div
            style="
                font-size:13px;
                font-weight:bold;
                color:#9a3412;
                margin-bottom:10px;
            "
        >
            Catatan Admin
        </div>

        <div
            style="
                font-size:14px;
                line-height:1.7;
                color:#431407;
                word-break:break-word;
            "
        >
            {$catatan}
        </div>

        </div>


        <p
            style="
                margin:0;
                font-size:14px;
                line-height:1.7;
                color:#4b5563;
            "
        >
            Silakan login ke
            <strong>SAPLARIN</strong>
            untuk melihat informasi lebih lanjut
            dan melakukan tindak lanjut apabila diperlukan.
        </p>

        </td>
        </tr>


        <!-- ========================================================= -->
        <!-- FOOTER -->
        <!-- ========================================================= -->

        <tr>

        <td
            style="
                background:#f8fafc;
                border-top:1px solid #e5e7eb;
                padding:20px 30px;
                text-align:center;
            "
        >

        <div
            style="
                font-size:12px;
                line-height:1.6;
                color:#6b7280;
            "
        >
            Email ini dikirim secara otomatis oleh sistem
            <strong>SAPLARIN</strong>.
        </div>

        <div
            style="
                margin-top:6px;
                font-size:11px;
                color:#9ca3af;
            "
        >
            Dinas Kebudayaan Provinsi Bali
        </div>

        </td>

        </tr>

        </table>

        </td>
        </tr>

        </table>

        </body>
        </html>
        HTML;
    }
}