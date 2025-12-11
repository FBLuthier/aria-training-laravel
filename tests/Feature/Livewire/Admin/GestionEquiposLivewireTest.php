<?php

namespace Tests\Feature\Livewire\Admin;

use Livewire\Livewire;
use Tests\Feature\Livewire\BaseCrudTest;

/**
 * Tests para GestionarEquipos.
 *
 * Extiende de BaseCrudTest para heredar tests comunes.
 * Solo implementa configuración y tests específicos de Equipos.
 *
 * ANTES: 184 líneas con código repetitivo
 * AHORA: ~80 líneas (solo configuración y tests específicos)
 * REDUCCIÓN: ~56% menos código
 */
class GestionEquiposLivewireTest extends BaseCrudTest
{
    // =======================================================================
    //  CONFIGURACIÓN REQUERIDA
    // =======================================================================

    protected function getModelClass(): string
    {
        return \App\Models\Equipo::class;
    }

    protected function getComponentClass(): string
    {
        return \App\Livewire\Admin\GestionarEquipos::class;
    }

    protected function getRequiredFields(): array
    {
        return ['form.nombre' => 'Equipo Test'];
    }

    // NOTA: Los siguientes tests se heredan automáticamente de BaseCrudTest:
    // - test_componente_se_carga_para_administradores()
    // - test_componente_no_se_carga_para_usuarios_normales()
    // - test_administrador_puede_crear_registros()
    // - test_componente_valida_campo_requerido()
    // - test_no_crear_registros_duplicados()
    // - test_componente_puede_editar_registros()
    // - test_componente_puede_eliminar_registros()
    // - test_busqueda_filtra_correctamente()
    // - test_ordenamiento_funciona()

    // =======================================================================
    //  TESTS ESPECÍFICOS DE EQUIPOS
    // =======================================================================

    public function test_crear_equipo_con_caracteres_especiales(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test($this->getComponentClass())
            ->call('create')
            ->set('form.nombre', 'Equipo con números 123 y símbolos @#$%')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas($this->getTableName(), [
            'nombre' => 'Equipo con números 123 y símbolos @#$%',
        ]);
    }

    public function test_crear_equipo_con_caracteres_unicode(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test($this->getComponentClass())
            ->call('create')
            ->set('form.nombre', 'Equipo con ñ, á, é, í, ó, ú')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas($this->getTableName(), [
            'nombre' => 'Equipo con ñ, á, é, í, ó, ú',
        ]);
    }
}
