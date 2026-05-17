<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB; // Agrega esto
use App\Traits\LogsActivity;


class Parada extends Model
{
    use HasUuids, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ruta_id',
        'nombre',
        'numero_orden',
        'lat',
        'lng',
        'tarifa_desde_origen',
        'tipo_parada',
        'ubicacion',
        'descripcion',
        'radio_metros',
        'es_obligatoria'
    ];

    // Esto evita que el binario de PostGIS ensucie tu JSON
    protected $hidden = [
        'ubicacion'
    ];

    protected $appends = ['ubicacion_coords'];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function getUbicacionCoordsAttribute()
    {
        $coords = DB::table('paradas')
            ->selectRaw('ST_X(ubicacion::geometry) as lng, ST_Y(ubicacion::geometry) as lat')
            ->where('id', $this->id)
            ->first();

        return $coords ? ['lat' => (float)$coords->lat, 'lng' => (float)$coords->lng] : null;
    }
    /* ==========================================
       MUTATOR PARA EVITAR ERROR DE GEOMETRÍA
    ========================================== */
    public function setUbicacionAttribute($value)
{
    // Si mandamos lat y lng explícitamente en el array de creación
    if (isset($this->attributes['lat']) && isset($this->attributes['lng'])) {
        $lat = $this->attributes['lat'];
        $lng = $this->attributes['lng'];
        $this->attributes['ubicacion'] = DB::raw("ST_GeomFromText('POINT($lng $lat)', 4326)");
    } 
    // Si por alguna razón no hay coordenadas, evitamos el string vacío que rompe PostGIS
    else {
        $this->attributes['ubicacion'] = null;
    }
}
}