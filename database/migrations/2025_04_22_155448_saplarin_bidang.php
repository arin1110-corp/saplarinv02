<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('saplarin_bidang', function (Blueprint $table) {
            $table->increments('bidang_id');
            $table->string('bidang_nama', 100);
            $table->string('bidang_instansi', 100);
            $table->integer('bidang_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};