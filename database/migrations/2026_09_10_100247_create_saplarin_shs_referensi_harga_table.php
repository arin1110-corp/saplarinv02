<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Buat tabel referensi harga
        |--------------------------------------------------------------------------
        */

        Schema::create('saplarin_shs_referensi_harga', function (Blueprint $table) {
            $table->bigIncrements('shs_referensi_id');

            $table->uuid('shs_referensi_uid')->unique();

            $table->unsignedBigInteger('shs_id');

            $table->decimal('shs_referensi_harga', 15, 2);

            $table->text('shs_referensi_link');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Foreign Key
            |--------------------------------------------------------------------------
            */

            $table->foreign('shs_id')->references('shs_id')->on('saplarin_shs')->cascadeOnDelete();

            $table->index('shs_id');
        });

        /*
        |--------------------------------------------------------------------------
        | MIGRASI DATA LAMA
        |--------------------------------------------------------------------------
        |
        | Data lama:
        |
        | saplarin_shs.shs_link_survei
        |
        | Contoh:
        |
        | https://tokopedia.com/produk
        | https://shopee.co.id/produk
        |
        | Setiap link akan menjadi satu record.
        |
        | Harga referensi menggunakan:
        |
        | saplarin_shs.shs_harga
        |
        */

        $dataLama = DB::table('saplarin_shs')->select('shs_id', 'shs_harga', 'shs_link_survei')->whereNotNull('shs_link_survei')->where('shs_link_survei', '!=', '')->get();

        foreach ($dataLama as $shs) {
            /*
            |--------------------------------------------------------------------------
            | Pecah link berdasarkan baris
            |--------------------------------------------------------------------------
            */

            $links = preg_split('/\r\n|\r|\n/', trim($shs->shs_link_survei));

            foreach ($links as $link) {
                $link = trim($link);

                if ($link === '') {
                    continue;
                }

                DB::table('saplarin_shs_referensi_harga')->insert([
                    'shs_referensi_uid' => (string) Str::uuid(),

                    'shs_id' => $shs->shs_id,

                    /*
                    | Harga lama dijadikan harga referensi awal
                    */

                    'shs_referensi_harga' => $shs->shs_harga,

                    'shs_referensi_link' => $link,

                    'created_at' => now(),

                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_shs_referensi_harga');
    }
};