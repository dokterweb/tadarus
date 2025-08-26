<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Iqro_history extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['iqro_id', 'iqro_jilid', 'halaman', 'nilai', 'keterangan'];

      // Relasi dengan iqro
      public function iqro()
      {
          return $this->belongsTo(Iqro::class, 'iqro_id', 'id');
      }
}
