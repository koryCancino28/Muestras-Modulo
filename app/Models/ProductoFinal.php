<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoFinal extends Model
{
    use HasFactory;
    protected $table = 'producto_final';
    public $timestamps = false;

    protected $fillable = [
        'articulo_id', 
        'costo_total_produccion',
        'costo_total_real',
        'costo_total_publicado',
        'volumen_id', 
        'updated_by'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class); 
    }
    
    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    public function unidadDeMedida()
    {
         return $this->hasOneThrough(
            \App\Models\UnidadMedida::class,
            \App\Models\Clasificacion::class,
            'id', 
            'id', 
            'clasificacion_id', 
            'unidad_de_medida_id' 
        );
    }

    public function bases()
    {
        return $this->belongsToMany(Base::class, 'producto_final_base')->withPivot('cantidad');
    }
    public function volumen()
    {
        return $this->belongsTo(Volumen::class);
    }
    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'producto_final_insumo')->withPivot('cantidad');
    }

    public function calcularCostos()
    {
        $config = Configuracion::pluck('valor', 'nombre');

        // 1. Cálculo de costos (se mantiene igual)
        $costoInsumos = $this->insumos->sum(fn($i) => $i->precio * $i->pivot->cantidad);
        $costoBases = $this->bases->sum(fn($b) => $b->precio * $b->pivot->cantidad);
        $costoFijo = ($config['costo_fijo'] ?? 0) + ($config['costo_maquina'] ?? 0) + ($config['costo_humano'] ?? 0);

        $costoTotalProduccion = $costoInsumos + $costoBases + $costoFijo;
        $costoTotalReal = $costoTotalProduccion * 1.18;

        // 2. Verificación COMPLETA de insumos caros
        $tieneInsumosCaros = $this->tieneInsumosCarosDirectos() || 
                            $this->tieneInsumosCarosEnBases() ||
                            $this->tieneInsumosCarosEnPrebases();

        // 3. Cálculo de margen (se mantiene igual)
        $margen = match ($this->clasificacion->nombre ?? '') {
            'publico' => $config['margen_publico'] ?? 1.702,
            'medico' => $tieneInsumosCaros ? ($config['margen_medico_con_insumos_caros'] ?? 1.5)
                                        : ($config['margen_medico_estandar'] ?? 1.05),
            default => $config['margen_publico'] ?? 1.702,
        };

        $this->update([
            'costo_total_produccion' => $costoTotalProduccion,
            'costo_total_real' => $costoTotalReal,
            'costo_total_publicado' => $costoTotalReal * $margen,
        ]);
    }

    public function tieneInsumosCarosDirectos()
    {
        return $this->insumos()->where('es_caro', true)->exists();
    }

    public function tieneInsumosCarosEnBases()
    {
        return DB::table('producto_final_base')
            ->join('base_insumo', 'producto_final_base.base_id', '=', 'base_insumo.base_id')
            ->join('insumos', 'base_insumo.insumo_id', '=', 'insumos.id')
            ->where('producto_final_base.producto_final_id', $this->id)
            ->where('insumos.es_caro', true)
            ->exists();
    }

    public function tieneInsumosCarosEnPrebases()
    {
        return DB::table('producto_final_base')
            ->join('base_prebase', 'producto_final_base.base_id', '=', 'base_prebase.base_id')
            ->join('base_insumo', 'base_prebase.prebase_id', '=', 'base_insumo.base_id')
            ->join('insumos', 'base_insumo.insumo_id', '=', 'insumos.id')
            ->where('producto_final_base.producto_final_id', $this->id)
            ->where('insumos.es_caro', true)
            ->exists();
    }
}
