<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'serie',
        'numero',
        'precio_total',
        'proveedor_id',
        'fecha_emision',
        'condicion_pago',
        'moneda_id',
        'igv',
        'referencia'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'precio_total' => 'decimal:2',
        'igv' => 'decimal:2'
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function moneda()
    {
        return $this->belongsTo(TipoMoneda::class, 'moneda_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    // Métodos auxiliares
    public function calcularTotal()
    {
        $subtotal = $this->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio;
        });

        return $subtotal + $this->igv;
    }

    public function calcularSubtotal()
    {
        return $this->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio;
        });
    }
}