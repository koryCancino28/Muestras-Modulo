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

    protected $table = 'clasificaciones';


    public function unidadMedida() 
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_de_medida_id'); 
    }

    public function muestras()
    {
        return $this->hasMany(Muestras::class);
    }
    public function volumenes()
    {
        return $this->hasMany(Volumen::class, 'clasificacion_id');
    }
    public function bases()
    {
        return $this->hasMany(Base::class);
    }
}
