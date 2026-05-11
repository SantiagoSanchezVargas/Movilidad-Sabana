<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Incidente extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['titulo', 'descripcion', 'latitud', 'longitud', 'tipo', 'activo'];

    protected static function booted()
    {
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }
}