<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    /**
     * Login Flutter / Mobile SAPLARIN
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        try {
            /*
            |--------------------------------------------------------------------------
            | LOGIN KE SADARIN API
            |--------------------------------------------------------------------------
            */

            $sadarInApi = rtrim(env('SADARIN_API'), '/');

            if (empty($sadarInApi)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Konfigurasi SADARIN_API belum tersedia.',
                    ],
                    500,
                );
            }

            $response = Http::timeout(15)->post($sadarInApi . '/login', [
                'nip' => $request->nip,
                'password' => $request->password,
            ]);

            /*
            |--------------------------------------------------------------------------
            | SADARIN TIDAK BISA DIAKSES
            |--------------------------------------------------------------------------
            */

            if (!$response->ok()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'API SADARIN tidak dapat diakses.',
                    ],
                    503,
                );
            }

            $data = $response->json();

            /*
            |--------------------------------------------------------------------------
            | LOGIN SADARIN GAGAL
            |--------------------------------------------------------------------------
            */

            if (!($data['status'] ?? false)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $data['message'] ?? 'NIP atau password salah.',
                    ],
                    401,
                );
            }

            /*
            |--------------------------------------------------------------------------
            | DATA PEGAWAI
            |--------------------------------------------------------------------------
            */

            $pegawai = $data['data'] ?? null;

            if (!$pegawai) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Data pegawai tidak ditemukan.',
                    ],
                    404,
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CARI USER SAPLARIN
            |--------------------------------------------------------------------------
            */

            $user = ModelUser::where('user_uid', $pegawai['id'])->first();

            if (!$user) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'User SAPLARIN tidak ditemukan.',
                    ],
                    403,
                );
            }

            /*
            |--------------------------------------------------------------------------
            | AMBIL SEMUA ROLE
            |--------------------------------------------------------------------------
            */

            $rolesData = ModelUser::where('user_uid', $pegawai['id'])->get();

            if ($rolesData->isEmpty()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Role SAPLARIN tidak ditemukan.',
                    ],
                    403,
                );
            }

            $roles = $rolesData->pluck('user_role')->toArray();

            /*
            |--------------------------------------------------------------------------
            | NORMALISASI ROLE
            |--------------------------------------------------------------------------
            */

            $roles = collect($roles)
                ->map(function ($role) {
                    if ($role === 'Admin') {
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

            /*
            |--------------------------------------------------------------------------
            | DATA USER UNTUK FLUTTER
            |--------------------------------------------------------------------------
            */

            $userData = [
                'id' => $pegawai['id'] ?? null,
                'nama' => $pegawai['nama'] ?? null,
                'nip' => $pegawai['nip'] ?? null,

                'jabatan_id' => $pegawai['jabatan_id'] ?? null,
                'bidang_id' => $pegawai['bidang_id'] ?? null,

                'bidang' => $pegawai['bidang'] ?? null,
                'jabatan' => $pegawai['jabatan'] ?? null,

                'email' => $pegawai['email'] ?? null,
                'hp' => $pegawai['hp'] ?? null,
            ];

            /*
            |--------------------------------------------------------------------------
            | HAPUS TOKEN MOBILE LAMA
            |--------------------------------------------------------------------------
            |
            | Supaya satu device/user tidak menumpuk terlalu banyak token.
            |
            */

            $user->tokens()->where('name', 'saplarin-mobile')->delete();

            /*
            |--------------------------------------------------------------------------
            | BUAT TOKEN SANCTUM
            |--------------------------------------------------------------------------
            */

            $token = $user->createToken('saplarin-mobile')->plainTextToken;

            /*
            |--------------------------------------------------------------------------
            | RESPONSE LOGIN
            |--------------------------------------------------------------------------
            */

            return response()->json(
                [
                    'status' => true,

                    'message' => 'Login berhasil.',

                    'token' => $token,

                    'token_type' => 'Bearer',

                    'data' => $userData,

                    'roles' => $roles,

                    'active_role' => $activeRole,
                ],
                200,
            );
        } catch (\Throwable $e) {
            Log::error('SAPLARIN Mobile Login Error', [
                'message' => $e->getMessage(),
                'nip' => $request->nip,
            ]);

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Terjadi kesalahan pada server SAPLARIN.',
                ],
                500,
            );
        }
    }

    /**
     * Data user yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => true,

            'data' => [
                'id' => $user->user_uid ?? null,
                'nama' => $user->user_nama ?? null,
                'nip' => $user->user_nip ?? null,
                'email' => $user->user_email ?? null,
            ],
        ]);
    }

    /**
     * Logout Flutter.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}