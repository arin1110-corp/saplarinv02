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
        //
        Schema::create('saplarin_sub_kegiatan', function (Blueprint $table) {
            $table->id('sub_kegiatan_id');
            $table->string('sub_kegiatan_uid')->unique();
            $table->string('sub_kegiatan_kegiatan');
            $table->string('sub_kegiatan_nama');
            $table->integer('sub_kegiatan_status')->default(1);
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
        //
        Schema::dropIfExists('saplarin_sub_kegiatan');
    }
};