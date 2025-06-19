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
        Schema::create('saplarin_verificator', function (Blueprint $table) {
            $table->increments('verificator_id');
            $table->string('verificator_nip', 100);
            $table->string('verificator_nama', 100);
            $table->string('verificator_email',100);
            $table->string('verificator_notelp', 100);
            $table->string('verificator_jk', 1);
            $table->string('verificator_golongan', 50);
            $table->integer('verificator_jabatan');
            $table->string('verificator_password', 200);
            $table->text('verificator_foto');
            $table->integer('verificator_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saplarin_verificator');
    }
};