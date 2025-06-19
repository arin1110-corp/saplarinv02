<?php

namespace App\Http\Controllers;

use App\Models\ModelVerificator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class VerificatorController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'verificator_nip' => 'required',
            'verificator_password' => 'required',
        ]);

        $verificator = ModelVerificator::where('verificator_nip', $request->verificator_nip)
            ->orWhere('verificator_email', $request->verificator_nip)
            ->first();

        // Jika verificator tidak ditemukan
        if (!$verificator) {
            return response()->json(['error' => 'NIP atau email tidak ditemukan'], 401);
        }

        // Cek apakah password benar
        if (!Hash::check($request->verificator_password, $verificator->verificator_password)) {
            return response()->json(['error' => 'Password salah'], 401);
        }

        // Cek status verificator
        if ($verificator->verificator_status != 1) {
            return response()->json(['error' => 'Akun tidak aktif'], 401);
        }

        $token = $verificator->createToken('verificator-token')->plainTextToken;

        // Ambil token yang baru saja dibuat dan update expired_at-nya
        $tokenModel = $verificator->tokens()->latest()->first();
        $tokenModel->expires_at = Carbon::now()->addHours(2);
        $tokenModel->save();

        return response()->json([
            'token' => $token,
            'role' => 'verificator',
            'expires_at' => $tokenModel->expires_at->setTimezone('Asia/Jakarta')->toDateTimeString() // Convert to Jakarta timezone
        ]);
    }
}
