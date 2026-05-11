<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Viaje extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ruta_id', 
        'vehiculo_id', 
        'conductor_id', 
        'fecha_programada', 
        'fecha_inicio_real', 
        'fecha_fin_real', 
        'estado', 
        'pasajeros_actuales'
    ];

    // Relaciones principales
    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionViaje::class);
    }
}