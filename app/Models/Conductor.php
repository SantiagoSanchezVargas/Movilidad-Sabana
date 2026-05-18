<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;


class Conductor extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'conductores'; // Especificamos el nombre de la tabla
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'id',
    'user_id',
    'nombre',
    'licencia',
    'telefono',
    'estado'
];
    public function user()
{
    return $this->belongsTo(User::class);
}
}