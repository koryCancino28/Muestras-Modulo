<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empaque extends Model
{
    use HasFactory;

    protected $table = 'empaques';
    public $timestamps = false;
    protected $fillable = [
        'articulo_id',
        'tipo',
        'precio',
    ];

        public function ultimoLote()
    {
        return $this->hasOne(Lote::class, 'articulo_id', 'articulo_id')->latestOfMany();
    }
    public function articulo()
    {
        return $this->belongsTo(Articulo::class); 
    }

    public function bases()
    {
        return $this->belongsToMany(Base::class)
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    /**
     * Filtro por tipo de empaque.
     */
    public function scopeMaterial($query)
    {
        return $query->where('tipo', 'material');
    }

    public function scopeEnvase($query)
    {
        return $query->where('tipo', 'envase');
    }
}
