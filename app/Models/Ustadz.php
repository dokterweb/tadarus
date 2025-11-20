<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ustadz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'kelompok_id','mengajari','kelamin'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kelompok()
    {
        return $this->belongsTo(kelompok::class, 'kelompok_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi_ustadz::class, 'ustadz_id');
    }
}
