<?php

namespace App\Livewire\Forms;

use App\Models\Equipo;
use Livewire\Attributes\Rule;
use Livewire\Form;
use Illuminate\Validation\Rule as ValidationRule;

class EquipoForm extends Form
{
    public ?Equipo $equipo = null;
    
    #[Rule('required|string|min:3|max:255')]
    public string $nombre = '';
    
    /**
     * Carga un modelo de Equipo existente en el formulario.
     */
    public function setEquipo(Equipo $equipo): void
    {
        $this->equipo = $equipo;
        $this->nombre = $equipo->nombre;
    }

    /**
     * Guarda el formulario, ya sea creando o actualizando el equipo.
     */
    public function save(): string
    {
        // Aplicar regla de validación única dinámicamente
        $this->validate([
            'nombre' => [
                'required', 'string', 'min:3', 'max:255',
                ValidationRule::unique('equipos')->ignore($this->equipo?->id)
            ]
        ]);

        if (isset($this->equipo)) {
            // Actualizar
            $this->equipo->update(['nombre' => $this->nombre]);
            return 'Equipo actualizado exitosamente.';
        } else {
            // Crear
            $this->equipo = Equipo::create(['nombre' => $this->nombre]);
            return 'Equipo creado exitosamente.';
        }
    }

     /**
     * Resetea el formulario a su estado inicial.
     */
// INICIO BLOQUE (Método reset Corregido)
    public function reset(...$properties): void
    {
        // 1. Ejecutamos nuestra lógica personalizada primero
        $this->equipo = null;
        
        // 2. Llamamos al método reset() original del padre
        //    para que haga su trabajo estándar (limpiar $nombre, etc.)
        parent::reset(...$properties);
    }
// FINAL BLOQUE (Método reset Corregido)
}