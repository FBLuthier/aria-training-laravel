<?php

namespace App\Livewire\Forms;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Data\UserData;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $userModel = null;

    public $usuario = '';

    public $correo = '';

    public $nombre_1 = '';

    public $nombre_2 = '';

    public $apellido_1 = '';

    public $apellido_2 = '';

    public $telefono = '';

    public $fecha_nacimiento = '';

    public $tipo_usuario_id = '';

    public $entrenador_id = null;

    public $photo;

    public function rules()
    {
        return [
            'usuario' => ['required', 'string', 'max:15', Rule::unique('usuarios')->ignore($this->userModel?->id)],
            'correo' => ['required', 'email', 'max:45', Rule::unique('usuarios')->ignore($this->userModel?->id)],
            'nombre_1' => 'required|string|max:15',
            'nombre_2' => 'nullable|string|max:15',
            'apellido_1' => 'required|string|max:15',
            'apellido_2' => 'nullable|string|max:15',
            'telefono' => 'required|string|max:15',
            'fecha_nacimiento' => 'required|date',
            'tipo_usuario_id' => ['required', Rule::enum(UserRole::class)],
            'tipo_usuario_id' => ['required', Rule::enum(UserRole::class)],
            'entrenador_id' => 'nullable|exists:usuarios,id',
            'photo' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function setUser(User $user)
    {
        $this->userModel = $user;

        $this->usuario = $user->usuario;
        $this->correo = $user->correo;
        $this->nombre_1 = $user->nombre_1;
        $this->nombre_2 = $user->nombre_2;
        $this->apellido_1 = $user->apellido_1;
        $this->apellido_2 = $user->apellido_2;
        $this->telefono = $user->telefono;
        $this->fecha_nacimiento = $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : null;
        $this->tipo_usuario_id = $user->tipo_usuario_id->value;
        $this->entrenador_id = $user->entrenador_id;
    }

    public function store()
    {
        $this->validate();

        $data = $this->all();

        // Ajustes de roles
        $this->adjustRoleData($data);

        // Crear DTO
        $userData = UserData::fromArray($data);

        // Delegar a la Action
        return app(CreateUserAction::class)->execute($userData);
    }

    public function update()
    {
        $this->validate();

        $data = $this->all();

        // Ajustes de roles
        $this->adjustRoleData($data);

        // Crear DTO
        $userData = UserData::fromArray($data);

        // Delegar a la Action
        return app(UpdateUserAction::class)->execute($this->userModel, $userData);
    }

    private function adjustRoleData(array &$data)
    {
        // Asegurar que entrenador_id sea null si no es atleta
        if ($data['tipo_usuario_id'] != UserRole::Atleta->value) {
            $data['entrenador_id'] = null;
        }

        // Lógica para Entrenadores: Forzar rol Atleta y asignarse a sí mismo
        if (auth()->user()->esEntrenador()) {
            $data['tipo_usuario_id'] = UserRole::Atleta->value;
            $data['entrenador_id'] = auth()->id();
        }
    }
}
