<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bulan extends Model
{
    use HasFactory;
    protected $fillable=['nama_bulan'];
    
    public function bulanans()
    {
        return $this->hasMany(Bulanan::class);
    }
}
