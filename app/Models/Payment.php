<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['payment_type','periode_id','posnya_id'];

    // Relasi Many-to-One dengan Period
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    // Relasi Many-to-One dengan Pos
    public function posnya()
    {
        return $this->belongsTo(Posnya::class, 'posnya_id');
    }
}
