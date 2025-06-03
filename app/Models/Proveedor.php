<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;

    protected $table = 'proveedores';
    protected $fillable = [
        'razon_social',
        'ruc',
        'direccion',
        'correo',
        'correo_cpe',
        'telefono_1',
        'telefono_2',
        'persona_contacto',
        'observacion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'string'
    ];
    // Relaciones
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}