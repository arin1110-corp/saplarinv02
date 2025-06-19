<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelGolongan;
use Illuminate\Database\Seeder;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Kosongkan tabel users dan reset auto-increment ID
        ModelGolongan::truncate();
        ModelGolongan::create(
        [
            'golongan_nama' => 'Juru Muda',
            'golongan_pangkat' => 'I/a',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Juru Muda Tingkat I',
            'golongan_pangkat' => 'I/b',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Juru',
            'golongan_pangkat' => 'I/c',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Juru Tingkat I',
            'golongan_pangkat' => 'I/d',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pengatur Muda',
            'golongan_pangkat' => 'II/a',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pengatur Muda Tingkat I',
            'golongan_pangkat' => 'II/b',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pengatur',
            'golongan_pangkat' => 'II/c',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pengatur Tingkat I',
            'golongan_pangkat' => 'II/d',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Penata Muda',
            'golongan_pangkat' => 'III/a',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Penata Muda Tingkat I',
            'golongan_pangkat' => 'III/b',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Penata',
            'golongan_pangkat' => 'III/c',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Penata Tingkat I',
            'golongan_pangkat' => 'III/d',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pembina',
            'golongan_pangkat' => 'IV/a',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pembina Tingkat I',
            'golongan_pangkat' => 'IV/b',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pembina Utama Muda',
            'golongan_pangkat' => 'IV/c',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pembina Utama',
            'golongan_pangkat' => 'IV/d',
            'golongan_status' => 1,
        ]);
        ModelGolongan::create(
        [
            'golongan_nama' => 'Pembina Utama',
            'golongan_pangkat' => 'IV/e',
            'golongan_status' => 1,
        ]);
    }
}