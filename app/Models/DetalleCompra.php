<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compra';

    protected $fillable = [
        'compra_id',
        'lote_id',
        'cantidad',
        'precio'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'decimal:4',
        'fecha_vencimiento' => 'date'
    ];

    // Relaciones
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

     public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    // Métodos auxiliares
    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->precio;
    }
}