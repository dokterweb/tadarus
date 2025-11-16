<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Madina extends Model
{
    protected $table = 'madina';

    public function tadarusHistories()
    {
        return $this->hasMany(TadarusHistory::class, 'surat_id');
    }
}
