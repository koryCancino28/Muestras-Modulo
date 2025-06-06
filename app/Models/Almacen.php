<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacenes';

    protected $fillable = [
        'nombre',
        'ubicacion'
    ];

    // Relaciones
    public function detalleLotes()
    {
        return $this->hasMany(DetalleLote::class);
    }
}