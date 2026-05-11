<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReporteCongestion extends Model
{
    use HasUuids;

    protected $table = 'reportes_congestion';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 
        'viaje_id', 
        'tipo', 
        'descripcion', 
        'nivel_severidad'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }
}