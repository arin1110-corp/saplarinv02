<?php

namespace App\Http\Controllers;

use App\Models\ModelJson;
use Illuminate\Http\Request;

class AdminJsonController extends Controller
{
    public function index()
    {
        $jsons = ModelJson::orderBy('json_key', 'asc')->get();

        return view('administrator.json.index', compact('jsons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'json_key' => 'required|string|max:255|unique:saplarin_json,json_key',
            'json_nama' => 'nullable|string',
            'json_value' => 'required|string',
            'json_status' => 'required|in:0,1',
        ]);

        $decoded = json_decode($request->json_value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Format JSON tidak valid.');
        }

        ModelJson::create([
            'json_key' => $request->json_key,
            'json_nama' => $request->json_nama,
            'json_value' => json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'json_status' => $request->json_status,
        ]);

        return back()->with('success', 'JSON berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'json_id' => 'required|exists:saplarin_json,json_id',
            'json_key' => 'required|string|max:255',
            'json_nama' => 'nullable|string',
            'json_value' => 'required|string',
            'json_status' => 'required|in:0,1',
        ]);

        $json = ModelJson::findOrFail($request->json_id);

        $exists = ModelJson::where('json_key', $request->json_key)
            ->where('json_id', '!=', $json->json_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'JSON key sudah digunakan.');
        }

        $decoded = json_decode($request->json_value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Format JSON tidak valid.');
        }

        $json->update([
            'json_key' => $request->json_key,
            'json_nama' => $request->json_nama,
            'json_value' => json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'json_status' => $request->json_status,
        ]);

        return back()->with('success', 'JSON berhasil diperbarui.');
    }

    public function delete($id)
    {
        $json = ModelJson::findOrFail($id);
        $json->delete();

        return back()->with('success', 'JSON berhasil dihapus.');
    }
}