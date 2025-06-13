<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $fillable = [
        'articulo_id',
        'num_lote',
        'fecha_vencimiento',
        'precio'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'precio' => 'decimal:4'
    ];

    // Relaciones
    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleLote::class);
    }
}