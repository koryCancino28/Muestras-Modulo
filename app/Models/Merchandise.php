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
    public function ultimoLote()
    {
        return $this->hasOne(Lote::class, 'articulo_id', 'articulo_id')->latestOfMany();
    }
}