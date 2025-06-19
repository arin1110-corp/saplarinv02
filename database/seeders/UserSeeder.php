<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Modeluser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Kosongkan tabel users dan reset auto-increment ID
       Modeluser::truncate();
       Modeluser::create([
           'user_nama' => 'John Doe',
           'user_notelp' => '08123456789',
           'user_jk' => 'L',
           'user_golongan' => '7',
           'user_jabatan' => '1',
           'user_bidang' => '1',
           'user_foto' => 'john_doe.jpg',
           'user_email' => 'indraardika@gmail.com',
           'user_nip' => '199510112020121001',
           'user_password' => Hash::make('password123'), // Pastikan password di-hash
           'user_status' => '1',
       ]);
    }
}