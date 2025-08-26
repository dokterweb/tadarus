<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sabaq_history extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['sabaq_id', 'surat_id','surat_no', 'dariayat', 'sampaiayat', 'tgl_sabaq', 'nilai', 'keterangan'];

    // Relasi dengan sabaq
    public function sabaq()
    {
        return $this->belongsTo(Sabaq::class, 'sabaq_id', 'id');
    }

    public function surat()
    {
        return $this->belongsTo(Madina::class, 'surat_id');
    }
}
