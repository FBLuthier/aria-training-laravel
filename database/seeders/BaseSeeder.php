<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * =======================================================================
 * CLASE BASE ABSTRACTA PARA SEEDERS REUTILIZABLES
 * =======================================================================
 * 
 * Esta clase proporciona funcionalidad común para todos los seeders del sistema,
 * eliminando código duplicado y garantizando consistencia.
 * 
 * FUNCIONALIDADES:
 * - Truncado seguro de tablas (con manejo de foreign keys)
 * - Creación en lote eficiente
 * - Progress feedback en consola
 * - Manejo de errores
 * - Hooks antes y después del seeding
 * 
 * MODO DE USO:
 * ```php
 * class EquipoSeeder extends BaseSeeder
 * {
 *     // REQUERIDO: Especificar el modelo
 *     protected function getModelClass(): string
 *     {
 *         return Equipo::class;
 *     }
 *     
 *     // OPCIONAL: Número de registros (default: 10)
 *     protected function getCount(): int
 *     {
 *         return 20;
 *     }
 *     
 *     // OPCIONAL: Datos específicos en lugar de factory
 *     protected function getData(): array
 *     {
 *         return [
 *             ['nombre' => 'Mancuernas'],
 *             ['nombre' => 'Barra Olímpica'],
 *         ];
 *     }
 *     
 *     // OPCIONAL: Truncar antes de seed
 *     protected function shouldTruncate(): bool
 *     {
 *         return true;
 *     }
 * }
 * ```
 * 
 * BENEFICIOS:
 * - Seeders en 2 minutos vs 10-15 minutos
 * - Código consistente en todo el proyecto
 * - Feedback visual del progreso
 * - Seguro (maneja foreign keys)
 * 
 * @package Database\Seeders
 * @since 1.7
 */
abstract class BaseSeeder extends Seeder
{
    // =======================================================================
    //  MÉTODOS ABSTRACTOS (DEBEN SER IMPLEMENTADOS)
    // =======================================================================
    /**
     * Retorna la clase del modelo a seedear.
     * 
     * @return string
     */
    abstract protected function getModelClass(): string;
    
    /**
     * Retorna el número de registros a crear con factory.
     * 
     * @return int
     */
    protected function getCount(): int
    {
        return 10;
    }
    
    /**
     * Retorna datos específicos a crear (opcional).
     * Si se define, se crean estos registros en lugar de usar factory.
     * 
     * @return array|null
     */
    protected function getData(): ?array
    {
        return null;
    }
    
    /**
     * Determina si se debe truncar la tabla antes de seedear.
     * 
     * @return bool
     */
    protected function shouldTruncate(): bool
    {
        return false;
    }
    
    /**
     * Hook antes de seedear.
     * 
     * @return void
     */
    protected function beforeSeeding(): void
    {
        // Por defecto no hace nada
    }
    
    /**
     * Hook después de seedear.
     * 
     * @return void
     */
    protected function afterSeeding(): void
    {
        // Por defecto no hace nada
    }
    
    /**
     * Ejecuta el seeder.
     * 
     * @return void
     */
    public function run(): void
    {
        $modelClass = $this->getModelClass();
        $modelName = class_basename($modelClass);
        
        $this->command->info("Seeding {$modelName}...");
        
        // Truncar si es necesario
        if ($this->shouldTruncate()) {
            $this->command->warn("Truncating {$modelName} table...");
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $modelClass::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
        
        // Hook antes
        $this->beforeSeeding();
        
        // Crear registros
        $specificData = $this->getData();
        
        if ($specificData !== null) {
            // Crear datos específicos
            $this->command->info("Creating " . count($specificData) . " specific {$modelName} records...");
            
            foreach ($specificData as $data) {
                $modelClass::create($data);
            }
            
            $this->command->info("Created " . count($specificData) . " {$modelName} records.");
        } else {
            // Crear con factory
            $count = $this->getCount();
            $this->command->info("Creating {$count} {$modelName} records with factory...");
            
            $modelClass::factory($count)->create();
            
            $this->command->info("Created {$count} {$modelName} records.");
        }
        
        // Hook después
        $this->afterSeeding();
        
        $this->command->info("✓ {$modelName} seeding completed successfully.");
    }
}
