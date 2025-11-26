<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App; // <-- 1. AÑADIMOS ESTA LÍNEA

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- SEEDERS DE PRODUCCIÓN ---
        $this->call([
            TipoUsuarioSeeder::class,
        ]);

        // --- SEEDERS SOLO PARA DESARROLLO ---
        // 2. CAMBIAMOS '$this->app' POR 'App'
        if (App::environment('local')) {
            
            // Seeders de Datos Maestros (Orden Importante)
            $this->call([
                EquipoSeeder::class,
                GrupoMuscularSeeder::class,
                EjercicioSeeder::class,
            ]);

            // Crea nuestro usuario de prueba "Atleta"
            \App\Models\User::firstOrCreate(
                ['usuario' => 'atleta'],
                [
                    'nombre_1' => 'Usuario',
                    'apellido_1' => 'DePrueba',
                    'correo' => 'test@example.com',
                    'telefono' => '1234567890',
                    'fecha_nacimiento' => '2000-01-01',
                    'estado' => 1,
                    'tipo_usuario_id' => 3, // ID de Atleta
                    'password' => bcrypt('password'), // Asegurar password si se crea
                ]
            );

            // Crea nuestro usuario de prueba "Entrenador"
            \App\Models\User::firstOrCreate(
                ['usuario' => 'entrenador'],
                [
                    'nombre_1' => 'Usuario',
                    'apellido_1' => 'DePrueba',
                    'correo' => 'entrenador@example.com',
                    'telefono' => '1234567890',
                    'fecha_nacimiento' => '2000-01-01',
                    'estado' => 1,
                    'tipo_usuario_id' => 2, // ID de Entrenador
                    'password' => bcrypt('password'),
                ]
            );

            // Crea nuestro usuario de prueba "Administrador"
            \App\Models\User::firstOrCreate(
                ['usuario' => 'admin'],
                [
                    'nombre_1' => 'Admin',
                    'apellido_1' => 'Aria',
                    'correo' => 'admin@example.com',
                    'telefono' => '1234567890',
                    'fecha_nacimiento' => '2000-01-01',
                    'estado' => 1,
                    'tipo_usuario_id' => 1, // ID de Administrador
                    'password' => bcrypt('password'),
                ]
            );
            // Crea 25 equipos de prueba usando el factory
            // \App\Models\Equipo::factory(25)->create();
        }
    }
}