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
        Schema::create('saplarin_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_nip', 100);
            $table->string('user_nama', 100);
            $table->string('user_email',100);
            $table->string('user_notelp', 100);
            $table->string('user_jk', 1);
            $table->string('user_golongan', 50);
            $table->integer('user_jabatan');
            $table->string('user_password', 100);
            $table->text('user_foto');
            $table->string('user_bidang', 100);
            $table->integer('user_status');
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