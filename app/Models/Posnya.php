<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posnya extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['pos_name','keterangan'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'posnya_id');
    }
}
