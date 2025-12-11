<?php

namespace Tests\Feature\Admin;

use App\Models\Equipo;
use App\Models\TipoUsuario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GestionEquiposTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Configuración inicial para todas las pruebas
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Crear tipos de usuario primero
        TipoUsuario::create(['id' => 1, 'rol' => 'Administrador']);
        TipoUsuario::create(['id' => 2, 'rol' => 'Entrenador']);
        TipoUsuario::create(['id' => 3, 'rol' => 'Atleta']);
    }

    // =======================================================================
    //  PRUEBAS DE AUTORIZACIÓN BÁSICA
    // =======================================================================

    public function test_administrador_puede_ver_gestion_equipos(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $response = $this->actingAs($admin)->get('/admin/equipos');

        $response->assertStatus(200);
    }

    public function test_usuario_normal_no_puede_ver_gestion_equipos(): void
    {
        $atleta = User::factory()->create(['tipo_usuario_id' => 3]);

        $response = $this->actingAs($atleta)->get('/admin/equipos');

        $response->assertRedirect('/dashboard');
    }

    public function test_entrenador_no_puede_ver_gestion_equipos(): void
    {
        $entrenador = User::factory()->create(['tipo_usuario_id' => 2]);

        $response = $this->actingAs($entrenador)->get('/admin/equipos');

        $response->assertRedirect('/dashboard');
    }

    public function test_usuario_no_autenticado_es_redirigido_a_login(): void
    {
        $response = $this->get('/admin/equipos');

        $response->assertRedirect('/login');
    }

    // =======================================================================
    //  PRUEBAS DE CREACIÓN DE EQUIPOS
    // =======================================================================

    public function test_administrador_puede_crear_equipos(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => 'Mancuernas 10kg',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipos', ['nombre' => 'Mancuernas 10kg']);
    }

    public function test_no_crear_equipo_sin_nombre(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => '',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseMissing('equipos', ['nombre' => '']);
    }

    public function test_no_crear_equipo_con_nombre_vacio(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => '   ',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseMissing('equipos', ['nombre' => '   ']);
    }

    // =======================================================================
    //  PRUEBAS DE DUPLICADOS Y VALIDACIÓN
    // =======================================================================

    public function test_no_crear_equipos_con_nombres_duplicados(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        // Crear primer equipo
        $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => 'Mancuernas 10kg',
        ]);

        // Intentar crear duplicado
        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => 'Mancuernas 10kg',
        ]);

        $response->assertSessionHasErrors(['nombre']);
    }

    public function test_crear_equipo_con_caracteres_especiales(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => 'Equipo con números 123 y símbolos @#$%',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipos', [
            'nombre' => 'Equipo con números 123 y símbolos @#$%',
        ]);
    }

    // =======================================================================
    //  PRUEBAS DE EDICIÓN
    // =======================================================================

    public function test_editar_equipo_cambia_nombre_correctamente(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);
        $equipo = Equipo::factory()->create(['nombre' => 'Equipo viejo']);

        $response = $this->actingAs($admin)->put("/admin/equipos/{$equipo->id}", [
            'nombre' => 'Equipo nuevo',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipos', ['nombre' => 'Equipo nuevo']);
        $this->assertDatabaseMissing('equipos', ['nombre' => 'Equipo viejo']);
    }

    // =======================================================================
    //  PRUEBAS DE ELIMINACIÓN (SOFT DELETE)
    // =======================================================================

    public function test_eliminar_equipo_lo_mueve_a_papelera(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);
        $equipo = Equipo::factory()->create(['nombre' => 'Equipo a eliminar']);

        $response = $this->actingAs($admin)->delete("/admin/equipos/{$equipo->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('equipos', ['nombre' => 'Equipo a eliminar']);
    }

    // =======================================================================
    //  PRUEBAS DE CASOS EXTREMOS
    // =======================================================================

    public function test_crear_equipo_con_caracteres_unicode(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => 'Equipo con ñ, á, é, í, ó, ú',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipos', [
            'nombre' => 'Equipo con ñ, á, é, í, ó, ú',
        ]);
    }

    public function test_no_crear_equipo_con_nombre_demasiado_largo(): void
    {
        $admin = User::factory()->create(['tipo_usuario_id' => 1]);

        $nombreLargo = str_repeat('a', 46); // Más de 45 caracteres

        $response = $this->actingAs($admin)->post('/admin/equipos', [
            'nombre' => $nombreLargo,
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseMissing('equipos', ['nombre' => $nombreLargo]);
    }
}
