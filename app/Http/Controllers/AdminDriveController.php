<?php

namespace App\Http\Controllers;

use App\Models\ModelJson;
use App\Models\ModelDriveFolder;
use Illuminate\Http\Request;

class AdminDriveController extends Controller
{
    public function json()
    {
        $jsons = ModelJson::orderBy('json_id', 'desc')->get();

        return view('administrator.drive.json', compact('jsons'));
    }

    public function storeJson(Request $request)
    {
        $request->validate([
            'json_nama' => 'required|string|max:255',
            'json_file' => 'required|string|max:255',
            'json_status' => 'required|in:0,1',
        ]);

        ModelJson::create([
            'json_nama' => $request->json_nama,
            'json_file' => $request->json_file,
            'json_status' => $request->json_status,
        ]);

        return back()->with('success', 'Data JSON berhasil ditambahkan.');
    }

    public function updateJson(Request $request)
    {
        $request->validate([
            'json_id' => 'required|exists:saplarin_json,json_id',
            'json_nama' => 'required|string|max:255',
            'json_file' => 'required|string|max:255',
            'json_status' => 'required|in:0,1',
        ]);

        ModelJson::where('json_id', $request->json_id)->update([
            'json_nama' => $request->json_nama,
            'json_file' => $request->json_file,
            'json_status' => $request->json_status,
        ]);

        return back()->with('success', 'Data JSON berhasil diperbarui.');
    }

    public function folder()
    {
        $folders = ModelDriveFolder::with('json')
            ->orderBy('folder_id', 'desc')
            ->get();

        $jsons = ModelJson::where('json_status', 1)
            ->orderBy('json_nama', 'asc')
            ->get();

        return view('administrator.drive.folder', compact('folders', 'jsons'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'folder_nama' => 'required|string|max:255',
            'folder_prefix' => 'required|string|max:100|unique:saplarin_drive_folder,folder_prefix',
            'folder_drive_id' => 'required|string|max:255',
            'folder_json' => 'required|exists:saplarin_json,json_id',
            'folder_status' => 'required|in:0,1',
        ]);

        ModelDriveFolder::create([
            'folder_nama' => $request->folder_nama,
            'folder_prefix' => strtolower($request->folder_prefix),
            'folder_drive_id' => $request->folder_drive_id,
            'folder_json' => $request->folder_json,
            'folder_status' => $request->folder_status,
        ]);

        return back()->with('success', 'Folder Drive berhasil ditambahkan.');
    }

    public function updateFolder(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:saplarin_drive_folder,folder_id',
            'folder_nama' => 'required|string|max:255',
            'folder_prefix' => 'required|string|max:100',
            'folder_drive_id' => 'required|string|max:255',
            'folder_json' => 'required|exists:saplarin_json,json_id',
            'folder_status' => 'required|in:0,1',
        ]);

        $cek = ModelDriveFolder::where('folder_prefix', strtolower($request->folder_prefix))
            ->where('folder_id', '!=', $request->folder_id)
            ->exists();

        if ($cek) {
            return back()->with('error', 'Prefix folder sudah digunakan.');
        }

        ModelDriveFolder::where('folder_id', $request->folder_id)->update([
            'folder_nama' => $request->folder_nama,
            'folder_prefix' => strtolower($request->folder_prefix),
            'folder_drive_id' => $request->folder_drive_id,
            'folder_json' => $request->folder_json,
            'folder_status' => $request->folder_status,
        ]);

        return back()->with('success', 'Folder Drive berhasil diperbarui.');
    }
}