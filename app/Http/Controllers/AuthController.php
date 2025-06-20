<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ModelUser;
use App\Models\ModelAdmin;
use App\Models\ModelVerificator;

class AuthController extends Controller
{
    // ===================== USER =====================
    public function formUser()
    {
        return view('auth.loginuser');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $user = ModelUser::where('user_nip', $request->nip)
            ->orWhere('user_email', $request->nip)
            ->first();

        // Step 1: NIP/Email tidak ditemukan
        if (!$user) {
            return back()->withErrors(['nip' => 'NIP atau Email tidak ditemukan'])->withInput();
        }

        // Step 2: Password salah
        if (!Hash::check($request->password, $user->user_password)) {
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        }

        // Step 3: Akun tidak aktif
        if ($user->user_status != 1) {
            return back()->withErrors(['nip' => 'Akun tidak aktif'])->withInput();
        }

        // Berhasil login
        Auth::guard('user')->login($user);
        $request->session()->regenerate();

        return redirect('/user/dashboard');
    }

    // ===================== VERIFIKATOR =====================
    public function formVerifikator()
    {
        return view('auth.loginverifikator');
    }

    public function loginVerifikator(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $verifikator = ModelVerificator::where('verificator_nip', $request->nip)
            ->orWhere('verificator_email', $request->nip)
            ->first();

        // Step 1: NIP/Email tidak ditemukan
        if (!$verifikator) {
            return back()->withErrors(['nip' => 'NIP atau Email tidak ditemukan'])->withInput();
        }

        // Step 2: Password salah
        if (!Hash::check($request->password, $verifikator->verificator_password)) {
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        }

        // Step 3: Akun tidak aktif
        if ($verifikator->verificator_status != 1) {
            return back()->withErrors(['nip' => 'Akun tidak aktif'])->withInput();
        }

        Auth::guard('verifikator')->login($verifikator);
        $request->session()->regenerate();

        return redirect('/verifikator/dashboard');
    }

    // ===================== ADMIN =====================
    public function formAdmin()
    {
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = ModelAdmin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password) || $admin->status != 1) {
            return back()->withErrors(['username' => 'Login Admin Gagal!']);
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect('/admin/dashboard');
    }

    // ===================== LOGOUT SEMUA GUARD =====================
    public function logout(Request $request)
    {
        foreach (['admin', 'verifikator', 'user'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-user'); // arahkan ke login user default
    }
}
