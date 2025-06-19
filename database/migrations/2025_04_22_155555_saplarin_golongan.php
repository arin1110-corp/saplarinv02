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
        Schema::create('saplarin_golongan', function (Blueprint $table) {
            $table->increments('golongan_id');
            $table->string('golongan_nama', 100);
            $table->string('golongan_pangkat', 100);
            $table->integer('golongan_status');
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