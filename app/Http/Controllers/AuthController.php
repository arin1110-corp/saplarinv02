<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login untuk User
    public function loginUser(Request $request)
    {
        $credentials = $request->only('user_email', 'user_password');
        
        // Cek menggunakan user_email atau user_nip
        $user = Auth::guard('web')->attempt($credentials);
        if (!$user) {
            $user = Auth::guard('web')->attempt(['user_nip' => $request->user_nip, 'user_password' => $request->user_password]);
        }

        if ($user) {
            return response()->json(['user' => $user, 'message' => 'Login sukses!'], 200);
        }

        return response()->json(['message' => 'Login gagal!'], 401);
    }

    // Login untuk verificator
    public function loginVerificator(Request $request)
    {
        $credentials = $request->only('verifikator_email', 'verifikator_password');

        // Cek menggunakan verifikator_email atau verifikator_nip
        $verifikator = Auth::guard('verifikator')->attempt($credentials);
        if (!$verifikator) {
            $verifikator = Auth::guard('verifikator')->attempt(['verifikator_nip' => $request->verifikator_nip, 'verifikator_password' => $request->verifikator_password]);
        }

        if ($verifikator) {
            return response()->json(['user' => $verifikator, 'message' => 'Login sukses!'], 200);
        }

        return response()->json(['message' => 'Login gagal!'], 401);
    }

    // Login untuk Admin
    public function loginAdmin(Request $request)
    {
        $credentials = $request->only('admin_username', 'admin_password');

        // Cek menggunakan admin_username atau admin_email
        $admin = Auth::guard('admin')->attempt($credentials);
        if (!$admin) {
            $admin = Auth::guard('admin')->attempt(['admin_username' => $request->admin_username, 'admin_password' => $request->admin_password]);
        }

        if ($admin) {
            return response()->json(['user' => $admin, 'message' => 'Login sukses!'], 200);
        }

        return response()->json(['message' => 'Login gagal!'], 401);
    }

    // Logout
    public function logout()
    {
        Auth::guard('web')->logout();
        Auth::guard('verificator')->logout();
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['message' => 'Logout sukses']);
    }
}