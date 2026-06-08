<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulPembelajaran;
use App\Models\Owner;
use Illuminate\Http\Request;

class ModulPembelajaranController extends Controller
{
    public function index()
    {
        $modul = ModulPembelajaran::with('owner.user', 'transaksiModul')->get();

        return response()->json([
            'message' => 'Data modul pembelajaran berhasil diambil',
            'data' => $modul,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'nullable|exists:owners,id',
            'nama' => 'required',
            'stok' => 'required|integer|min:0',
            'level' => 'required',
        ]);

        $ownerId = $request->owner_id ?? Owner::first()->id;

        $modul = ModulPembelajaran::create([
            'owner_id' => $ownerId,
            'nama' => $request->nama,
            'stok' => $request->stok,
            'level' => $request->level,
        ]);

        return response()->json([
            'message' => 'Data modul pembelajaran berhasil ditambahkan',
            'data' => $modul->load('owner.user'),
        ], 201);
    }

    public function show(string $id)
    {
        $modul = ModulPembelajaran::with('owner.user', 'transaksiModul')->findOrFail($id);

        return response()->json([
            'message' => 'Detail modul pembelajaran berhasil diambil',
            'data' => $modul,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $modul = ModulPembelajaran::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'stok' => 'required|integer|min:0',
            'level' => 'required',
        ]);

        $modul->update([
            'nama' => $request->nama,
            'stok' => $request->stok,
            'level' => $request->level,
        ]);

        return response()->json([
            'message' => 'Data modul pembelajaran berhasil diperbarui',
            'data' => $modul->load('owner.user'),
        ]);
    }

    public function destroy(string $id)
    {
        $modul = ModulPembelajaran::findOrFail($id);

        if ($modul->transaksiModul()->count() > 0) {
            return response()->json([
                'message' => 'Modul masih memiliki riwayat transaksi',
            ], 400);
        }

        $modul->delete();

        return response()->json([
            'message' => 'Data modul pembelajaran berhasil dihapus',
        ]);
    }
}
