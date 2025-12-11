<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GrupoMuscularSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Los 3 Principales (Orden Fijo)
        $principales = [
            'Cuerpo Completo',
            'Tren Superior',
            'Tren Inferior',
        ];

        // 2. El resto (Orden Alfabético)
        $secundarios = [
            'Antebrazo',
            'Bíceps',
            'Core',
            'Cuádriceps',
            'Espalda',
            'Glúteo',
            'Hombros',
            'Isquiotibiales',
            'Pantorrilla',
            'Pecho',
            'Tríceps',
        ];

        // Combinar
        $todos = array_merge($principales, $secundarios);

        // Insertar
        foreach ($todos as $nombre) {
            // Usamos Eloquent para que los timestamps se llenen automáticamente
            \App\Models\GrupoMuscular::firstOrCreate(
                ['nombre' => $nombre]
            );
        }
    }
}
