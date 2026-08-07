<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeederRuang extends Seeder
{
    public function run(): void
    {
        DB::table('saplarin_ruang')->insert([

            [

                'ruang_uid' => Str::uuid(),

                'ruang_nama' => 'Ruang Rapat Padma',

                'ruang_lokasi' => 'Gedung I',

                'ruang_kapasitas' => 30,

                'ruang_status' => 1,

                'created_at' => now(),

                'updated_at' => now(),

            ],

            [

                'ruang_uid' => Str::uuid(),

                'ruang_nama' => 'Ruang Rapat Widyasabha',

                'ruang_lokasi' => 'Gedung II Lantai 3',

                'ruang_kapasitas' => 15,

                'ruang_status' => 1,

                'created_at' => now(),

                'updated_at' => now(),

            ],

        ]);
    }
}