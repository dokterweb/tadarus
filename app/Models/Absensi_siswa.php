<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi_siswa extends Model
{
    protected $fillable = ['tgl_absen', 'status', 'siswa_id','keterangan'];

    // Relasi dengan siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id'); 
    }

   
}
