<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar verificación de claves foráneas para permitir truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar la tabla antes de poblar
        Equipo::truncate();

        // Reactivar verificación
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $equipos = [
            'Bandas',
            'Barra',
            'Mancuerna',
            'Pesa Rusa',
            'Peso Corporal',
            'Polea',
            'Smith',
            'Máquina',
        ];

        foreach ($equipos as $nombre) {
            Equipo::create(['nombre' => $nombre]);
        }
    }
}
