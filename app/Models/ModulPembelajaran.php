<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulPembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_modul',
        'level',
        'stok',
    ];

    public function transaksiModul()
    {
        return $this->hasMany(TransaksiModul::class, 'modul_id');
    }
}
