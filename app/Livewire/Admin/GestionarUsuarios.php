<?php

namespace App\Livewire\Admin;

use App\Enums\SortDirection;
use App\Enums\UserRole;
use App\Livewire\Forms\UserForm;
use App\Models\TipoUsuario;
use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse; // Assuming Livewire\Component is the new base class

class GestionarUsuarios extends Component
{
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;

    // Form Object
    public UserForm $form;

    // Estado de edición
    public $isEditing = false;

    public $editingId = null;
    public $deletingId = null;
    public $restoringId = null;
    public $forceDeletingId = null;
    public $resettingPasswordId = null;
    public $newPassword = '';
    
    // Papelera
    public $showingTrash = false;

    // Búsqueda y Ordenamiento
    public $search = '';
    public $sortField = 'id';
    public SortDirection $sortDirection = SortDirection::DESC;
    public $perPage = 10;

    // Filtros
    public $filtroRol = null; // null = Todos, 1 = Admin, 2 = Entrenador, 3 = Atleta

    // Listas para selects
    public $tipos_usuario_list = [];

    public $entrenadores_list = [];

    // Assuming these are needed for modal management, based on the diff's `openModal` and `closeModal`
    public $showFormModal = false;
    public $showDeleteModal = false;

    public function mount(UserService $userService)
    {
        $this->userService = $userService;
        $this->tipos_usuario_list = TipoUsuario::where('id', '!=', UserRole::Admin->value)->get();

        if (auth()->user()->esEntrenador()) {
            $this->filtroRol = UserRole::Atleta->value; // Forzar filtro a Atletas
        }

        $this->cargarEntrenadores();
    }

    protected UserService $userService;

    public function cargarEntrenadores()
    {
        // Cargar usuarios con rol de Entrenador (ID 2)
        $this->entrenadores_list = User::where('tipo_usuario_id', UserRole::Entrenador->value)->get();
    }

    // Removed getModelClass() as it's not in the diff's context for the new structure

    public function render()
    {
        $this->authorize('viewAny', User::class);

        return view('livewire.admin.gestionar-usuarios');
    }

    #[Computed]
    public function items()
    {
        return User::query()
            ->withoutAdmins()
            ->visibleTo(auth()->user())
            ->byRole($this->filtroRol)
            ->search($this->search)
            ->trash($this->showingTrash) // Assuming $this->showingTrash and $this->search still exist
            ->sortBy($this->sortField, $this->sortDirection->value) // Assuming these still exist
            ->paginate($this->getPerPage()); // Assuming getPerPage() still exists
    }

    public function create(): void
    {
        $this->authorize('create', User::class); // Added authorization
        $this->resetInputFields();
        $this->openModal(); // Changed to openModal
    }

    public function edit($id) // Changed signature and logic
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $this->form->setUser($user);
        $this->isEditing = true;
        $this->editingId = $id; // Keep editingId for consistency if needed elsewhere
        $this->openModal();
    }

    public function save() // Changed signature and logic
    {
        if ($this->isEditing) {
            $this->authorize('update', $this->form->userModel);
            $updatedUser = $this->form->update();

            // AUDITORÍA AUTOMÁTICA POR OBSERVER

            $this->dispatch('notify', message: __('users.messages.updated'));
        } else {
            $this->authorize('create', User::class);
            $newUser = $this->form->store();

            // AUDITORÍA AUTOMÁTICA POR OBSERVER

            $this->dispatch('notify', message: __('users.messages.created'));
        }

        $this->closeModal();
        $this->cargarEntrenadores(); // Recargar lista por si se creó un nuevo entrenador
    }

    public function delete($id)
    {
        $this->deletingId = $id;
    }

    public function performDelete()
    {
        $user = User::findOrFail($this->deletingId);
        $this->authorize('delete', $user);

        $this->userService->delete($user);
        $this->deletingId = null;
        $this->dispatch('notify', message: __('users.messages.deleted'));
    }

    public function restore($id)
    {
        $this->restoringId = $id;
    }

    public function performRestore()
    {
        $user = User::withTrashed()->findOrFail($this->restoringId);
        $this->authorize('restore', $user);
        
        $this->userService->restore($user);
        $this->restoringId = null;
        $this->dispatch('notify', message: __('users.messages.restored'));
    }

    public function forceDelete($id)
    {
        $this->forceDeletingId = $id;
    }

    public function performForceDelete()
    {
        $user = User::withTrashed()->findOrFail($this->forceDeletingId);
        $this->authorize('forceDelete', $user);
        
        $this->userService->forceDelete($user);
        $this->forceDeletingId = null;
        $this->dispatch('notify', message: __('users.messages.force_deleted'));
    }

    public function confirmPasswordReset($id)
    {
        $this->resettingPasswordId = $id;
        $this->newPassword = '';
    }

    public function generatePassword()
    {
        $this->newPassword = $this->userService->generateSecurePassword(10);
    }

    public function performPasswordReset()
    {
        $this->validate([
            'newPassword' => 'required|min:8',
        ]);

        $user = User::findOrFail($this->resettingPasswordId);
        $this->userService->resetPassword($user, $this->newPassword);

        $this->resettingPasswordId = null;
        $this->newPassword = '';
        $this->dispatch('notify', message: 'Contraseña restablecida correctamente.');
    }



    // Assuming these methods are part of the new modal management
    public function openModal()
    {
        $this->showFormModal = true;
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->resetInputFields();
    }

    public function updatedShowFormModal($value): void
    {
        if (! $value) {
            $this->resetInputFields();
        }
    }

    public function resetInputFields(): void
    {
        $this->form->reset();

        if (auth()->user()->esEntrenador()) {
            $this->form->tipo_usuario_id = UserRole::Atleta->value; // Entrenadores solo crean atletas
        }

        $this->editingId = null;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function setFiltroRol($rolId)
    {
        if ($rolId == UserRole::Admin->value) {
            return;
        } // Seguridad: No permitir filtrar por admin
        $this->filtroRol = $rolId;
        $this->resetPage();
    }

    public function toggleTrash()
    {
        $this->showingTrash = !$this->showingTrash;
        $this->resetPage();
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === SortDirection::ASC 
                ? SortDirection::DESC 
                : SortDirection::ASC;
        } else {
            $this->sortField = $field;
            $this->sortDirection = SortDirection::ASC;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
