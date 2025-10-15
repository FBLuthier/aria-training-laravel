<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario' => fake()->unique()->userName(),
            'nombre_1' => fake()->firstName(),
            'nombre_2' => null,
            'apellido_1' => fake()->lastName(),
            'apellido_2' => null,
            'correo' => fake()->unique()->safeEmail(),
            'contrasena' => static::$password ??= Hash::make('password'),
            'telefono' => fake()->phoneNumber(),
            'fecha_nacimiento' => fake()->date(),
            'estado' => 1,
            'tipo_usuario_id' => 3, // Por defecto, crea Atletas
            'remember_token' => Str::random(10), // <-- AÑADE ESTA LÍNEA DE NUEVO

            
        ];
    }
}