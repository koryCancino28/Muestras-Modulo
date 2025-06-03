<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleLote extends Model
{
    use HasFactory;

    protected $table = 'detalle_lote';

    protected $fillable = [
        'lote_id',
        'stock',
        'almacen_id'
    ];

    protected $casts = [
        'stock' => 'integer'
    ];

    // Relaciones
    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
}