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
    public function loginuser(Request $request)
    {
        $request->validate([
            'user_nip' => 'required',
            'user_password' => 'required',
        ]);

        $user = ModelUser::where('user_nip', $request->user_nip)
            ->orWhere('user_email', $request->user_nip)
            ->first();

        if (!$user) {
            return back()->withErrors(['user_nip' => 'NIP atau Email tidak ditemukan']);
        }

        if (!Hash::check($request->user_password, $user->user_password)) {
            return back()->withErrors(['user_password' => 'Password salah']);
        }

        if ($user->user_status != 1) {
            return back()->withErrors(['user_nip' => 'Akun tidak aktif']);
        }

        // Login manual
        Auth::login($user);
        $request->session()->regenerate(); // Security: anti session fixation

        return redirect()->intended('/dashboard');
    }
}
