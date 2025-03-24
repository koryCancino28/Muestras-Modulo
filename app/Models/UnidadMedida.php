<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{

    protected $table = 'unidad_de_medida'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre_unidad_de_medida',
    ];
    public function clasificaciones()
    {
        return $this->hasMany(Clasificacion::class);
    }
    // Relación con la tabla 'unidad_de_medida'
    public function muestras()
    {
        return $this->HasMany(Muestras::class);
    }
}
