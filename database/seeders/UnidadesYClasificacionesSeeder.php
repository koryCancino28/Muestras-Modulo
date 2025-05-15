<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadMedida;
use App\Models\Clasificacion;

class UnidadesYClasificacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear las unidades de medida
        $unidades = [
            ['nombre_unidad_de_medida' => 'und'],
            ['nombre_unidad_de_medida' => 'ml'],
            ['nombre_unidad_de_medida' => 'g'],
            //SIN RELACION CON LAS CLISIFICACIONES, nuevas unidades de medida para la calculadora
            ['nombre_unidad_de_medida' => 'mcg'],
            ['nombre_unidad_de_medida' => 'ml'],
            ['nombre_unidad_de_medida' => 'ui'],
            ['nombre_unidad_de_medida' => '%'],
            ['nombre_unidad_de_medida' => 'g'],
            ['nombre_unidad_de_medida' => 'mg'],
            ['nombre_unidad_de_medida' => 'L'],
            ['nombre_unidad_de_medida' => 'kg'],
        ];

        foreach ($unidades as $unidad) {
            UnidadMedida::create($unidad);
        }

        // 2. Crear las clasificaciones con sus unidades correspondientes
        $clasificaciones = [
            // Clasificaciones que usan "und" (unidades)
            'Gomas' => 'und',
            'Capsulas' => 'und',
            'Ovulos' => 'und',
            'Esmalte' => 'und',
            
            // Clasificaciones que usan "ml" (mililitros)
            'Jarabes' => 'ml',
            'Gotas' => 'ml',
            'Soluciones' => 'ml',
            'Solución Nasal' => 'ml',
            'Shampoo' => 'ml',
            'Solución Topico' => 'ml',
            'Solución Oral' => 'ml',
            
            // Clasificaciones que usan "gr" (gramos)
            'Polvo' => 'g',
            'Talco' => 'g',
            'Cremas' => 'g',
            'Unguento' => 'g',
            'Gel' => 'g',
            'Jabon' => 'g'
        ];

        foreach ($clasificaciones as $nombre => $unidad) {
            $unidadMedida = UnidadMedida::where('nombre_unidad_de_medida', $unidad)->first();
            
            Clasificacion::create([
                'nombre_clasificacion' => $nombre,
                'unidad_de_medida_id' => $unidadMedida->id
            ]);
        }
    }
}