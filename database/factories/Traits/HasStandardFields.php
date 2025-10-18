<?php

namespace Database\Factories\Traits;

/**
 * Trait para campos estándar en Factories.
 * 
 * Proporciona definiciones consistentes para campos comunes
 * que aparecen en la mayoría de modelos.
 * 
 * MODO DE USO:
 * ```php
 * class EquipoFactory extends Factory
 * {
 *     use HasStandardFields;
 *     
 *     protected function definition(): array
 *     {
 *         return [
 *             'nombre' => $this->uniqueName(),
 *             ...$this->standardTimestamps(),
 *         ];
 *     }
 * }
 * ```
 */
trait HasStandardFields
{
    /**
     * Genera un nombre único.
     * 
     * @param int $words
     * @return string
     */
    protected function uniqueName(int $words = 2): string
    {
        return fake()->unique()->words($words, true);
    }
    
    /**
     * Genera una descripción.
     * 
     * @param int $sentences
     * @return string
     */
    protected function description(int $sentences = 3): string
    {
        return fake()->sentence($sentences);
    }
    
    /**
     * Genera timestamps estándar.
     * 
     * @return array
     */
    protected function standardTimestamps(): array
    {
        $createdAt = fake()->dateTimeBetween('-1 year', 'now');
        
        return [
            'created_at' => $createdAt,
            'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
        ];
    }
    
    /**
     * Estado activo (no eliminado).
     * 
     * @return array
     */
    protected function active(): array
    {
        return [
            'deleted_at' => null,
        ];
    }
    
    /**
     * Estado eliminado (soft deleted).
     * 
     * @return array
     */
    protected function deleted(): array
    {
        return [
            'deleted_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
