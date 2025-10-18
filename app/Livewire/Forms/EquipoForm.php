<?php

namespace App\Livewire\Forms;

use App\Models\Equipo;
use Illuminate\Validation\Rule as ValidationRule;

/**
 * =======================================================================
 * FORMULARIO PARA GESTIÓN DE EQUIPOS
 * =======================================================================
 * 
 * Este formulario maneja la creación y edición de equipos de gimnasio.
 * Extiende de BaseModelForm para heredar toda la lógica común de validación,
 * guardado y manejo de errores.
 * 
 * FUNCIONALIDADES HEREDADAS:
 * - save(): Guarda o actualiza el modelo
 * - validate(): Valida los datos del formulario
 * - reset(): Resetea el formulario a valores por defecto
 * - setModel(): Carga datos de un modelo existente
 * 
 * VALIDACIONES:
 * - Nombre: requerido, 3-45 caracteres, único en la tabla
 * 
 * USO EN COMPONENTE:
 * ```php
 * public EquipoForm $form;
 * 
 * public function create()
 * {
 *     $this->form->reset();
 *     $this->showFormModal = true;
 * }
 * 
 * public function edit($id)
 * {
 *     $equipo = Equipo::findOrFail($id);
 *     $this->form->setEquipo($equipo);
 *     $this->showFormModal = true;
 * }
 * 
 * public function save()
 * {
 *     $this->form->save();
 * }
 * ```
 * 
 * @package App\Livewire\Forms
 * @since 1.0
 */
class EquipoForm extends BaseModelForm
{
    // =======================================================================
    //  PROPIEDADES DEL FORMULARIO
    // =======================================================================

    /** @var string Nombre del equipo de gimnasio */
    public string $nombre = '';

    // =======================================================================
    //  IMPLEMENTACIÓN DE MÉTODOS ABSTRACTOS
    // =======================================================================

    /**
     * Retorna las reglas de validación.
     */
    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:45',
                ValidationRule::unique('equipos')->ignore($this->model?->id)
            ]
        ];
    }

    /**
     * Retorna la clase del modelo.
     */
    protected function getModelClass(): string
    {
        return Equipo::class;
    }

    /**
     * Rellena el formulario desde un modelo existente.
     */
    protected function fillFromModel($model): void
    {
        $this->nombre = $model->nombre;
    }

    /**
     * Retorna los datos listos para guardar.
     */
    protected function getModelData(): array
    {
        return [
            'nombre' => $this->nombre
        ];
    }

    // =======================================================================
    //  MÉTODOS PÚBLICOS DE CONVENIENCIA
    // =======================================================================

    /**
     * Método de conveniencia para compatibilidad con código existente.
     * 
     * @param Equipo $equipo
     * @return void
     */
    public function setEquipo(Equipo $equipo): void
    {
        $this->setModel($equipo);
    }
}