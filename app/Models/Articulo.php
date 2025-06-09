<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;
    protected $table = 'articulos';
    public $timestamps = true;  

    protected $fillable = [
        'sku',
        'nombre',
        'descripcion',
        'tipo',
        'stock',
        'estado',
        'created_by',
        'updated_by',
    ];

           public static function boot()
    {
        parent::boot();

        static::creating(function ($articulo) {
            // Obtener el último número de SKU
            $ultimoSku = DB::table('articulos')
                ->where('sku', 'like', 'GROB_%')
                ->orderBy('id', 'desc')
                ->value('sku');

            if ($ultimoSku) {
                $numero = (int) Str::after($ultimoSku, 'GROB_') + 1;
            } else {
                $numero = 1;
            }

            $articulo->sku = 'GROB_' . str_pad($numero, 3, '0', STR_PAD_LEFT);
        });
    }
    public function bases()
    {
        return $this->hasMany(Base::class);
    }

    public function productosFinales()
    {
        return $this->hasMany(ProductoFinal::class);
    }

    public function empaques()
    {
        return $this->hasMany(Empaque::class);
    }
    public function util()
    {
        return $this->hasMany(Util::class);
    }

    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }
    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}

