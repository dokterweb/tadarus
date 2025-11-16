<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi_ustadz extends Model
{
    protected $fillable = [
        'tgl_absen', 
        'status', 
        'ustadz_id', 
        'keterangan'
    ];

    // Relasi dengan model Ustadz
    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class, 'ustadz_id');
    }
}
