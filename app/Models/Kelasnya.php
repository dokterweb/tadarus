<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelasnya extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_kelas'];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
