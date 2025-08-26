<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Iqro extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['siswa_id','ustadz_id'];

    // Relasi dengan Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    // Relasi dengan Ustadz
    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class, 'ustadz_id', 'id');
    }

      // Relasi dengan manzil_histories
      public function iqroHistories()
      {
          return $this->hasMany(Iqro_history::class, 'iqro_id', 'id');
      }
}
