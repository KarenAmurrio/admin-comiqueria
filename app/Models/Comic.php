<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    protected $fillable = ['titulo', 'categoria', 'descripcion', 'precio', 'stock', 'imagen_url', 'formato', 'editorial', 'genero', 'demografia'];

    protected $casts = [
        'editorial' => 'array',
        'genero' => 'array',
        'demografia' => 'array',
    ];
}