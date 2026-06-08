<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengajarController extends Controller
{
    public function index()
    {
        $pengajar = Pengajar::with('user', 'levelPembelajaran')->get();

        return response()->json([
            'message' => 'Data pengajar berhasil diambil',
            'data' => $pengajar,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengajar' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->nama_pengajar,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengajar',
        ]);

        $pengajar = Pengajar::create([
            'user_id' => $user->id,
            'nama_pengajar' => $request->nama_pengajar,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Data pengajar dan akun login berhasil ditambahkan',
            'data' => $pengajar->load('user'),
        ], 201);
    }

    public function show(string $id)
    {
        $pengajar = Pengajar::with('user', 'levelPembelajaran')->findOrFail($id);

        return response()->json([
            'message' => 'Detail pengajar berhasil diambil',
            'data' => $pengajar,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $pengajar = Pengajar::findOrFail($id);

        $request->validate([
            'nama_pengajar' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $pengajar->update([
            'nama_pengajar' => $request->nama_pengajar,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        if ($pengajar->user) {
            $pengajar->user->update([
                'name' => $request->nama_pengajar,
            ]);
        }

        return response()->json([
            'message' => 'Data pengajar berhasil diperbarui',
            'data' => $pengajar->load('user'),
        ]);
    }

    public function destroy(string $id)
    {
        $pengajar = Pengajar::findOrFail($id);

        if ($pengajar->levelPembelajaran()->count() > 0) {
            return response()->json([
                'message' => 'Pengajar masih memiliki data bimbingan siswa',
            ], 400);
        }

        if ($pengajar->user) {
            $pengajar->user->delete();
        }

        $pengajar->delete();

        return response()->json([
            'message' => 'Data pengajar berhasil dihapus',
        ]);
    }
}
