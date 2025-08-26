<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'kelas_id', 'ustadz_id', 'kelamin', 'tempat_lahir', 'tgl_lahir', 'alamat', 'nama_ayah', 'nama_ibu', 'no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kelasnya()
    {
        return $this->belongsTo(Kelasnya::class, 'kelas_id', 'id');
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class, 'ustadz_id', 'id');
    }

    public function sabaqs()
    {
        return $this->hasMany(Sabaq::class, 'siswa_id', 'id');
    }

    public function sabqis()
    {
        return $this->hasMany(Sabqi::class, 'siswa_id', 'id');
    }

    public function manzils()
    {
        return $this->hasMany(Manzil::class, 'siswa_id', 'id');
    }

    public function iqros()
    {
        return $this->hasMany(iqro::class, 'siswa_id', 'id');
    }

    public function sabaqHistories()
    {
        return $this->hasManyThrough(
            Sabaq_history::class,
            Sabaq::class,
            'siswa_id',      // Foreign key di tabel Sabaq (tabel perantara)
            'sabaq_id',      // Foreign key di tabel SabaqHistory
            'id',            // Local key di model Siswa
            'id'             // Local key di model Sabaq
        );
    }

    public function sabqiHistories()
    {
        return $this->hasManyThrough(
            Sabqi_history::class,
            Sabqi::class,
            'siswa_id',
            'sabqi_id',
            'id',
            'id'
        );
    }

    public function manzilHistories()
    {
        return $this->hasManyThrough(
            Manzil_history::class,
            Manzil::class,
            'siswa_id',
            'manzil_id',
            'id',
            'id'
        );
    }

    public function iqroHistories()
    {
        return $this->hasManyThrough(
            Iqro_history::class,
            Iqro::class,
            'siswa_id',
            'iqro_id',
            'id',
            'id'
        );
    }
}
