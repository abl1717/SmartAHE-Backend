<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelPembelajaran;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use App\Models\ModulPembelajaran;
use App\Models\TransaksiModul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $siswa = Siswa::with([
            'orangTua.user',
            'levelPembelajaran.pengajar'
        ])
            ->latest()
            ->paginate($perPage);

        $totalSiswa = Siswa::count();

        $totalOrangTua = OrangTua::count();

        $totalPengajar = \App\Models\Pengajar::count();

        return response()->json([
            'message' => 'Data siswa berhasil diambil',

            'summary' => [
                'total_siswa' => $totalSiswa,
                'total_orang_tua' => $totalOrangTua,
                'total_pengajar' => $totalPengajar,
            ],

            'data' => $siswa,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_orang_tua' => 'required|in:lama,baru',
            'nama_siswa' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'pengajar_id' => 'required|exists:pengajars,id',

            'orang_tua_id' => 'required_if:tipe_orang_tua,lama|exists:orang_tuas,id',

            'nama_orang_tua' => 'required_if:tipe_orang_tua,baru',
            'no_hp' => 'required_if:tipe_orang_tua,baru',
            'alamat_orang_tua' => 'required_if:tipe_orang_tua,baru',
            'email' => 'required_if:tipe_orang_tua,baru|email|unique:users,email',
            'password' => 'required_if:tipe_orang_tua,baru|min:6',
        ]);

        return DB::transaction(function () use ($request) {
            if ($request->tipe_orang_tua === 'baru') {
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
                    'alamat' => $request->alamat_orang_tua,
                ]);

                $orangTuaId = $orangTua->id;
            } else {
                $orangTuaId = $request->orang_tua_id;
            }

            $siswa = Siswa::create([
                'orang_tua_id' => $orangTuaId,
                'nama_siswa' => $request->nama_siswa,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
            ]);

            LevelPembelajaran::create([
                'siswa_id' => $siswa->id,
                'pengajar_id' => $request->pengajar_id,
                'level' => 'Level 1',
                'keterangan' => 'Siswa baru mendaftar',
            ]);

            $modulLevelSatu = ModulPembelajaran::where('level', 'Level 1')->first();

            if (!$modulLevelSatu) {
                return response()->json([
                    'message' => 'Modul Level 1 belum tersedia.',
                ], 400);
            }

            if ($modulLevelSatu->stok < 1) {
                return response()->json([
                    'message' => 'Stok Modul Level 1 tidak mencukupi.',
                ], 400);
            }

            $modulLevelSatu->update([
                'stok' => $modulLevelSatu->stok - 1,
            ]);

            TransaksiModul::create([
                'modul_pembelajaran_id' => $modulLevelSatu->id,
                'jenis' => 'Keluar',
                'jumlah' => 1,
                'tanggal' => now(),
                'keterangan' => 'Modul Level 1 diberikan kepada siswa baru: ' . $siswa->nama_siswa,
            ]);

            return response()->json([
                'message' => 'Data siswa berhasil ditambahkan',
                'data' => $siswa->load('orangTua.user', 'levelPembelajaran.pengajar'),
            ], 201);
        });
    }

    public function show(string $id)
    {
        $siswa = Siswa::with('orangTua.user', 'levelPembelajaran.pengajar')->findOrFail($id);

        return response()->json([
            'message' => 'Detail siswa berhasil diambil',
            'data' => $siswa,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama_siswa' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'orang_tua_id' => 'required|exists:orang_tuas,id',
            'pengajar_id' => 'required|exists:pengajars,id',
        ]);

        $siswa->update([
            'orang_tua_id' => $request->orang_tua_id,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
        ]);

        $level = LevelPembelajaran::where('siswa_id', $siswa->id)->first();

        if ($level) {
            $level->update([
                'pengajar_id' => $request->pengajar_id,
            ]);
        }

        return response()->json([
            'message' => 'Data siswa berhasil diperbarui',
            'data' => $siswa->load('orangTua.user', 'levelPembelajaran.pengajar'),
        ]);
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        LevelPembelajaran::where('siswa_id', $siswa->id)->delete();
        $siswa->delete();

        return response()->json([
            'message' => 'Data siswa berhasil dihapus',
        ]);
    }
}
