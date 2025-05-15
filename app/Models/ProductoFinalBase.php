<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoFinalBase extends Model
{
    use HasFactory;

    protected $table = 'producto_final_base';

    protected $fillable = [
        'producto_final_id',
        'base_id',
        'cantidad',
    ];
}
