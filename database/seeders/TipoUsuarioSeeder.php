<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_usuarios')->insert([
            ['id' => 1, 'rol' => 'Administrador'],
            ['id' => 2, 'rol' => 'Entrenador'],
            ['id' => 3, 'rol' => 'Atleta'],
        ]);
    }
}