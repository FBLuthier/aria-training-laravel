<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseCrudComponent;
use App\Models\User;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;

class GestionarUsuarios extends BaseCrudComponent
{
    // Propiedades del formulario
    public $usuario;
    public $correo;
    public $nombre_1;
    public $nombre_2;
    public $apellido_1;
    public $apellido_2;
    public $telefono;
    public $fecha_nacimiento;
    public $tipo_usuario_id;
    public $entrenador_id; // Para asignar entrenador a un atleta

    // Estado de edición
    public $isEditing = false;
    public $editingId = null;

    // Filtros
    public $filtroRol = null; // null = Todos, 1 = Admin, 2 = Entrenador, 3 = Atleta

    // Listas para selects
    public $tipos_usuario_list = [];
    public $entrenadores_list = [];

    public function mount()
    {
        $this->tipos_usuario_list = TipoUsuario::where('id', '!=', 1)->get();
        $this->cargarEntrenadores();
    }

    public function cargarEntrenadores()
    {
        // Cargar usuarios con rol de Entrenador (ID 2)
        $this->entrenadores_list = User::where('tipo_usuario_id', 2)->get();
    }

    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.admin.gestionar-usuarios';
    }

    #[Computed]
    public function items()
    {
        $query = User::query()->where('tipo_usuario_id', '!=', 1);

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre_1', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido_1', 'like', '%' . $this->search . '%')
                  ->orWhere('correo', 'like', '%' . $this->search . '%')
                  ->orWhere('usuario', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por Rol
        if ($this->filtroRol) {
            $query->where('tipo_usuario_id', $this->filtroRol);
        }

        // Ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection->value);

        // Soft Deletes
        if ($this->showingTrash) {
            $query->onlyTrashed();
        }

        return $query->paginate($this->getPerPage());
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $this->editingId = $id;
        $this->isEditing = true;

        $user = User::findOrFail($id);
        
        $this->usuario = $user->usuario;
        $this->correo = $user->correo;
        $this->nombre_1 = $user->nombre_1;
        $this->nombre_2 = $user->nombre_2;
        $this->apellido_1 = $user->apellido_1;
        $this->apellido_2 = $user->apellido_2;
        $this->telefono = $user->telefono;
        $this->fecha_nacimiento = $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : null;
        $this->tipo_usuario_id = $user->tipo_usuario_id;
        $this->entrenador_id = $user->entrenador_id;

        $this->showFormModal = true;
    }

    public function save(): void
    {
        // Reglas de validación
        $rules = [
            'usuario' => ['required', 'string', 'max:15', Rule::unique('usuarios')->ignore($this->editingId)],
            'correo' => ['required', 'email', 'max:45', Rule::unique('usuarios')->ignore($this->editingId)],
            'nombre_1' => 'required|string|max:15',
            'apellido_1' => 'required|string|max:15',
            'telefono' => 'required|string|max:15',
            'fecha_nacimiento' => 'required|date',
            'tipo_usuario_id' => 'required|exists:tipo_usuarios,id',
            'entrenador_id' => 'nullable|exists:usuarios,id',
        ];

        // Validación condicional: Si es atleta, se recomienda entrenador (aunque es nullable en BD)
        // Aquí podríamos forzarlo si quisiéramos, pero lo dejaremos opcional por ahora.

        $validatedData = $this->validate($rules);

        // Datos adicionales automáticos
        $validatedData['estado'] = 1; // Activo por defecto

        // Asegurar que entrenador_id sea null si no es atleta
        if ($validatedData['tipo_usuario_id'] != 3) {
            $validatedData['entrenador_id'] = null;
        }

        if (!$this->editingId) {
            // Creación: Asignar contraseña por defecto
            $validatedData['contrasena'] = Hash::make('password'); // Default password
        }

        // Guardar/Actualizar
        if ($this->editingId) {
            $user = User::find($this->editingId);
            
            // Capturar valores anteriores
            $oldValues = $user->toArray();
            
            $user->update($validatedData);
            
            // AUDITORÍA
            $this->auditUpdate($user, $oldValues);
            
            $this->dispatch('notify', message: 'Usuario actualizado correctamente.');
        } else {
            $user = User::create($validatedData);
            
            // AUDITORÍA
            $this->auditCreate($user);
            
            $this->dispatch('notify', message: 'Usuario creado correctamente. Contraseña temporal: "password"');
        }

        $this->closeFormModal();
        $this->cargarEntrenadores(); // Recargar lista por si se creó un nuevo entrenador
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetInputFields();
    }

    public function updatedShowFormModal($value): void
    {
        if (!$value) {
            $this->resetInputFields();
        }
    }

    public function resetInputFields(): void
    {
        $this->usuario = '';
        $this->correo = '';
        $this->nombre_1 = '';
        $this->nombre_2 = '';
        $this->apellido_1 = '';
        $this->apellido_2 = '';
        $this->telefono = '';
        $this->fecha_nacimiento = '';
        $this->tipo_usuario_id = ''; // Resetear rol
        $this->entrenador_id = null;
        $this->editingId = null;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function setFiltroRol($rolId)
    {
        if ($rolId == 1) return; // Seguridad: No permitir filtrar por admin
        $this->filtroRol = $rolId;
        $this->resetPage();
    }
}
