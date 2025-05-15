<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    use HasFactory;
    protected $table = 'base';
    protected $fillable = [
        'nombre',
        'clasificacion_id',
        'tipo',
        'precio',
        'volumen_id',
        'unidad_de_medida_id',
        'cantidad',
        'created_by',
        'updated_by',
    ];
    
    // Base.php
    public function insumos() {
        return $this->belongsToMany(Insumo::class)->withPivot('cantidad');
    }

    public function empaques() {
        return $this->belongsToMany(Empaque::class)->withPivot('cantidad');
    }

    public function prebases() {
        return $this->belongsToMany(Base::class, 'base_prebase', 'base_id', 'prebase_id')->withPivot('cantidad');
    }
    public function unidadDeMedida()
    {
        return $this->hasOneThrough(
            \App\Models\UnidadMedida::class,
            \App\Models\Clasificacion::class,
            'id', 
            'id', 
            'clasificacion_id', 
            'unidad_de_medida_id' 
        );
    }

    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    public function volumen()
    {
        return $this->belongsTo(Volumen::class);
    }

}
