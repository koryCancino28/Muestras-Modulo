<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            [
                'id' => 1,
                'nombre' => 'costo_humano',
                'valor' => 26.30,
                'descripcion' => 'Costo humano fijo',
            ],
            [
                'id' => 2,
                'nombre' => 'costo_maquina',
                'valor' => 0.28,
                'descripcion' => 'Costo fijo por uso de maquinaria',
            ],
            [
                'id' => 3,
                'nombre' => 'costo_fijo',
                'valor' => 1.50,
                'descripcion' => 'Otros costos fijos de producción',
            ],
            [
                'id' => 4,
                'nombre' => 'margen_publico',
                'valor' => 1.702,  
                'descripcion' => 'Margen de venta para público',
            ],
            [
                'id' => 5,
                'nombre' => 'margen_medico_estandar',
                'valor' => 1.05,  
                'descripcion' => 'Margen de venta estándar para médicos',
            ],
            [
                'id' => 6,
                'nombre' => 'margen_medico_con_insumos_caros',
                'valor' => 1.5,  
                'descripcion' => 'Margen de venta para médicos con insumos caros',
            ],
        ]);
    }
}
