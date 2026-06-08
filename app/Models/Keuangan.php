<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'jenis',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
