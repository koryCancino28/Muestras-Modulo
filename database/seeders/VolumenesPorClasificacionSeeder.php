<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clasificacion;
use App\Models\Volumen;

class VolumenesPorClasificacionSeeder extends Seeder
{
    public function run(): void
    {
        $datos = [
            'Capsulas' => [30],
            'Polvo' => [100, 250, 500, 1000],
            'Talco' => [80, 100, 110, 120],
            'Cremas' => [10, 15, 20, 30, 40, 50],
            'Gomas' => [30],
            'Unguento' => [10, 15, 20, 30, 40, 50],
            'Jarabes' => [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 150],
            'Solución Topico' => [5, 10, 15, 20, 25, 30, 40, 50, 60, 70, 80, 90, 100],
            'Jabon' => [100, 150, 250, 500, 1000],
            'Shampoo' => [100, 150, 250, 500, 1000],
            'Gel' => [10, 15, 20, 30, 40, 50],
            'Supositorio' => [5, 10, 15, 20],
            'Solución Oral' => [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 150],
        ];

        foreach ($datos as $nombreClasificacion => $volumenes) {
            $clasificacion = Clasificacion::where('nombre_clasificacion', $nombreClasificacion)->first();

            if ($clasificacion) {
                foreach ($volumenes as $valor) {
                    Volumen::create([
                        'clasificacion_id' => $clasificacion->id,
                        'nombre' => $valor
                    ]);
                }
            }
        }
    }
}
