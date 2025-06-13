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
        'created_by',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'precio_total' => 'decimal:4',
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
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}