<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;
 
class Ruta extends Model
{
    use LogsActivity;
 
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'origen',
        'origen_lat',
        'origen_lng',
        'destino',
        'destino_lat',
        'destino_lng',
        'distancia_km',
        'duracion_estimada',
        'estado',
        'conductor_id',
        'paradas'
    ];
 
    protected $casts = [
        'paradas' => 'array',
    ];
 
    /**
     * Relación con Conductor
     */
    public function conductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class);
    }
 
    /**
     * Relación con Paradas
     */
    public function paradas(): HasMany
    {
        return $this->hasMany(Parada::class)->orderBy('orden');
    }
 
    /**
     * Relación con Confirmaciones de Paradas
     */
    public function confirmaciones(): HasMany
    {
        return $this->hasMany(ParadaConfirmacion::class);
    }
 
    /**
     * Obtener todas las confirmaciones de esta ruta por conductor
     */
    public function confirmacionesPorConductor($conductorId): HasMany
    {
        return $this->confirmaciones()->where('conductor_id', $conductorId);
    }
 
    /**
     * Accesor para obtener coordenadas de origen como array
     */
    public function getOriginCoordinatesAttribute(): array
    {
        return [
            'lat' => $this->origen_lat ?? 4.8604,
            'lng' => $this->origen_lng ?? -74.0447,
        ];
    }
 
    /**
     * Accesor para obtener coordenadas de destino como array
     */
    public function getDestinationCoordinatesAttribute(): array
    {
        return [
            'lat' => $this->destino_lat ?? 4.7110,
            'lng' => $this->destino_lng ?? -74.0076,
        ];
    }
 
    /**
     * Scope para rutas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }
 
    /**
     * Scope para rutas con coordenadas completas
     */
    public function scopeConCoordenadas($query)
    {
        return $query->whereNotNull('origen_lat')
                    ->whereNotNull('origen_lng')
                    ->whereNotNull('destino_lat')
                    ->whereNotNull('destino_lng');
    }
}