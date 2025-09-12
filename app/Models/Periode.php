<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periode extends Model
{
    use HasFactory, SoftDeletes;
    // Tentukan nama tabel jika berbeda dari default plural
    protected $table = 'periodes'; // pastikan ini sesuai dengan nama tabel di database
    protected $primaryKey = 'id';
    protected $fillable=['periode_start','periode_end','periode_status'];

    // Relasi One-to-Many dengan Payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'periode_id');
    }
}
