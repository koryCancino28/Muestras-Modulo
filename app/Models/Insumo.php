<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;
    protected $table = 'insumos';

    protected $fillable = [
        'nombre',
        'precio',
        'unidad_de_medida_id',
        'estado',
        'stock',
        'created_by',
        'updated_by',
        'es_caro',
    ];
      public function bases()
    {
        return $this->belongsToMany(Base::class)
            ->withPivot('cantidad')
            ->withTimestamps();
    }
        public function productosFinales()
    {
        return $this->belongsToMany(ProductoFinal::class, 'producto_final_insumo')
                    ->withPivot('cantidad', 'precio');
    }
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_de_medida_id');
    }

}
