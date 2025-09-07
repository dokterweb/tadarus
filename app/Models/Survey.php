<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'options'];

    // Menggunakan cast untuk field options menjadi array
    protected $casts = [
        'options' => 'array',
    ];
}
