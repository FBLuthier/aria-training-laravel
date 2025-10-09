<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama a nuestros seeders de catálogo
        $this->call([
            TipoUsuarioSeeder::class,
        ]);

        // Crea nuestro usuario de prueba
        \App\Models\User::factory()->create([
            'usuario' => 'tester',
            'nombre_1' => 'Usuario',
            'apellido_1' => 'DePrueba',
            'correo' => 'test@example.com',
            'telefono' => '1234567890',
            'fecha_nacimiento' => '2000-01-01',
            'estado' => 1,
            'id_tipo_usuario' => 3, // ID de Atleta
        ]);
    }
}
