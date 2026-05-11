<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // <--- AGREGAR ESTO
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
    'name',      // Cámbialo de 'nombre' a 'name'
    'apellido',
    'telefono',
    'estado',
    'email',
    'password',
    'role_id',   // Relación profesional
];
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relación con Roles
    public function rol()
    {
        // Esto busca la tabla 'roles' usando 'role_id'
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasRole($roleNombre)
    {
        // Verifica si el nombre del rol coincide (ej. 'administrador')
        return $this->rol?->nombre === $roleNombre;
    }
}