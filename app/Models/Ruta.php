<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use App\Traits\LogsActivity;

class Ruta extends Model
{
    use HasUuids, HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'vehiculo_id', // Añadido para el Eager Loading
        'nombre',
        'codigo',
        'descripcion',
        'distancia_km',
        'duracion_estimada',
        'estado',
        'origen',
        'destino'
    ];

    protected $casts = [
        'parametros_ruta' => 'json',
        'distancia_km' => 'float',
    ];

    protected $appends = ['origen_coords', 'destino_coords'];

    /**
     * RELACIONES
     */

    // Cambiado de 'usuario' a 'conductor' para que funcione con: 
    // Ruta::with(['conductor'])
    public function conductor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Definimos 'vehiculo' para que funcione con: 
    // Ruta::with(['vehiculo'])
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function paradas(): HasMany
    {
        return $this->hasMany(Parada::class)->orderBy('numero_orden');
    }

    /**
     * ACCESSORS PARA POSTGIS
     */

    public function getOrigenCoordsAttribute()
    {
        $coords = DB::table('rutas')
            ->selectRaw('ST_X(origen::geometry) as lng, ST_Y(origen::geometry) as lat')
            ->where('id', $this->id)
            ->first();

        return $coords ? ['lat' => (float)$coords->lat, 'lng' => (float)$coords->lng] : null;
    }

    public function getDestinoCoordsAttribute()
    {
        $coords = DB::table('rutas')
            ->selectRaw('ST_X(destino::geometry) as lng, ST_Y(destino::geometry) as lat')
            ->where('id', $this->id)
            ->first();

        return $coords ? ['lat' => (float)$coords->lat, 'lng' => (float)$coords->lng] : null;
    }
}