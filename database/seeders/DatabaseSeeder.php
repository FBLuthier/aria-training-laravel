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
            
            // Crea nuestro usuario de prueba "Atleta"
            \App\Models\User::factory()->create([
                'usuario' => 'atleta',
                'nombre_1' => 'Usuario',
                'apellido_1' => 'DePrueba',
                'correo' => 'test@example.com',
                'telefono' => '1234567890',
                'fecha_nacimiento' => '2000-01-01',
                'estado' => 1,
                'id_tipo_usuario' => 3, // ID de Atleta
            ]);

            // Crea nuestro usuario de prueba "Entrenador"
            \App\Models\User::factory()->create([
                'usuario' => 'entrenador',
                'nombre_1' => 'Usuario',
                'apellido_1' => 'DePrueba',
                'correo' => 'entrenador@example.com',
                'telefono' => '1234567890',
                'fecha_nacimiento' => '2000-01-01',
                'estado' => 1,
                'id_tipo_usuario' => 2, // ID de Entrenador
            ]);

            // Crea nuestro usuario de prueba "Administrador"
            \App\Models\User::factory()->create([
                'usuario' => 'admin',
                'nombre_1' => 'Admin',
                'apellido_1' => 'Aria',
                'correo' => 'admin@example.com',
                'telefono' => '1234567890',
                'fecha_nacimiento' => '2000-01-01',
                'estado' => 1,
                'id_tipo_usuario' => 1, // ID de Administrador
            ]);
            // Crea 25 equipos de prueba usando el factory
            \App\Models\Equipo::factory(25)->create();
        }
    }
}