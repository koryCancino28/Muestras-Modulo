<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{

    protected $table = 'merchandises';
    public $timestamps = false;

    protected $fillable = [
        'articulo_id',
        'precio',
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}