<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'orang_tua_id',
        'nama_siswa',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function levelPembelajaran()
    {
        return $this->hasMany(LevelPembelajaran::class);
    }
}
