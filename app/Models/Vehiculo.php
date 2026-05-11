<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Vehiculo extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'vehiculos'; // Laravel a veces busca 'vehiculos' (con s)

    protected $fillable = [
        'placa', 'modelo', 'marca', 'capacidad', 'estado', 'conductor_id'
    ];

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }
}