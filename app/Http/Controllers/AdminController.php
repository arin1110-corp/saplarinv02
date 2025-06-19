<?php

namespace App\Http\Controllers;

use App\Models\ModelAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class AdminController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'admin_username' => 'required',
            'admin_password' => 'required',
        ]);

        $admin = ModelAdmin::where('admin_username', $request->admin_username)->first();

        // Jika admin tidak ditemukan
        if (!$admin) {
            return response()->json(['error' => 'Username tidak ditemukan'], 401);
        }

        // Cek apakah password benar
        if (!Hash::check($request->admin_password, $admin->admin_password)) {
            return response()->json(['error' => 'Password salah'], 401);
        }

        // Cek status admin
        if ($admin->admin_status != 1) {
            return response()->json(['error' => 'Akun tidak aktif'], 401);
        }


        $token = $admin->createToken('admin-token')->plainTextToken;

        // Ambil token yang baru saja dibuat dan update expired_at-nya
        $tokenModel = $admin->tokens()->latest()->first();
        $tokenModel->expires_at = Carbon::now()->addHours(2);
        $tokenModel->save();

        return response()->json([
            'token' => $token,
            'role' => 'admin',
            'expires_at' => $tokenModel->expires_at->setTimezone('Asia/Jakarta')->toDateTimeString() // Convert to Jakarta timezone
        ]);
    }
}
