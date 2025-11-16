<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Iqrohistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['tgl_iqro', 'siswa_id', 'ustadz_id', 'jenisiqro_id', 'hal_awal', 'hal_akhir', 'nilaibacaan'];

    // Relasi dengan sabaq
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    public function jenisiqro()
    {
        return $this->belongsTo(Jenisiqro::class, 'jenisiqro_id');
    }
    
    public function absensi_siswa()
    {
        return $this->hasOne(Absensi_siswa::class, 'siswa_id', 'siswa_id')
            ->whereColumn('absensi_siswas.tgl_absen', 'iqrohistories.tgl_iqro');
    }
}
