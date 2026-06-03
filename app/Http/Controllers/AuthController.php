<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ModelUser;

class AuthController extends Controller
{
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

        $rolesData = ModelUser::where('user_uid', $pegawai['id'])->get();

        if ($rolesData->isEmpty()) {
            return back()->with('error', 'Role tidak ditemukan');
        }

        /*
            Ambil role dari database.
            Kalau masih ada role lama "Admin", otomatis dianggap "Admin Full".
        */
        $roles = $rolesData->pluck('user_role')->toArray();

        $roles = collect($roles)
            ->map(function ($role) {
                return $role === 'Admin' ? 'Admin Full' : $role;
            })
            ->unique()
            ->values()
            ->toArray();

        /*
            Prioritas role saat login:
            1. Admin Full
            2. Admin SPJ
            3. Admin KAK
            4. Admin PWA
            5. Admin BBM
            6. Pegawai
        */
        if (in_array('Admin Full', $roles)) {
            $activeRole = 'Admin Full';
        } elseif (in_array('Admin SPJ', $roles)) {
            $activeRole = 'Admin SPJ';
        } elseif (in_array('Admin KAK', $roles)) {
            $activeRole = 'Admin KAK';
        } elseif (in_array('Admin PWA', $roles)) {
            $activeRole = 'Admin PWA';
        } elseif (in_array('Admin BBM', $roles)) {
            $activeRole = 'Admin BBM';
        } elseif (in_array('Pegawai', $roles)) {
            $activeRole = 'Pegawai';
        } else {
            $activeRole = $roles[0];
        }

        session([
            'pegawai_id' => $pegawai['id'],
            'pegawai_nama' => $pegawai['nama'],
            'pegawai_nip' => $pegawai['nip'],
            'roles' => $roles,
            'active_role' => $activeRole,
            'logged_in' => true,
        ]);

        return $this->redirectByRole($activeRole);
    }

    public function setRole(Request $request)
    {
        $request->validate([
            'role' => 'required',
        ]);

        $roles = session('roles', []);

        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (!in_array($request->role, $roles)) {
            return back()->with('error', 'Role tidak valid');
        }

        session([
            'active_role' => $request->role,
        ]);

        return $this->redirectByRole($request->role);
    }

    private function redirectByRole($role)
    {
        if ($role && str_starts_with($role, 'Admin')) {
            return redirect('/admin/dashboard');
        }

        return redirect('/user/dashboard');
    }

    public function dashboardUser()
    {
        return view('user.dashboard');
    }

    public function bbm()
    {
        return view('user.bbm.index');
    }

    public function spj()
    {
        return view('user.spj.index');
    }

    public function kak()
    {
        return view('user.kak.index');
    }

    public function pwa()
    {
        return view('user.pwa.index');
    }

    public function riwayat()
    {
        return view('user.riwayat');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();

        return redirect('/');
    }
}