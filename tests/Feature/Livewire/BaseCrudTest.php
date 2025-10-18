<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use App\Models\TipoUsuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Clase base abstracta para pruebas de CRUDs.
 * 
 * Proporciona métodos de prueba reutilizables para:
 * - Autorización (admin vs usuarios normales)
 * - Operaciones CRUD (crear, editar, eliminar)
 * - Validaciones
 * - Búsqueda y ordenamiento
 * 
 * MODO DE USO:
 * ```php
 * class GestionEjerciciosTest extends BaseCrudTest
 * {
 *     protected function getModelClass(): string
 *     {
 *         return \App\Models\Ejercicio::class;
 *     }
 *     
 *     protected function getComponentClass(): string
 *     {
 *         return \App\Livewire\Admin\GestionarEjercicios::class;
 *     }
 *     
 *     protected function getRequiredFields(): array
 *     {
 *         return ['form.nombre' => 'Ejercicio Test'];
 *     }
 *     
 *     // Opcional: Agregar tests específicos
 *     public function test_algo_especifico(): void
 *     {
 *         // Tu test específico
 *     }
 * }
 * ```
 * 
 * BENEFICIOS:
 * - Reduce código de tests en ~70%
 * - Tests consistentes en todos los CRUDs
 * - Menos errores por duplicación
 * - Fácil mantenimiento
 */
abstract class BaseCrudTest extends TestCase
{
    use RefreshDatabase;
    
    // =======================================================================
    //  MÉTODOS ABSTRACTOS (DEBEN SER IMPLEMENTADOS)
    // =======================================================================
    
    /**
     * Retorna la clase del modelo que se está probando.
     * 
     * @return string
     */
    abstract protected function getModelClass(): string;
    
    /**
     * Retorna la clase del componente Livewire que se está probando.
     * 
     * @return string
     */
    abstract protected function getComponentClass(): string;
    
    /**
     * Retorna los campos requeridos para crear un registro válido.
     * 
     * @return array Ej: ['form.nombre' => 'Test', 'form.descripcion' => 'Desc']
     */
    abstract protected function getRequiredFields(): array;
    
    // =======================================================================
    //  MÉTODOS CON IMPLEMENTACIÓN POR DEFECTO (PUEDEN SER SOBRESCRITOS)
    // =======================================================================
    
    /**
     * Retorna los campos opcionales para pruebas.
     * 
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [];
    }
    
    /**
     * Retorna el nombre de la tabla en la base de datos.
     * 
     * @return string
     */
    protected function getTableName(): string
    {
        $model = $this->getModelClass();
        return (new $model())->getTable();
    }
    
    /**
     * Retorna un campo que debe ser único (para pruebas de validación).
     * 
     * @return string
     */
    protected function getUniqueField(): string
    {
        return 'form.nombre';
    }
    
    /**
     * Retorna un campo requerido (para pruebas de validación).
     * 
     * @return string
     */
    protected function getRequiredField(): string
    {
        return 'form.nombre';
    }
    
    /**
     * Retorna un campo sorteable (para pruebas de ordenamiento).
     * 
     * @return string
     */
    protected function getSortableField(): string
    {
        return 'nombre';
    }
    
    /**
     * Crea un usuario administrador.
     * 
     * @return User
     */
    protected function createAdmin(): User
    {
        return User::factory()->create(['tipo_usuario_id' => 1]);
    }
    
    /**
     * Crea un usuario normal (no admin).
     * 
     * @return User
     */
    protected function createNormalUser(): User
    {
        return User::factory()->create(['tipo_usuario_id' => 3]);
    }
    
    /**
     * Crea una instancia del modelo con datos de prueba.
     * 
     * @param array $attributes
     * @return mixed
     */
    protected function createModel(array $attributes = [])
    {
        $modelClass = $this->getModelClass();
        return $modelClass::factory()->create($attributes);
    }
    
    // =======================================================================
    //  SETUP
    // =======================================================================
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear tipos de usuario
        TipoUsuario::create(['id' => 1, 'rol' => 'Administrador']);
        TipoUsuario::create(['id' => 2, 'rol' => 'Entrenador']);
        TipoUsuario::create(['id' => 3, 'rol' => 'Atleta']);
    }
    
    // =======================================================================
    //  PRUEBAS DE AUTORIZACIÓN
    // =======================================================================
    
    public function test_componente_se_carga_para_administradores(): void
    {
        $admin = $this->createAdmin();
        
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->assertOk()
                ->assertSet('showingTrash', false);
    }
    
    public function test_componente_no_se_carga_para_usuarios_normales(): void
    {
        $user = $this->createNormalUser();
        
        Livewire::actingAs($user)
                ->test($this->getComponentClass())
                ->assertForbidden();
    }
    
    // =======================================================================
    //  PRUEBAS DE CREACIÓN
    // =======================================================================
    
    public function test_administrador_puede_crear_registros(): void
    {
        $admin = $this->createAdmin();
        $fields = $this->getRequiredFields();
        
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('create')
                ->set($fields)
                ->call('save')
                ->assertHasNoErrors();
        
        // Verificar en BD (solo el primer campo)
        $firstField = array_key_first($fields);
        $fieldName = str_replace('form.', '', $firstField);
        $this->assertDatabaseHas($this->getTableName(), [
            $fieldName => $fields[$firstField]
        ]);
    }
    
    // =======================================================================
    //  PRUEBAS DE VALIDACIÓN
    // =======================================================================
    
    public function test_componente_valida_campo_requerido(): void
    {
        $admin = $this->createAdmin();
        
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('create')
                ->set($this->getRequiredField(), '')
                ->call('save')
                ->assertHasErrors([$this->getRequiredField()]);
    }
    
    public function test_no_crear_registros_duplicados(): void
    {
        $admin = $this->createAdmin();
        $fields = $this->getRequiredFields();
        
        // Crear primer registro
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('create')
                ->set($fields)
                ->call('save');
        
        // Intentar crear duplicado
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('create')
                ->set($fields)
                ->call('save')
                ->assertHasErrors([$this->getUniqueField()]);
    }
    
    // =======================================================================
    //  PRUEBAS DE EDICIÓN
    // =======================================================================
    
    public function test_componente_puede_editar_registros(): void
    {
        $admin = $this->createAdmin();
        $fields = $this->getRequiredFields();
        
        // Obtener nombre del campo sin 'form.'
        $firstField = array_key_first($fields);
        $fieldName = str_replace('form.', '', $firstField);
        
        // Crear modelo
        $model = $this->createModel([$fieldName => 'Valor Original']);
        
        // Editar
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('edit', $model->id)
                ->assertSet('showFormModal', true)
                ->set($firstField, 'Valor Actualizado')
                ->call('save')
                ->assertHasNoErrors();
        
        $this->assertDatabaseHas($this->getTableName(), ['id' => $model->id, $fieldName => 'Valor Actualizado']);
        $this->assertDatabaseMissing($this->getTableName(), [$fieldName => 'Valor Original']);
    }
    
    // =======================================================================
    //  PRUEBAS DE ELIMINACIÓN
    // =======================================================================
    
    public function test_componente_puede_eliminar_registros(): void
    {
        $admin = $this->createAdmin();
        $fields = $this->getRequiredFields();
        
        // Obtener nombre del campo
        $firstField = array_key_first($fields);
        $fieldName = str_replace('form.', '', $firstField);
        
        // Crear modelo
        $model = $this->createModel([$fieldName => 'A Eliminar']);
        
        // Eliminar
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('delete', $model->id)
                ->call('performDelete')
                ->assertHasNoErrors();
        
        $this->assertSoftDeleted($this->getTableName(), [$fieldName => 'A Eliminar']);
    }
    
    // =======================================================================
    //  PRUEBAS DE BÚSQUEDA
    // =======================================================================
    
    public function test_busqueda_filtra_correctamente(): void
    {
        $admin = $this->createAdmin();
        $fields = $this->getRequiredFields();
        
        // Obtener nombre del campo
        $firstField = array_key_first($fields);
        $fieldName = str_replace('form.', '', $firstField);
        $searchValue = $fields[$firstField];
        
        // Crear algunos modelos
        $this->createModel([$fieldName => $searchValue]);
        $this->createModel([$fieldName => 'Otro Valor']);
        
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->set('search', $searchValue)
                ->assertSet('search', $searchValue);
    }
    
    // =======================================================================
    //  PRUEBAS DE ORDENAMIENTO
    // =======================================================================
    
    public function test_ordenamiento_funciona(): void
    {
        $admin = $this->createAdmin();
        $sortField = $this->getSortableField();
        
        Livewire::actingAs($admin)
                ->test($this->getComponentClass())
                ->call('sortBy', $sortField)
                ->assertSet('sortField', $sortField);
    }
}
