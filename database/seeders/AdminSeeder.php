<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelAdmin::truncate();
        ModelAdmin::create(
        [
            'admin_username' => 'arintech',
            'admin_password' => Hash::make('P@ssword12345'), // Pastikan password di-hash
            'admin_status' => 1,
        ]);
    }
}