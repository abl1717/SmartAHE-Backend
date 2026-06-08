<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modulPembelajaran()
    {
        return $this->hasMany(ModulPembelajaran::class);
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class);
    }
}
