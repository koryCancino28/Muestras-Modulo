<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volumen extends Model
{
    use HasFactory;
    protected $table = 'volumenes';

    protected $fillable = [
        'nombre',
        'clasificacion_id',
    ];

    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }
  
}
