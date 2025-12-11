<?php

namespace Database\Factories;

use App\Models\Equipo;
use Database\Factories\Traits\HasStandardFields;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * =======================================================================
 * FACTORY PARA MODELO EQUIPO
 * =======================================================================
 *
 * Factory para generar datos de prueba de equipos de gimnasio.
 * Usado principalmente en testing y seeding.
 *
 * FUNCIONALIDADES:
 * - Genera nombres únicos de equipos
 * - Puede usar HasStandardFields trait para timestamps
 * - Soporte para estados (activo/eliminado)
 *
 * USO EN TESTS:
 * ```php
 * // Crear un equipo
 * $equipo = Equipo::factory()->create();
 *
 * // Crear múltiples equipos
 * $equipos = Equipo::factory()->count(10)->create();
 *
 * // Crear con datos específicos
 * $equipo = Equipo::factory()->create([
 *     'nombre' => 'Mancuernas 10kg'
 * ]);
 *
 * // Crear sin persistir
 * $equipo = Equipo::factory()->make();
 * ```
 *
 * USO EN SEEDERS:
 * ```php
 * public function run(): void
 * {
 *     Equipo::factory()->count(20)->create();
 * }
 * ```
 *
 * @extends Factory<Equipo>
 *
 * @since 1.0
 */
class EquipoFactory extends Factory
{
    // =======================================================================
    //  CONFIGURACIÓN
    // =======================================================================

    /** @var string Clase del modelo asociado */
    protected $model = Equipo::class;

    // =======================================================================
    //  DEFINICIÓN DE DATOS
    // =======================================================================

    /**
     * Define el estado por defecto del modelo.
     *
     * Genera nombres de equipos usando palabras únicas de Faker.
     * El método unique() garantiza que no haya duplicados.
     *
     * EJEMPLOS DE NOMBRES GENERADOS:
     * - "consequatur"
     * - "perspiciatis"
     * - "reprehenderit"
     *
     * NOTA: Para nombres más realistas, considera usar un array
     * de nombres de equipos reales:
     * ```php
     * $equipos = ['Mancuernas', 'Barra Olímpica', 'Banco Plano', ...];
     * return ['nombre' => fake()->unique()->randomElement($equipos)];
     * ```
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera nombre único de equipo
            'nombre' => fake()->unique()->word(),
        ];
    }
}
