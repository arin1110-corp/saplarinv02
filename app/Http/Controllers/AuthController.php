<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\ModelUser;
use App\Models\ModelAdmin;
use App\Models\ModelVerificator;
use App\Models\ModelPegawai;

class AuthController extends Controller
{
    // ===================== USER =====================
    public function formUser()
    {
        return view('auth.loginuser');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $response = Http::post(env('SADARIN_API') . '/login', [
            'nip' => $request->nip,
            'password' => $request->password,
        ]);

        if (!$response->ok()) {
            return back()->with('error', 'API tidak bisa diakses');
        }

        $data = $response->json();

        if (!($data['status'] ?? false)) {
            return back()->with('error', $data['message'] ?? 'Login gagal');
        }

        $pegawai = $data['data'] ?? null;

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan');
        }

        $roles = ModelUser::where('user_uid', $pegawai['id'])->get();

        if ($roles->isEmpty()) {
            return back()->with('error', 'Role tidak ditemukan');
        }

        $role = $roles->firstWhere('user_role', 'Admin') ?? $roles->firstWhere('user_role', 'Pegawai');

        if (!$role) {
            return back()->with('error', 'Role tidak valid');
        }

        session([
            'pegawai_id' => $pegawai['id'],
            'pegawai_nama' => $pegawai['nama'],
            'pegawai_nip' => $pegawai['nip'],
            'active_role' => $role->user_role,
            'logged_in' => true,
        ]);

        return redirect($role->user_role === 'Admin' ? '/login-admin' : '/login-user');
    }

    public function logoutSubmit()
    {
        Auth::logout();
        session()->flush();

        return redirect('/');
    }
    // ===================== ADMIN =====================
    public function loginAdmin()
    {
        if (!session('logged_in') || session('active_role') !== 'Admin') {
            return redirect('/login-admin')->with('error', 'Anda harus login sebagai Admin untuk mengakses halaman ini');
        }

        return view('administrator.admin');
    }
}