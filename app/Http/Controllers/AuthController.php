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

        $roles = $rolesData->pluck('user_role')->toArray();

        $roles = collect($roles)
            ->map(function ($role) {

            if ($role == 'Admin') {
                return 'Admin Full';
            }

            return $role;
            })
            ->unique()
            ->values()
            ->toArray();

        /*
|--------------------------------------------------------------------------
| PRIORITAS ROLE
|--------------------------------------------------------------------------
|
| Admin Full
| Admin Arsiparis
| Admin BBM
| Operator
| Pegawai
|
*/

        if (in_array('Admin Full', $roles)) {

            $activeRole = 'Admin Full';
        } elseif (in_array('Admin Arsiparis', $roles)) {

            $activeRole = 'Admin Arsiparis';
        } elseif (in_array('Admin BBM', $roles)) {

            $activeRole = 'Admin BBM';
        } elseif (in_array('Operator', $roles)) {

            $activeRole = 'Operator';
        } else {

            $activeRole = 'Pegawai';
        }

        session([
            'pegawai_id' => $pegawai['id'],
            'pegawai_nama' => $pegawai['nama'],
            'pegawai_nip' => $pegawai['nip'],

            'pegawai_jabatan_id' => $pegawai['jabatan_id'] ?? null,
            'pegawai_bidang_id' => $pegawai['bidang_id'] ?? null,

            'pegawai_bidang' => $pegawai['bidang'] ?? null,
            'pegawai_jabatan' => $pegawai['jabatan'] ?? null,

            'pegawai_email' => $pegawai['email'] ?? null,
            'pegawai_hp' => $pegawai['hp'] ?? null,

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
        if (
            $role == 'Admin Full' ||
            $role == 'Admin Arsiparis' ||
            $role == 'Admin BBM'
        ) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
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