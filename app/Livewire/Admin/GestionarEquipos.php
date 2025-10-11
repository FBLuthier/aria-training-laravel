<?php

namespace App\Livewire\Admin;

use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GestionarEquipos extends Component
{
    use WithPagination;

    public string $search = '';
    
    // INICIO: Propiedades para el ordenamiento
    public string $sortField = 'id'; // Columna por la que se ordena por defecto
    public string $sortDirection = 'asc'; // Dirección por defecto
    // FIN: Propiedades para el ordenamiento

    // INICIO: NUEVO MÉTODO PARA CAMBIAR DE VISTA
    public function toggleTrash(): void
    {
        $this->showingTrash = !$this->showingTrash;
    }
    // FIN: NUEVO MÉTODO

    // INICIO: Método para cambiar el orden
    public function sortBy(string $field): void
    {
        // Si se hace clic en la misma columna, invierte la dirección.
        // Si no, establece la dirección a 'asc'.
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
    // FIN: Método para cambiar el orden

    public function render()
    {
        $equipos = Equipo::query()
            ->when($this->search, function ($query) {
                return $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            // INICIO: Lógica de ordenamiento actualizada
            ->orderBy($this->sortField, $this->sortDirection)
            // FIN: Lógica de ordenamiento actualizada
            ->paginate(10);

        return view('livewire.admin.gestionar-equipos', [
            'equipos' => $equipos,
        ]);
    }


}