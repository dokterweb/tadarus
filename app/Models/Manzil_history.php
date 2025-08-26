<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manzil_history extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['manzil_id', 'surat_id', 'surat_no', 'dariayat', 'sampaiayat', 'tgl_manzil', 'nilai', 'keterangan'];

      // Relasi dengan manzil
      public function manzil()
      {
          return $this->belongsTo(Manzil::class, 'manzil_id', 'id');
      }

      public function surat()
    {
        return $this->belongsTo(Madina::class, 'surat_id');
    }
}
