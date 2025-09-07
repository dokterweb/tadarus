<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi_siswa extends Model
{
    use HasFactory;
    protected $fillable = ['tgl_absen', 'status', 'siswa_id'];

    // Relasi dengan siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id'); 
    }
}
