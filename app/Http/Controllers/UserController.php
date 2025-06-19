<?php

namespace App\Http\Controllers;

use App\Models\ModelUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'user_nip' => 'required',
            'user_password' => 'required',
        ]);

        $user = ModelUser::where('user_nip', $request->user_nip)
            ->orWhere('user_email', $request->user_nip)
            ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return response()->json(['error' => 'NIP atau email tidak ditemukan'], 401);
        }

        // Cek apakah password benar
        if (!Hash::check($request->user_password, $user->user_password)) {
            return response()->json(['error' => 'Password salah'], 401);
        }

        // Cek status user
        if ($user->user_status != 1) {
            return response()->json(['error' => 'Akun tidak aktif'], 401);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        // Ambil token yang baru saja dibuat dan update expired_at-nya
        $tokenModel = $user->tokens()->latest()->first();
        $tokenModel->expires_at = Carbon::now()->addHours(2);
        $tokenModel->save();

        return response()->json([
            'token' => $token,
            'role' => 'user',
            'expires_at' => $tokenModel->expires_at->setTimezone('Asia/Jakarta')->toDateTimeString() // Convert to Jakarta timezone
        ]);
    }
}
