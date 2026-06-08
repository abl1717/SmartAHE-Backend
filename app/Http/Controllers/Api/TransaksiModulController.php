<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulPembelajaran;
use App\Models\TransaksiModul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiModulController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiModul::with('modul')->latest()->get();

        return response()->json([
            'message' => 'Data transaksi modul berhasil diambil',
            'data' => $transaksi,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'modul_pembelajaran_id' => 'required|exists:modul_pembelajarans,id',
            'jenis' => 'required|in:Masuk,Keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        return DB::transaction(function () use ($request) {
            $modul = ModulPembelajaran::findOrFail($request->modul_pembelajaran_id);

            if ($request->jenis === 'Keluar' && $modul->stok < $request->jumlah) {
                return response()->json([
                    'message' => 'Stok modul tidak mencukupi',
                ], 400);
            }

            $transaksi = TransaksiModul::create([
                'modul_pembelajaran_id' => $request->modul_pembelajaran_id,
                'jenis' => $request->jenis,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            if ($request->jenis === 'Masuk') {
                $modul->update([
                    'stok' => $modul->stok + $request->jumlah,
                ]);
            } else {
                $modul->update([
                    'stok' => $modul->stok - $request->jumlah,
                ]);
            }

            return response()->json([
                'message' => 'Transaksi modul berhasil disimpan',
                'data' => $transaksi->load('modul'),
            ], 201);
        });
    }

    public function show(string $id)
    {
        $transaksi = TransaksiModul::with('modul')->findOrFail($id);

        return response()->json([
            'message' => 'Detail transaksi modul berhasil diambil',
            'data' => $transaksi,
        ]);
    }

    public function destroy(string $id)
    {
        $transaksi = TransaksiModul::findOrFail($id);
        $transaksi->delete();

        return response()->json([
            'message' => 'Transaksi modul berhasil dihapus',
        ]);
    }
}
