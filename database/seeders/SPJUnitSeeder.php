<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ModelSPJUnit;

class SPJUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['unit_kode' => 'DISBUD', 'unit_nama' => 'Dinas Kebudayaan Provinsi Bali'],
            ['unit_kode' => 'TB', 'unit_nama' => 'UPTD Taman Budaya'],
            ['unit_kode' => 'MB', 'unit_nama' => 'UPTD Museum Bali'],
            ['unit_kode' => 'MPRB', 'unit_nama' => 'UPTD Monumen Perjuangan Rakyat Bali'],
        ];

        foreach ($units as $unit) {
            ModelSPJUnit::firstOrCreate(
                ['unit_kode' => $unit['unit_kode']],
                [
                    'unit_uid' => Str::uuid(),
                    'unit_nama' => $unit['unit_nama'],
                    'unit_status' => 1,
                ]
            );
        }
    }
}