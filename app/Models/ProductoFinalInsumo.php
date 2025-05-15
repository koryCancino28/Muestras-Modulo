<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoFinalInsumo extends Model
{
    use HasFactory;

    protected $table = 'producto_final_insumo';

    protected $fillable = [
        'producto_final_id',
        'insumos_id',
        'cantidad',
    ];
}
