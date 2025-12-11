<?php

namespace App\Livewire\Forms;

use App\Models\Rutina;
use Livewire\Attributes\Rule;
use Livewire\Form;

class RutinaForm extends Form
{
    public ?Rutina $model = null;

    #[Rule('required|min:3|max:45')]
    public $nombre = '';

    #[Rule('nullable|string')]
    public $descripcion = '';

    #[Rule('required|exists:usuarios,id')]
    public $atleta_id = '';

    public function setModel(Rutina $rutina)
    {
        $this->model = $rutina;
        $this->nombre = $rutina->nombre;
        $this->descripcion = $rutina->descripcion;
        $this->atleta_id = $rutina->atleta_id;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'atleta_id' => $this->atleta_id,
            'estado' => 1, // Activa por defecto
        ];

        if ($this->model && $this->model->exists) {
            $this->model->update($data);

            return 'Rutina actualizada correctamente';
        } else {
            $this->model = Rutina::create($data);

            return 'Rutina creada correctamente';
        }
    }
}
