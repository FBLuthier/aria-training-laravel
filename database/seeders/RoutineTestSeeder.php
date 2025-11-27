<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Objetivo;
use App\Models\Rutina;
use App\Models\User;

class RoutineTestSeeder extends Seeder
{
    public function run(): void
    {
        $objetivo = Objetivo::firstOrCreate(['nombre' => 'Hipertrofia']);
        
        $usuario = User::where('tipo_usuario_id', 3)->first();
        
        if (!$usuario) {
            $this->command->error('No athlete found!');
            return;
        }

        $rutina = Rutina::create([
            'nombre' => 'Rutina Test Seeder',
            'usuario_id' => $usuario->id,
            'objetivo_id' => $objetivo->id,
            'estado' => 1,
        ]);

        $this->command->info('Rutina created with ID: ' . $rutina->id);
    }
}
