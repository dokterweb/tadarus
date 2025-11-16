<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TadarusHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['siswa_id','ustadz_id','surat_id','surat_no', 'dariayat', 'sampaiayat', 'tgl_tadarusnya','keterangan'];

    // Relasi dengan sabaq
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    public function surat()
    {
        return $this->belongsTo(Madina::class, 'surat_id');
    }
    
    public function absensi_siswa()
    {
        return $this->hasOne(Absensi_siswa::class, 'siswa_id', 'siswa_id')
            ->whereColumn('absensi_siswas.tgl_absen', 'tadarus_histories.tgl_tadarusnya');
    }
}
