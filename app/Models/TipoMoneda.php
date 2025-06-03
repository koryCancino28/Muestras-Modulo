<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMoneda extends Model
{
    use HasFactory;
    
    protected $table = 'tipo_moneda';

    protected $fillable = [
        'nombre',
        'codigo_iso',
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'moneda_id');
    }
    public function tiposCambios()
    {
        return $this->hasMany(TipoCambio::class, 'tipo_moneda_id');
    }

        public function ultimoCambio()
    {
        return $this->hasOne(TipoCambio::class)->latestOfMany('fecha');
    }

}
