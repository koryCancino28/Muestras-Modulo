<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muestras extends Model
{
    use HasFactory;

    protected $dates = ['fecha_hora_entrega'];
    protected $table = 'muestras';

    // Eliminamos 'unidad_de_medida_id' del fillable ya que no existirá
    protected $fillable = [
        'nombre_muestra',
        'observacion',
        'cantidad_de_muestra',
        'precio',
        'estado',
        'clasificacion_id', // Mantenemos esta relación
        'fecha_hora_entrega',
        'tipo_muestra',
        'aprobado_jefe_comercial',
        'aprobado_coordinadora'
    ];

    // Relación con Clasificacion
    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    // Accesor para obtener la unidad de medida a través de la clasificación
    public function getUnidadDeMedidaAttribute()
    {
        return $this->clasificacion ? $this->clasificacion->unidadMedida : null;
    }
}