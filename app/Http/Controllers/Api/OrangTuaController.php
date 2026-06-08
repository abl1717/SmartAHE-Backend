<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrangTuaController extends Controller
{
    public function index()
    {
        $orangTua = OrangTua::with('user', 'siswas')->get();

        return response()->json([
            'message' => 'Data orang tua berhasil diambil',
            'data' => $orangTua,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_orang_tua' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->nama_orang_tua,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'orangtua',
        ]);

        $orangTua = OrangTua::create([
            'user_id' => $user->id,
            'nama_orang_tua' => $request->nama_orang_tua,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'message' => 'Data orang tua dan akun login berhasil ditambahkan',
            'data' => $orangTua->load('user'),
        ], 201);
    }

    public function show(string $id)
    {
        $orangTua = OrangTua::with('user', 'siswas')->findOrFail($id);

        return response()->json([
            'message' => 'Detail orang tua berhasil diambil',
            'data' => $orangTua,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $orangTua = OrangTua::findOrFail($id);

        $request->validate([
            'nama_orang_tua' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $orangTua->update([
            'nama_orang_tua' => $request->nama_orang_tua,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        if ($orangTua->user) {
            $orangTua->user->update([
                'name' => $request->nama_orang_tua,
            ]);
        }

        return response()->json([
            'message' => 'Data orang tua berhasil diperbarui',
            'data' => $orangTua->load('user'),
        ]);
    }

    public function destroy(string $id)
    {
        $orangTua = OrangTua::findOrFail($id);

        if ($orangTua->siswas()->count() > 0) {
            return response()->json([
                'message' => 'Orang tua masih memiliki data siswa',
            ], 400);
        }

        if ($orangTua->user) {
            $orangTua->user->delete();
        }

        $orangTua->delete();

        return response()->json([
            'message' => 'Data orang tua berhasil dihapus',
        ]);
    }
}
