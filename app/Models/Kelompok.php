<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelompok extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_kelompok','pelajaran','jenis'];

    public function ustadzs()
    {
        return $this->hasMany(Ustadz::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
