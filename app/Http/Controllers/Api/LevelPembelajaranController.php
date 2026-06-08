<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelPembelajaran;
use Illuminate\Http\Request;

class LevelPembelajaranController extends Controller
{
    public function index()
    {
        $level = LevelPembelajaran::with('siswa.orangTua', 'pengajar')->get();

        return response()->json([
            'message' => 'Data level pembelajaran berhasil diambil',
            'data' => $level,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'pengajar_id' => 'required|exists:pengajars,id',
            'level' => 'required',
            'keterangan' => 'nullable',
        ]);

        $level = LevelPembelajaran::create([
            'siswa_id' => $request->siswa_id,
            'pengajar_id' => $request->pengajar_id,
            'level' => $request->level,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Data level pembelajaran berhasil ditambahkan',
            'data' => $level->load('siswa.orangTua', 'pengajar'),
        ], 201);
    }

    public function show(string $id)
    {
        $level = LevelPembelajaran::with('siswa.orangTua', 'pengajar')->findOrFail($id);

        return response()->json([
            'message' => 'Detail level pembelajaran berhasil diambil',
            'data' => $level,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $level = LevelPembelajaran::findOrFail($id);

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'pengajar_id' => 'required|exists:pengajars,id',
            'level' => 'required',
            'keterangan' => 'nullable',
        ]);

        $level->update([
            'siswa_id' => $request->siswa_id,
            'pengajar_id' => $request->pengajar_id,
            'level' => $request->level,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Data level pembelajaran berhasil diperbarui',
            'data' => $level->load('siswa.orangTua', 'pengajar'),
        ]);
    }

    public function destroy(string $id)
    {
        $level = LevelPembelajaran::findOrFail($id);
        $level->delete();

        return response()->json([
            'message' => 'Data level pembelajaran berhasil dihapus',
        ]);
    }
}
