<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Owner;
use App\Models\Pengajar;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\LevelPembelajaran;
use App\Models\ModulPembelajaran;
use App\Models\TransaksiModul;
use App\Models\Keuangan;

class SmartAHESeeder extends Seeder
{
    public function run(): void
    {
        $userOwner = User::create([
            'name' => 'Owner SmartAHE',
            'email' => 'owner@smartahe.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        $owner = Owner::create([
            'user_id' => $userOwner->id,
        ]);

        $userPengajar = User::create([
            'name' => 'Bu Rina',
            'email' => 'rina@smartahe.com',
            'password' => Hash::make('pengajar123'),
            'role' => 'pengajar',
        ]);

        $pengajar = Pengajar::create([
            'user_id' => $userPengajar->id,
            'nama_pengajar' => 'Bu Rina',
            'no_hp' => '081234567890',
            'alamat' => 'Pekanbaru',
        ]);

        $userOrangTua = User::create([
            'name' => 'Pak Ahmad',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('orangtua123'),
            'role' => 'orangtua',
        ]);

        $orangTua = OrangTua::create([
            'user_id' => $userOrangTua->id,
            'nama_orang_tua' => 'Pak Ahmad',
            'no_hp' => '082345678901',
            'alamat' => 'Pekanbaru',
        ]);

        $siswa = Siswa::create([
            'orang_tua_id' => $orangTua->id,
            'nama_siswa' => 'Budi Santoso',
            'alamat' => 'Pekanbaru',
            'tanggal_lahir' => '2018-05-10',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        LevelPembelajaran::create([
            'siswa_id' => $siswa->id,
            'pengajar_id' => $pengajar->id,
            'level' => 'Level 1',
            'keterangan' => 'Siswa baru mendaftar',
        ]);

        $modulList = [
            ['nama' => 'Modul Level 1', 'level' => 'Level 1', 'stok' => 100],
            ['nama' => 'Modul Level 2', 'level' => 'Level 2', 'stok' => 100],
            ['nama' => 'Modul Level 3', 'level' => 'Level 3', 'stok' => 100],
            ['nama' => 'Modul Level 4', 'level' => 'Level 4', 'stok' => 100],
            ['nama' => 'Modul Level 5', 'level' => 'Level 5', 'stok' => 100],
            ['nama' => 'Modul Level 6', 'level' => 'Level 6', 'stok' => 100],
            ['nama' => 'Modul Level 7', 'level' => 'Level 7', 'stok' => 100],
        ];

        foreach ($modulList as $item) {
            $modul = ModulPembelajaran::create([
                'owner_id' => $owner->id,
                'nama' => $item['nama'],
                'level' => $item['level'],
                'stok' => $item['stok'],
            ]);

            TransaksiModul::create([
                'modul_pembelajaran_id' => $modul->id,
                'jenis' => 'Masuk',
                'jumlah' => $item['stok'],
                'tanggal' => now(),
                'keterangan' => 'Stok awal ' . $item['nama'],
            ]);
        }

        Keuangan::create([
            'owner_id' => $owner->id,
            'jenis' => 'Pemasukan',
            'jumlah' => 500000,
            'tanggal' => now(),
            'keterangan' => 'Pembayaran SPP',
        ]);
    }
}
