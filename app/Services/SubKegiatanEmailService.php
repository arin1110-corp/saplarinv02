<?php

namespace App\Services;

use App\Models\SubKegiatanLaporan;
use Illuminate\Support\Facades\Mail;

class SubKegiatanEmailService
{
    public function kirimCatatan(
        SubKegiatanLaporan $laporan,
        string $email,
        string $nama,
        string $catatan
    )
    {
        $subKegiatan =
            $laporan->subKegiatan
            ?->sub_kegiatan_nama
            ?? '-';

        $subject =
            'CATATAN LAPORAN SUB KEGIATAN';

        $body =
            "Yth. {$nama},\n\n" .

            "Terdapat catatan yang dikirim oleh Admin terkait Laporan Sub Kegiatan yang telah Anda input.\n\n" .

            "SUB KEGIATAN : {$subKegiatan}\n" .
            "BULAN        : {$laporan->laporan_bulan}\n" .
            "TAHUN        : {$laporan->laporan_tahun}\n\n" .

            "CATATAN      : {$catatan}\n" .


            
            "=================================\n" .
          
            "=================================\n\n" .

            "Silakan login ke SAPLARIN untuk melihat dan melakukan tindak lanjut.\n\n" .

            "Terima kasih.\n\n" .

            "SAPLARIN\n" .
            "Sistem Administrasi Pelaporan Internal\n" .
            "Dinas Kebudayaan Provinsi Bali";

        Mail::raw(
            $body,
            function ($message)
            use (
                $email,
                $subject
            ) {

                $message
                    ->to($email)
                    ->subject($subject);
            }
        );
    }
}