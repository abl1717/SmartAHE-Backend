<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\Owner;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $keuangan = Keuangan::with('owner.user')
            ->latest()
            ->paginate($perPage);

        $totalPemasukan = Keuangan::where('jenis', 'Pemasukan')
            ->sum('jumlah');

        $totalPengeluaran = Keuangan::where('jenis', 'Pengeluaran')
            ->sum('jumlah');

        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        $totalData = Keuangan::count();

        return response()->json([
            'message' => 'Data keuangan berhasil diambil',

            'summary' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'total_saldo' => $totalSaldo,
                'total_data' => $totalData,
            ],

            'data' => $keuangan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'nullable|exists:owners,id',
            'jenis' => 'required',
            'jumlah' => 'required|integer|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        $ownerId = $request->owner_id ?? Owner::first()->id;

        $keuangan = Keuangan::create([
            'owner_id' => $ownerId,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Data keuangan berhasil ditambahkan',
            'data' => $keuangan->load('owner.user'),
        ], 201);
    }

    public function show(string $id)
    {
        $keuangan = Keuangan::with('owner.user')->findOrFail($id);

        return response()->json([
            'message' => 'Detail keuangan berhasil diambil',
            'data' => $keuangan,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $keuangan = Keuangan::findOrFail($id);

        $request->validate([
            'jenis' => 'required',
            'jumlah' => 'required|integer|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        $keuangan->update([
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Data keuangan berhasil diperbarui',
            'data' => $keuangan->load('owner.user'),
        ]);
    }

    public function destroy(string $id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->delete();

        return response()->json([
            'message' => 'Data keuangan berhasil dihapus',
        ]);
    }
}
