<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_pad_subkomponen', function (Blueprint $table) {

            $table->bigIncrements('pad_subkomponen_id');

            $table->uuid('pad_subkomponen_uid')
                ->unique();

            $table->unsignedBigInteger('pad_subkomponen_komponen');

            $table->string('pad_subkomponen_kode', 100)
                ->nullable();

            $table->string('pad_subkomponen_nama', 255);

            $table->text('pad_subkomponen_keterangan')
                ->nullable();

            $table->boolean('pad_subkomponen_status')
                ->default(true);

            $table->timestamps();

            $table->foreign('pad_subkomponen_komponen')
                ->references('pad_komponen_id')
                ->on('saplarin_pad_komponen')
                ->cascadeOnDelete();

            $table->index(
                'pad_subkomponen_komponen'
            );

            $table->index(
                'pad_subkomponen_status'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'saplarin_pad_subkomponen'
        );
    }
};