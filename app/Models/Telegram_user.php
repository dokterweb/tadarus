<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Telegram_user extends Model
{
    use HasFactory;
    
    protected $table = 'telegram_users';

    protected $fillable = [
        'siswa_id',
        'telegram_id',
    ];

    /**
     * Relasi ke tabel siswas
     * Satu telegram user hanya milik satu siswa.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
