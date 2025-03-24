<?php

namespace App\Models;

use App\Models\UnidadMedida; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
    use HasFactory;

    // Definir los campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre_clasificacion',
        'unidad_de_medida_id',
    ];

    // Si el nombre de la tabla no sigue la convención de pluralización
    protected $table = 'clasificaciones';

    // Relación con las unidades de medida
    public function unidadMedida() // Método en minúsculas y singular
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_de_medida_id'); // Relación con la tabla 'unidad_de_medida'
    }

    // Define la relación con la tabla de muestras
    public function muestras()
    {
        return $this->hasMany(Muestras::class); // Reemplazado Muestra::class por Muestras::class
    }
}
