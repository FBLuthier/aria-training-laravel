<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1, 'rol' => 'Administrador'],
            ['id' => 2, 'rol' => 'Entrenador'],
            ['id' => 3, 'rol' => 'Atleta'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_usuarios')->updateOrInsert(['id' => $tipo['id']], $tipo);
        }
    }
}
