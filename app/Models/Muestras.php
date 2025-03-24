<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Muestras extends Model
{
    use HasFactory;

    protected $dates = ['fecha_hora_entrega'];
    protected $table = 'muestras'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre_muestra',
        'observacion',
        'cantidad_de_muestra',
        'precio',
        'estado',
        'unidad_de_medida_id',
        'fecha_hora_entrega'
    ];

       // Define la relación con la tabla de clasificaciones
       public function clasificacion()
       {
           return $this->belongsTo(Clasificacion::class);
       }
    // Relación con la tabla 'unidad_de_medida'
    public function unidadDeMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_de_medida_id');
    }
}
