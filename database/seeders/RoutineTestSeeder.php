<?php

namespace Database\Seeders;

use App\Models\Objetivo;
use App\Models\Rutina;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoutineTestSeeder extends Seeder
{
    public function run(): void
    {
        $objetivo = Objetivo::firstOrCreate(['nombre' => 'Hipertrofia']);

        $usuario = User::where('tipo_usuario_id', 3)->first();

        if (! $usuario) {
            $this->command->error('No athlete found!');

            return;
        }

        $rutina = Rutina::create([
            'nombre' => 'Rutina Test Seeder',
            'usuario_id' => $usuario->id,
            'objetivo_id' => $objetivo->id,
            'estado' => 1,
        ]);

        $this->command->info('Rutina created with ID: '.$rutina->id);
    }
}
