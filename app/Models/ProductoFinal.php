<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoFinal extends Model
{
    use HasFactory;
    protected $table = 'producto_final';
    protected $fillable = [
        'nombre',
        'clasificacion_id',
        'unidad_de_medida_id',
        'costo_maquina_id',
        'costo_humano_id',
        'costo_fijo_id',
        'costo_total_produccion',
        'costo_total_real',
        'estado',
        'created_by',
        'updated_by',
    ];

    // Relación con Configuración para costo máquina
    public function costoMaquina()
    {
        return $this->belongsTo(Configuracion::class, 'costo_maquina_id');
    }

    // Relación con Configuración para costo humano
    public function costoHumano()
    {
        return $this->belongsTo(Configuracion::class, 'costo_humano_id');
    }

    // Relación con Configuración para costo fijo
    public function costoFijo()
    {
        return $this->belongsTo(Configuracion::class, 'costo_fijo_id');
    }

    // Relación con ProductoFinalInsumo
    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'producto_final_insumo')
            ->withPivot('cantidad');
    }

    // Relación con ProductoFinalBase
    public function bases()
    {
        return $this->belongsToMany(Base::class, 'producto_final_base')
            ->withPivot('cantidad');
    }

    public function empaques()
    {
        return $this->belongsToMany(Empaque::class, 'empaque_producto_final')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }


        // Calcular costo total de producción
        public function calcularCostoTotalProduccion()
    {
        // Sumar precios de insumos
        $costoInsumos = $this->insumos->sum(function ($insumo) {
            return $insumo->precio * $insumo->pivot->cantidad;
        });

        // Sumar precios de bases
        $costoBases = $this->bases->sum(function ($base) {
            return $base->precio * $base->pivot->cantidad;
        });

        // Sumar precios de empaques (materiales y envases)
        $costoEmpaques = $this->empaques->sum(function ($empaque) {
            return $empaque->costo * $empaque->pivot->cantidad;
        });

        // Obtener los costos fijos desde las configuraciones
        $costoMaquina = $this->costoMaquina ? $this->costoMaquina->valor : 0;
        $costoHumano = $this->costoHumano ? $this->costoHumano->valor : 0;
        $costoFijo = $this->costoFijo ? $this->costoFijo->valor : 0;

        // Sumar todos los costos
        $costoTotal = $costoInsumos + $costoBases + $costoEmpaques + $costoMaquina + $costoHumano + $costoFijo;

        $this->costo_total_produccion = $costoTotal;
        $this->save();

        return $costoTotal;
    }


    // Calcular el costo total real (con IGV)
    public function calcularCostoTotalReal()
    {
        $costoTotalProduccion = $this->calcularCostoTotalProduccion();
        $igv = 0.18; // 18% de IGV
        $costoTotalReal = $costoTotalProduccion * (1 + $igv);
        $this->costo_total_real = $costoTotalReal;
        $this->save();

        return $costoTotalReal;
    }

    // Calcular el costo final con margen de ganancia
    public function calcularCostoFinal($tipoGanancia)
    {
        $costoRealConIgv = $this->calcularCostoTotalReal();

        // Verificar si hay insumos caros en el producto o en su base
        $insumosCarosProducto = $this->insumos->where('es_caro', true);
        $insumosCarosBase = collect();
        
        foreach ($this->bases as $base) {
            if ($base->insumos) {
                $insumosCarosBase = $insumosCarosBase->merge($base->insumos->where('es_caro', true));
            }
        }

        $hayInsumosCaros = $insumosCarosProducto->count() > 0 || $insumosCarosBase->count() > 0;

        // Obtener los márgenes desde la tabla configuraciones
        $margenPublico = Configuracion::where('nombre', 'margen_publico')->first()->valor ?? 1.0;
        $margenMedicoEstandar = Configuracion::where('nombre', 'margen_medico_estandar')->first()->valor ?? 1.0;
        $margenMedicoConInsumosCaros = Configuracion::where('nombre', 'margen_medico_con_insumos_caros')->first()->valor ?? 1.0;

        // Determinar el margen de ganancia
        if ($tipoGanancia === 'publico') {
            $margenGanancia = $margenPublico;
        } elseif ($tipoGanancia === 'medico') {
            $margenGanancia = $hayInsumosCaros ? $margenMedicoConInsumosCaros : $margenMedicoEstandar;
        } else {
            $margenGanancia = $margenPublico;
        }

        return $costoRealConIgv * $margenGanancia;
    }
}