<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_shs_satuan', function (Blueprint $table) {

            $table->id('satuan_id');

            $table->uuid('satuan_uid')->unique();

            $table->string('satuan_nama',100)->unique();

            $table->boolean('satuan_status')->default(true);

            $table->string('created_by')->nullable();

            $table->string('updated_by')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_shs_satuan');
    }
};