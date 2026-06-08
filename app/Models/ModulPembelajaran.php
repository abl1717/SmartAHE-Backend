<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulPembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'nama',
        'stok',
        'level',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function transaksiModul()
    {
        return $this->hasMany(TransaksiModul::class);
    }
}
