<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;
    protected $table = 'insumos';
    public $timestamps = false;
    protected $fillable = [
        'articulo_id',
        'precio',
        'unidad_de_medida_id',
        'es_caro',
    ];
    public function ultimoLote()
    {
        return $this->hasOneThrough(
            Lote::class,
            Articulo::class,
            'id',           // foreign key on Articulo table
            'articulo_id',  // foreign key on Lote table
            'articulo_id',  // local key on Insumo table
            'id'            // local key on Articulo table
        )->latestOfMany(); // o ->latest('fecha_vencimiento');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class); 
    }
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
