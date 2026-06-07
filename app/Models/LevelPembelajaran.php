<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelPembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'pengajar_id',
        'level',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pengajar()
    {
        return $this->belongsTo(Pengajar::class);
    }
}
