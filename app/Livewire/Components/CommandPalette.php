<?php

namespace App\Livewire\Components;

use App\Models\User;
use Livewire\Component;

class CommandPalette extends Component
{
    public $search = '';
    public $isOpen = false;
    public $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];
            return;
        }

        $this->results = [];

        // 1. Páginas del Sistema
        $pages = [
            ['title' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home'],
            ['title' => 'Gestión de Usuarios', 'route' => 'admin.usuarios.index', 'icon' => 'users'],
            ['title' => 'Gestión de Rutinas', 'route' => 'admin.rutinas', 'icon' => 'calendar'],
            ['title' => 'Gestión de Ejercicios', 'route' => 'admin.ejercicios', 'icon' => 'dumbbell'],
            ['title' => 'Gestión de Equipos', 'route' => 'admin.equipos.index', 'icon' => 'cube'],
            ['title' => 'Auditoría', 'route' => 'admin.auditoria.index', 'icon' => 'clipboard-list'],
        ];

        foreach ($pages as $page) {
            if (stripos($page['title'], $this->search) !== false) {
                $this->results[] = [
                    'type' => 'Page',
                    'title' => $page['title'],
                    'url' => route($page['route']),
                    'icon' => $page['icon']
                ];
            }
        }

        // 2. Usuarios (Solo si es admin/entrenador)
        if (auth()->user()->esAdmin() || auth()->user()->esEntrenador()) {
            $users = User::where('nombre_1', 'like', "%{$this->search}%")
                ->orWhere('apellido_1', 'like', "%{$this->search}%")
                ->orWhere('usuario', 'like', "%{$this->search}%")
                ->limit(5)
                ->get();

            foreach ($users as $user) {
                $this->results[] = [
                    'type' => 'User',
                    'title' => "{$user->nombre_1} {$user->apellido_1} ({$user->usuario})",
                    // En el futuro podríamos redirigir a un perfil específico, 
                    // por ahora vamos a la tabla filtrada (simulado) o impersonate
                    'url' => route('admin.usuarios.index', ['search' => $user->usuario]), 
                    'icon' => 'user'
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.components.command-palette');
    }
}
