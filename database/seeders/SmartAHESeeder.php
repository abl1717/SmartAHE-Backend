<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Pengajar;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\LevelPembelajaran;
use App\Models\ModulPembelajaran;
use App\Models\Keuangan;

class SmartAHESeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::create([
            'name' => 'Owner SmartAHE',
            'email' => 'owner@smartahe.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        $userPengajar = User::create([
            'name' => 'Bu Rina',
            'email' => 'rina@smartahe.com',
            'password' => Hash::make('pengajar123'),
            'role' => 'pengajar',
        ]);

        $pengajar = Pengajar::create([
            'user_id' => $userPengajar->id,
            'nama' => 'Bu Rina',
            'no_hp' => '08123456789',
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
            'nama' => 'Pak Ahmad',
            'no_hp' => '08234567890',
            'alamat' => 'Pekanbaru',
        ]);

        $siswa = Siswa::create([
            'orang_tua_id' => $orangTua->id,
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2018-05-10',
            'alamat' => 'Pekanbaru',
        ]);

        LevelPembelajaran::create([
            'siswa_id' => $siswa->id,
            'pengajar_id' => $pengajar->id,
            'level' => 'Level 1',
            'keterangan' => 'Siswa baru mendaftar',
        ]);

        ModulPembelajaran::create([
            'nama_modul' => 'Modul Membaca Dasar',
            'level' => 'Level 1',
            'stok' => 100,
        ]);

        Keuangan::create([
            'jenis' => 'Pemasukan',
            'jumlah' => 500000,
            'tanggal' => now(),
            'keterangan' => 'Pembayaran SPP',
        ]);
    }


}
