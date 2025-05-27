<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoMonedaSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipo_moneda')->updateOrInsert(
            ['codigo_iso' => 'USD'],
            ['nombre' => 'Dólar']
        );

        DB::table('tipo_moneda')->updateOrInsert(
            ['codigo_iso' => 'PEN'],
            ['nombre' => 'Sol']
        );
    }
}

