<?php

namespace App\Livewire\Athlete;

use App\Models\Rutina;
use App\Models\RutinaDia;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * =======================================================================
 * COMPONENTE: ATHLETE DASHBOARD
 * =======================================================================
 *
 * Dashboard principal del atleta con calendario compacto.
 *
 * CARACTERÍSTICAS:
 * - Calendario mensual compacto (solo día + nombre entrenamiento)
 * - Navegación mes anterior/siguiente
 * - Click en día navega a la sesión de entrenamiento
 * - Resaltado del día actual
 * - Tarjeta de "Entrenamiento de Hoy" si hay uno programado
 *
 * @since 1.7
 */
#[Layout('layouts.app')]
class AthleteDashboard extends Component
{
    /** Mes actual del calendario */
    public int $currentMonth;

    /** Año actual del calendario */
    public int $currentYear;

    /** Rutina activa del atleta (puede ser null) */
    public ?Rutina $rutina = null;

    /**
     * Inicializa el componente.
     *
     * Busca la rutina activa del atleta y configura el calendario
     * para mostrar el mes actual.
     */
    public function mount(): void
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        // Buscar la rutina activa del atleta logueado
        $this->rutina = Rutina::where('atleta_id', auth()->id())
            ->where('estado', 1) // Estado activo
            ->with('dias.rutinaEjercicios.ejercicio')
            ->first();
    }

    /**
     * Días programados agrupados por fecha.
     *
     * Retorna una colección de días que tienen fecha asignada,
     * agrupados por la fecha para facilitar el renderizado del calendario.
     *
     * @return Collection<string, Collection<RutinaDia>>
     */
    #[Computed]
    public function diasProgramados(): Collection
    {
        if (! $this->rutina) {
            return collect();
        }

        return $this->rutina->dias
            ->whereNotNull('fecha')
            ->groupBy(fn ($dia) => $dia->fecha->format('Y-m-d'));
    }

    /**
     * Entrenamiento programado para hoy.
     *
     * @return RutinaDia|null
     */
    #[Computed]
    public function entrenamientoHoy(): ?RutinaDia
    {
        if (! $this->rutina) {
            return null;
        }

        $hoy = now()->format('Y-m-d');

        return $this->rutina->dias
            ->whereNotNull('fecha')
            ->first(fn ($dia) => $dia->fecha->format('Y-m-d') === $hoy);
    }

    /**
     * Cambia el mes del calendario.
     *
     * @param  int  $increment  Positivo para avanzar, negativo para retroceder
     */
    public function changeMonth(int $increment): void
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)
            ->addMonths($increment);

        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    /**
     * Renderiza el componente.
     */
    public function render()
    {
        return view('livewire.athlete.athlete-dashboard');
    }
}
