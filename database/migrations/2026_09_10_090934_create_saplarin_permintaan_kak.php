<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saplarin_permintaan_kak', function (Blueprint $table) {
            $table->id('kak_id');
            $table->uuid('kak_uid')->unique();

            $table->unsignedBigInteger('kak_sub_kegiatan_id');
            $table->year('kak_tahun');
            $table->string('kak_keterangan');
            $table->string('kak_tahapan');
            $table->string('kak_file')->nullable();

            $table->tinyInteger('kak_status')->default(1);
            $table->string('kak_created_by')->nullable();
            $table->string('kak_created_by_nama')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saplarin_permintaan_kak');
    }
};