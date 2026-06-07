<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModul extends Model
{
    use HasFactory;

    protected $fillable = [
        'modul_id',
        'jenis',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    public function modul()
    {
        return $this->belongsTo(ModulPembelajaran::class, 'modul_id');
    }
}
