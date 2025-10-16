# Buenas Prácticas y Patrones del Sistema

Este documento explica **por qué** usamos ciertos patrones y prácticas en el sistema, no solo **cómo** usarlos.

---

## 🎯 Filosofía General

### Principio DRY (Don't Repeat Yourself)

**Problema que resuelve:** Código duplicado es difícil de mantener. Un cambio requiere actualizar múltiples lugares, lo que lleva a bugs e inconsistencias.

**Cómo lo aplicamos:**
- Actions para lógica de negocio reutilizable
- Traits para funcionalidad compartida entre componentes
- Query Builders para queries repetitivas
- Componentes Blade para UI consistente

**Ejemplo real:**
```php
// ❌ ANTES: Código de eliminación duplicado en 6 métodos
public function deleteEquipo() {
    $this->authorize('delete', $equipo);
    $oldValues = $equipo->toArray();
    $equipo->delete();
    ModelAudited::dispatch('delete', $equipo, $oldValues, null);
    // ... 15 líneas más
}

// ✅ DESPUÉS: Reutilizamos la Action
public function deleteEquipo() {
    $result = app(DeleteModelAction::class)->execute($equipo);
    // ... 1 línea, lógica centralizada
}
```

**Beneficio:** Cambio en 1 lugar = actualización en todo el sistema.

---

## 🧩 Actions (Lógica de Negocio)

### Por Qué Usamos Actions

**Problema:** La lógica de negocio compleja (eliminar, auditar, notificar) estaba esparcida en múltiples componentes.

**Solución:** Actions encapsulan operaciones de negocio completas.

**Beneficios:**

1. **Testing Más Fácil**
```php
// Puedes testear la Action aisladamente
public function test_delete_action()
{
    $modelo = Modelo::factory()->create();
    $result = app(DeleteModelAction::class)->execute($modelo);
    
    $this->assertTrue($result['success']);
    $this->assertSoftDeleted($modelo);
}
```

2. **Reutilización en Cualquier Contexto**
```php
// Desde un componente Livewire
app(DeleteModelAction::class)->execute($modelo);

// Desde un comando de consola
app(DeleteModelAction::class)->executeBulk($modelos);

// Desde un Job
app(DeleteModelAction::class)->execute($modelo, authorize: false);
```

3. **Consistencia Garantizada**
Todos los lugares que eliminan modelos usan la misma lógica de auditoría, autorización y notificación.

---

## 🎨 Traits (Funcionalidad Compartida)

### Por Qué Usamos Traits

**Problema:** Cada nuevo CRUD requería reimplementar las mismas funcionalidades: formularios, ordenamiento, papelera, etc.

**Solución:** Traits proporcionan funcionalidad "plug-and-play".

**Ejemplo real - WithCrudOperations:**

```php
// ❌ ANTES: ~500 líneas por componente CRUD
class GestionarEquipos extends Component
{
    // 25+ propiedades
    public string $sortField = 'id';
    public bool $showingTrash = false;
    public bool $showFormModal = false;
    // ... más propiedades
    
    // 40+ métodos
    public function create() { /* 10 líneas */ }
    public function edit($id) { /* 15 líneas */ }
    public function save() { /* 20 líneas */ }
    public function delete($id) { /* 15 líneas */ }
    public function sortBy($field) { /* 10 líneas */ }
    public function toggleTrash() { /* 8 líneas */ }
    // ... más métodos
}

// ✅ DESPUÉS: ~50 líneas por componente CRUD
class GestionarEquipos extends Component
{
    use WithCrudOperations; // ¡Incluye todo lo anterior!
    
    // Solo implementas lo específico del modelo
    protected function getModelClass(): string { return Equipo::class; }
    protected function setFormModel($model): void { $this->form->setEquipo($model); }
    protected function auditFormSave(?array $oldValues): void { /* auditoría */ }
}
```

**Beneficios:**

1. **Reducción Masiva de Código**
De ~500 líneas a ~50 líneas por CRUD.

2. **Desarrollo Más Rápido**
De 4-6 horas a 30-60 minutos por CRUD completo.

3. **Mantenimiento Centralizado**
Bug fix en el trait = actualización en todos los CRUDs automáticamente.

4. **Modularidad**
Puedes usar los traits individualmente si solo necesitas una funcionalidad específica:
```php
class SoloNecesitoOrdenamiento extends Component
{
    use HasSorting; // Solo ordenamiento, nada más
}
```

---

## 🔍 Query Builders Personalizados

### Por Qué Usamos Query Builders

**Problema:** Las queries complejas se repetían múltiples veces con pequeñas variaciones.

**Ejemplo real:**

```php
// ❌ ANTES: Esta query se repetía 4 veces en el componente
Equipo::query()
    ->when($this->search, fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%'))
    ->when($this->showingTrash, fn($q) => $q->onlyTrashed())
    ->orderBy($this->sortField, $this->sortDirection->value)
    ->paginate(10);

// ✅ DESPUÉS: Query reutilizable y legible
Equipo::query()
    ->filtered($this->search, $this->showingTrash, $this->sortField, $this->sortDirection->value)
    ->paginate(10);
```

**Beneficios:**

1. **Eliminación de Duplicación**
La misma query se usa en paginación, conteo, selección, etc.

2. **Legibilidad**
`->filtered(...)` es más legible que múltiples `->when(...)`.

3. **Testabilidad**
```php
public function test_search_filters_correctly()
{
    $equipo1 = Equipo::factory()->create(['nombre' => 'Mancuernas']);
    $equipo2 = Equipo::factory()->create(['nombre' => 'Barra']);
    
    $results = Equipo::query()->search('Manc')->get();
    
    $this->assertCount(1, $results);
}
```

4. **Composición Fluida**
```php
Equipo::query()
    ->search('Manc')
    ->trash(true)
    ->sortBy('nombre', 'desc')
    ->active()
    ->get();
```

---

## ⚡ Computed Properties (Livewire v3)

### Por Qué Usamos Computed Properties

**Problema:** Métodos costosos (queries, cálculos) se ejecutaban múltiples veces durante un mismo render.

**Ejemplo real:**

```php
// ❌ ANTES: selectedCount() se llama 6 veces en la vista
public function selectedCount(): int
{
    if ($this->selectingAll) {
        return $this->getTotalFilteredCount() - count($this->exceptItems);
    }
    return count($this->selectedItems);
}

// En vista:
{{ $this->selectedCount() }} // Llamada 1
{{ $this->selectedCount() }} // Llamada 2 - recalcula todo
{{ $this->selectedCount() }} // Llamada 3 - recalcula todo
// ... 3 veces más
```

**Resultado:** 6 ejecuciones del método = potencialmente 6 queries a DB.

```php
// ✅ DESPUÉS: Con Computed Property
#[Computed]
public function selectedCount(): int
{
    if ($this->selectingAll) {
        return $this->totalFilteredCount - count($this->exceptItems);
    }
    return count($this->selectedItems);
}

// En vista:
{{ $this->selectedCount }} // Llamada 1 - calcula y cachea
{{ $this->selectedCount }} // Llamada 2 - usa caché
{{ $this->selectedCount }} // Llamada 3 - usa caché
```

**Resultado:** 1 ejecución del método = 1 query máximo. Las demás usan caché.

**Beneficio:** Reducción significativa de overhead de cálculos y queries.

---

## 📊 WithAuditLogging Trait

### Por Qué Centralizamos la Auditoría

**Problema:** El código de auditoría era inconsistente y estaba duplicado en múltiples lugares.

**Ejemplo real:**

```php
// ❌ ANTES: Código de auditoría manual en cada operación
if ($this->form->equipo->wasRecentlyCreated) {
    ModelAudited::dispatch('create', $this->form->equipo, null, $this->form->equipo->toArray());
} else {
    ModelAudited::dispatch('update', $this->form->equipo, $oldValues, $this->form->equipo->toArray());
}

// ✅ DESPUÉS: Trait maneja todo automáticamente
$this->auditSave($this->form->equipo, $oldValues);
```

**Beneficios:**

1. **Consistencia Garantizada**
Todos usan el mismo formato de auditoría.

2. **Menos Errores**
No hay riesgo de olvidar auditar una operación.

3. **Código Más Limpio**
Métodos semánticos: `auditCreate()`, `auditUpdate()`, `auditDelete()`.

---

## 🎨 Componentes Blade Reutilizables

### Por Qué Componentes Blade

**Problema:** Bloques HTML repetitivos en todas las vistas.

**Ejemplo real:**

```blade
{{-- ❌ ANTES: Repetido 20 veces en cada tabla --}}
<td class="w-4 p-4">
    <input 
        wire:model.live="selectedItems" 
        value="{{ $equipo->id }}" 
        type="checkbox" 
        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
    >
</td>

{{-- ✅ DESPUÉS: Componente reutilizable --}}
<x-table-checkbox :value="$equipo->id" />
```

**Beneficios:**

1. **Consistencia UI**
Todos los checkboxes se ven y comportan igual.

2. **Mantenibilidad**
Cambiar el estilo en 1 componente = actualiza todas las tablas.

3. **Menos Código**
```blade
{{-- Antes: 200 líneas de HTML repetitivo --}}
{{-- Después: 50 líneas usando componentes --}}
{{-- Reducción: 75% --}}
```

---

## 🔢 Constantes en Lugar de Valores Mágicos

### Por Qué Evitamos Números Mágicos

**Problema:** Valores hardcodeados sin contexto.

**Ejemplo real:**

```php
// ❌ ANTES: ¿Qué es 10?
->paginate(10);

// ✅ DESPUÉS: Semántico y claro
private const PER_PAGE = 10;
->paginate(self::PER_PAGE);
```

**Beneficios:**

1. **Autodocumentación**
```php
/** Número de registros por página */
private const PER_PAGE = 10;
```

2. **Fácil de Cambiar**
```php
// Cambio en 1 lugar:
private const PER_PAGE = 20;

// Afecta todos los usos automáticamente
```

3. **Evita Errores**
```php
// ❌ Fácil equivocarse:
if ($count > 10) { /* ... */ } // ¿Es este el mismo 10?

// ✅ Obvio:
if ($count > self::PER_PAGE) { /* ... */ }
```

---

## 🔄 WithBulkActions Trait

### Por Qué Optimizamos la Selección Masiva

**Problema:** Cargar miles de IDs en memoria para selecciones masivas causaba problemas de rendimiento.

**Solución:** Modo "selectingAll" con lista de excepciones.

**Ejemplo real:**

```php
// ❌ ANTES: Cargar 10,000 IDs en memoria
$this->selectedItems = Equipo::pluck('id')->toArray(); // Array de 10,000 elementos

// Eliminar seleccionados
Equipo::whereIn('id', $this->selectedItems)->delete(); // Query lenta con array gigante

// ✅ DESPUÉS: Usa query directa
if ($this->selectingAll) {
    $query = Equipo::query()->applyFilters($this->search, $this->showingTrash);
    if (count($this->exceptItems) > 0) {
        $query->whereNotIn('id', $this->exceptItems); // Solo excluye unos pocos
    }
    $query->delete(); // Query eficiente
}
```

**Beneficio:** Maneja eficientemente desde 10 hasta 10,000+ registros sin consumir memoria excesiva.

---

## 📝 Estructura de Archivos

### Por Qué Esta Organización

```
app/
├── Actions/              # Lógica de negocio reutilizable
├── Livewire/
│   ├── Forms/           # Validación y guardado encapsulado
│   └── Traits/          # Funcionalidad compartida
└── Models/
    └── Builders/        # Queries reutilizables
```

**Beneficios:**

1. **Separación de Responsabilidades**
Cada carpeta tiene un propósito claro.

2. **Fácil de Encontrar**
¿Buscas lógica de negocio? → Actions
¿Buscas funcionalidad compartida? → Traits
¿Buscas queries? → Builders

3. **Escalabilidad**
Fácil agregar nuevos componentes sin desorganizar.

---

## ✅ Checklist de Buenas Prácticas

Al crear nuevo código, pregúntate:

- [ ] **¿Estoy duplicando código?** → Considera crear Action/Trait/Builder
- [ ] **¿Este método se usa múltiples veces en la vista?** → Considera Computed Property
- [ ] **¿Estoy hardcodeando números/strings?** → Usa constantes
- [ ] **¿Esta query se repite?** → Agrégala al Query Builder
- [ ] **¿Este bloque HTML se repite?** → Crea componente Blade
- [ ] **¿Estoy mezclando lógica de negocio con UI?** → Separa en Action
- [ ] **¿Necesito auditar esta operación?** → Usa WithAuditLogging trait

---

## 🎯 Resultado Final

Siguiendo estas prácticas obtenemos:

- ✅ **Código DRY:** Sin duplicación
- ✅ **Mantenibilidad:** Cambios en 1 lugar
- ✅ **Performance:** Caché automático, queries optimizadas
- ✅ **Testabilidad:** Componentes pequeños y aislados
- ✅ **Escalabilidad:** Fácil agregar funcionalidad
- ✅ **Consistencia:** Mismo patrón en todo el sistema
- ✅ **Productividad:** Desarrollo más rápido

**El tiempo invertido en seguir estas prácticas se recupera rápidamente en velocidad de desarrollo y facilidad de mantenimiento.** 🚀
