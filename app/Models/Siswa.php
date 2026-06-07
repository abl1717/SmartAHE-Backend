<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'orang_tua_id',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
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
