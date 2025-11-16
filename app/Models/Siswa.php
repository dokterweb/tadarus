<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama_siswa','kelas_id', 'kelompok_id', 'kelamin'];

    public function kelasnya()
    {
        return $this->belongsTo(Kelasnya::class, 'kelas_id', 'id');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }

    public function tadarusHistories()
    {
        return $this->hasMany(TadarusHistory::class, 'siswa_id', 'id'); 
    }

   
    public function iqros()
    {
        return $this->hasMany(iqro::class, 'siswa_id', 'id');
    }

}
