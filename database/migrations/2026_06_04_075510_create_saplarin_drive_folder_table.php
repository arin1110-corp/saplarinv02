<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_drive_folder', function (Blueprint $table) {
            $table->id('folder_id');
            $table->string('folder_nama');
            $table->string('folder_prefix')->unique();
            $table->string('folder_drive_id');
            $table->unsignedBigInteger('folder_json');
            $table->tinyInteger('folder_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_drive_folder');
    }
};