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

        $modul = ModulPembelajaran::create([
            'owner_id' => $owner->id,
            'nama' => 'Modul Membaca Dasar',
            'stok' => 100,
            'level' => 'Level 1',
        ]);

        TransaksiModul::create([
            'modul_pembelajaran_id' => $modul->id,
            'jenis' => 'Masuk',
            'jumlah' => 100,
            'tanggal' => now(),
            'keterangan' => 'Stok awal modul',
        ]);

        Keuangan::create([
            'owner_id' => $owner->id,
            'jenis' => 'Pemasukan',
            'jumlah' => 500000,
            'tanggal' => now(),
            'keterangan' => 'Pembayaran SPP',
        ]);
    }
}
