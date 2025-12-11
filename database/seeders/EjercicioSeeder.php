<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\Equipo;
use App\Models\GrupoMuscular;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EjercicioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiar tabla (Desactivar FKs para permitir truncate)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ejercicio::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Obtener IDs de Equipos y Grupos Musculares para optimizar
        $equipos = Equipo::pluck('id', 'nombre')->toArray();
        $grupos = GrupoMuscular::pluck('id', 'nombre')->toArray();

        // 3. Definir Datos (Basado en propuesta_ejercicios.md)
        $data = [
            'Pecho' => [
                'Press de Banca Plano' => ['Barra', 'Mancuerna', 'Smith'],
                'Press Inclinado' => ['Barra', 'Mancuerna', 'Smith'],
                'Aperturas (Flyes)' => ['Mancuerna', 'Polea', 'Máquina'],
                'Flexiones (Push-ups)' => ['Peso Corporal', 'Máquina'],
                'Fondos en Paralelas (Dips)' => ['Peso Corporal', 'Máquina'],
            ],
            'Espalda' => [
                'Dominadas (Pull-ups)' => ['Peso Corporal', 'Máquina'],
                'Jalón al Pecho (Lat Pulldown)' => ['Polea', 'Máquina'],
                'Remo Horizontal' => ['Barra', 'Mancuerna', 'Polea', 'Máquina'],
                'Remo al Mentón' => ['Barra', 'Polea'],
                'Hiperextensiones' => ['Peso Corporal'],
            ],
            'Piernas' => [
                'Sentadilla (Squat)' => ['Barra', 'Mancuerna', 'Peso Corporal', 'Smith'],
                'Prensa de Piernas' => ['Máquina'],
                'Zancadas (Lunges)' => ['Mancuerna', 'Barra', 'Peso Corporal'],
                'Peso Muerto (Deadlift)' => ['Barra', 'Mancuerna'],
                'Extensiones de Cuádriceps' => ['Máquina'],
                'Curl Femoral (Tumbado/Sentado)' => ['Máquina'],
                'Elevación de Talones (Gemelos)' => ['Máquina', 'Mancuerna', 'Peso Corporal'],
            ],
            'Hombros' => [
                'Press Militar (Overhead Press)' => ['Barra', 'Mancuerna', 'Máquina', 'Smith'],
                'Elevaciones Laterales' => ['Mancuerna', 'Polea'],
                'Elevaciones Frontales' => ['Mancuerna', 'Polea'],
                'Pájaros (Posterior)' => ['Mancuerna', 'Máquina'],
            ],
            'Brazos' => [
                'Curl de Bíceps' => ['Barra', 'Mancuerna', 'Polea'],
                'Curl Martillo' => ['Mancuerna', 'Polea'],
                'Curl Predicador' => ['Máquina', 'Mancuerna'],
                'Extensiones de Tríceps' => ['Polea'],
                'Press Francés' => ['Mancuerna'],
                'Patada de Tríceps' => ['Mancuerna', 'Polea'],
            ],
            'Abdominales' => [ // Mapeado a 'Core' en la propuesta, pero 'Abdominales' suele ser el nombre en BD. Ajustar si es necesario.
                'Crunch Abdominal' => ['Peso Corporal', 'Máquina'],
                'Elevación de Piernas' => ['Peso Corporal'],
                'Plancha (Plank)' => ['Peso Corporal'],
            ],
        ];

        // Mapeo de nombres de grupos musculares de la propuesta a la BD (si difieren)
        // Asumo que en BD están como 'Pecho', 'Espalda', etc. Si 'Core' es 'Abdominales', lo ajusto aquí.
        // Verificando GrupoMuscularSeeder... suele ser 'Pecho', 'Espalda', 'Piernas', 'Hombros', 'Bíceps', 'Tríceps', 'Abdominales'.
        // La propuesta agrupa Bíceps y Tríceps en "Brazos". Necesito desglosarlo o asignar ambos.
        // Voy a asignar "Brazos" a "Bíceps" por defecto y luego ajustar, o mejor, iterar con cuidado.

        // REVISIÓN: En GrupoMuscularSeeder tenemos: Pecho, Espalda, Piernas, Hombros, Bíceps, Tríceps, Abdominales, Antebrazo, Trapecio, Pantorrillas, Glúteos.
        // La propuesta agrupa. Voy a ajustar el array $data para que coincida con los nombres de la BD.

        $data_adjusted = [
            'Pecho' => [
                'Press de Banca Plano' => ['Barra', 'Mancuerna', 'Smith'],
                'Press Inclinado' => ['Barra', 'Mancuerna', 'Smith'],
                'Aperturas (Flyes)' => ['Mancuerna', 'Polea', 'Máquina'],
                'Flexiones (Push-ups)' => ['Peso Corporal', 'Máquina'],
                'Fondos en Paralelas (Dips)' => ['Peso Corporal', 'Máquina'],
            ],
            'Espalda' => [
                'Dominadas (Pull-ups)' => ['Peso Corporal', 'Máquina'],
                'Jalón al Pecho (Lat Pulldown)' => ['Polea', 'Máquina'],
                'Remo Horizontal' => ['Barra', 'Mancuerna', 'Polea', 'Máquina'],
                'Remo al Mentón' => ['Barra', 'Polea'],
                'Hiperextensiones' => ['Peso Corporal'],
            ],
            'Piernas' => [ // Asignaré a 'Piernas' general, aunque podría ser Cuádriceps/Femoral si existieran.
                'Sentadilla (Squat)' => ['Barra', 'Mancuerna', 'Peso Corporal', 'Smith'],
                'Prensa de Piernas' => ['Máquina'],
                'Zancadas (Lunges)' => ['Mancuerna', 'Barra', 'Peso Corporal'],
                'Peso Muerto (Deadlift)' => ['Barra', 'Mancuerna'],
                'Extensiones de Cuádriceps' => ['Máquina'],
                'Curl Femoral (Tumbado/Sentado)' => ['Máquina'],
                'Elevación de Talones (Gemelos)' => ['Máquina', 'Mancuerna', 'Peso Corporal'], // Podría ir a Pantorrillas si existe
            ],
            'Hombros' => [
                'Press Militar (Overhead Press)' => ['Barra', 'Mancuerna', 'Máquina', 'Smith'],
                'Elevaciones Laterales' => ['Mancuerna', 'Polea'],
                'Elevaciones Frontales' => ['Mancuerna', 'Polea'],
                'Pájaros (Posterior)' => ['Mancuerna', 'Máquina'],
            ],
            // Desglose de Brazos
            'Bíceps' => [
                'Curl de Bíceps' => ['Barra', 'Mancuerna', 'Polea'],
                'Curl Martillo' => ['Mancuerna', 'Polea'],
                'Curl Predicador' => ['Máquina', 'Mancuerna'],
            ],
            'Tríceps' => [
                'Extensiones de Tríceps' => ['Polea'],
                'Press Francés' => ['Mancuerna'],
                'Patada de Tríceps' => ['Mancuerna', 'Polea'],
            ],
            'Abdominales' => [
                'Crunch Abdominal' => ['Peso Corporal', 'Máquina'],
                'Elevación de Piernas' => ['Peso Corporal'],
                'Plancha (Plank)' => ['Peso Corporal'],
            ],
        ];

        foreach ($data_adjusted as $grupoNombre => $ejercicios) {
            $grupoId = $grupos[$grupoNombre] ?? null;

            if (! $grupoId) {
                // Fallback o log si no encuentra el grupo (ej: Piernas vs Cuádriceps)
                // Para este MVP, asumimos que coinciden con el Seeder de Grupos.
                continue;
            }

            foreach ($ejercicios as $nombreBase => $listaEquipos) {
                foreach ($listaEquipos as $equipoNombre) {
                    $equipoId = $equipos[$equipoNombre] ?? null;

                    if ($equipoId) {
                        // Construir nombre: "Sentadilla (Barra)"
                        $nombreFinal = "{$nombreBase} ({$equipoNombre})";

                        Ejercicio::create([
                            'nombre' => $nombreFinal,
                            'grupo_muscular_id' => $grupoId,
                            'equipo_id' => $equipoId,
                            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Default video
                            'estado' => 1,
                        ]);
                    }
                }
            }
        }
    }
}
