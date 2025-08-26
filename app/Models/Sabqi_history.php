<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sabqi_history extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['sabqi_id', 'surat_id', 'surat_no', 'dariayat', 'sampaiayat', 'tgl_sabqi', 'nilai','keterangan'];

      // Relasi dengan sabaq
      public function sabqi()
      {
          return $this->belongsTo(Sabqi::class, 'sabqi_id', 'id');
      }

     public function surat()
    {
        return $this->belongsTo(Madina::class, 'surat_id');
    }
}
