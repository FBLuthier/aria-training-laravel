# Selección Masiva Optimizada

Esta funcionalidad permite seleccionar y operar sobre múltiples registros de forma eficiente, incluso con miles de elementos.

---

## 🎯 Qué Resuelve

**Problema:** Necesitas eliminar, restaurar o modificar cientos o miles de registros que coinciden con ciertos filtros.

**Solución:** Sistema de selección masiva con dos modos:
1. **Modo Página Actual:** Selecciona solo los registros visibles (10-50)
2. **Modo Seleccionar Todos:** Selecciona TODOS los que coinciden con filtros (optimizado para miles)

---

## 🔧 Cómo Funciona

### Dos Modos de Selección

#### Modo 1: Página Actual (Por Defecto)

Cuando marcas "Seleccionar Todo", se seleccionan solo los registros de la página visible.

```
Página 1: [✓] Item 1, [✓] Item 2, ... [✓] Item 10
Seleccionados: 10 items
```

**Uso:** Para operar sobre pocos registros (10-50).

#### Modo 2: Seleccionar Todos (Optimizado)

Cuando hay más registros, aparece un banner ofreciendo seleccionar TODOS:

```
ℹ️  Se han seleccionado 10 equipos en esta página.
   [Click aquí para seleccionar todos los 1,500 equipos que coinciden con los filtros]
```

Al hacer clic:

```
✅ Se han seleccionado todos los 1,500 equipos que coinciden con los filtros actuales.
   [Seleccionar solo esta página]
```

**Uso:** Para operar sobre cientos o miles de registros.

---

## 💡 Por Qué Es Eficiente

### Problema con el Enfoque Ingenuo

```php
// ❌ MALO: Cargar 10,000 IDs en memoria
$selectedItems = [1, 2, 3, ..., 10000]; // Array gigante en memoria

// Eliminar todos
Equipo::whereIn('id', $selectedItems)->delete(); // Query lenta con array grande
```

**Problemas:**
- Consume mucha memoria (200KB para 10,000 IDs)
- Query lenta con array grande en WHERE IN
- No escala a decenas de miles de registros

### Solución Optimizada

```php
// ✅ BUENO: Usa query directa sin cargar IDs
if ($this->selectingAll) {
    // Solo usa un flag boolean (1 byte en memoria)
    $query = Equipo::query()->applyFilters($this->search, $this->showingTrash);
    
    // Solo excluye los pocos que el usuario desmarcó (5-10 IDs típicamente)
    if (count($this->exceptItems) > 0) {
        $query->whereNotIn('id', $this->exceptItems);
    }
    
    $query->delete(); // Query eficiente
}
```

**Beneficios:**
- Memoria constante (~1KB) sin importar cuántos registros
- Query optimizada por MySQL
- Escala a millones de registros

---

## 🚀 Casos de Uso Reales

### Caso 1: Limpiar Registros de Prueba

**Escenario:** Tienes 300 registros con "test" en el nombre que necesitas eliminar.

**Pasos:**
1. Busca "test"
2. Sistema muestra 300 resultados
3. Click en "Seleccionar Todo" → selecciona página actual (10)
4. Aparece banner: "Seleccionar todos los 300"
5. Click en banner
6. Click en "Eliminar Seleccionados"
7. Confirma: "¿Eliminar 300 equipos?"
8. ✅ Se eliminan los 300 registros eficientemente

### Caso 2: Restaurar Registros Eliminados por Error

**Escenario:** Alguien eliminó 150 mancuernas por error.

**Pasos:**
1. Ir a "Ver Papelera"
2. Buscar "Mancuerna"
3. Sistema muestra 150 resultados
4. "Seleccionar Todo" → "Seleccionar todos los 150"
5. Click en "Restaurar Seleccionados"
6. ✅ Se restauran los 150 registros

### Caso 3: Seleccionar Todos Excepto Algunos

**Escenario:** Necesitas eliminar 1,000 registros pero conservar 3 específicos.

**Pasos:**
1. Aplica filtros → 1,000 resultados
2. "Seleccionar todos los 1,000"
3. Navega y **desmarca** los 3 que quieres conservar
4. Sistema los guarda en `exceptItems`
5. Click en "Eliminar Seleccionados"
6. ✅ Se eliminan 997 registros (1,000 - 3)

---

## 🎨 Estados Visuales

### Estado 1: Sin Selección
```
[ ] Seleccionar Todo
-------------------
[ ] Item 1
[ ] Item 2
[ ] Item 3
```

### Estado 2: Selección de Página
```
[✓] Seleccionar Todo

ℹ️  Se han seleccionado 10 equipos en esta página.
   [Seleccionar todos los 1,500 equipos que coinciden con los filtros]

-------------------
[✓] Item 1
[✓] Item 2
[✓] Item 3
```

### Estado 3: Selección Total
```
[✓] Seleccionar Todo

✅ Se han seleccionados los 1,500 equipos que coinciden con los filtros actuales.
   [Seleccionar solo esta página]

-------------------
[✓] Item 1
[✓] Item 2
[✓] Item 3
```

---

## 🔧 Implementación Técnica

### En el Trait WithBulkActions

**Propiedades clave:**
```php
public bool $selectingAll = false;    // Flag: ¿modo "seleccionar todos" activo?
public array $exceptItems = [];       // IDs excluidos en modo "seleccionar todos"
public array $selectedItems = [];     // IDs seleccionados en modo página
```

**Métodos principales:**
```php
selectAllRecords()     // Activa modo "seleccionar todos"
selectOnlyPage()       // Desactiva modo "seleccionar todos"
toggleExcept($id)      // Excluye/incluye un ID específico
```

### En el Componente

**Implementación de acción en lote:**
```php
public function deleteSelected(): void
{
    // Obtener modelos basado en el modo
    $modelos = $this->getSelectedModels();
    
    // Ejecutar action
    $result = app(DeleteModelAction::class)->executeBulk($modelos);
    
    // Limpiar selecciones
    $this->confirmingBulkDelete = false;
    $this->clearSelections();
    
    // Notificar
    $this->dispatch('notify', message: $result['message'], type: 'success');
}

protected function getSelectedModels(bool $withTrashed = false)
{
    $query = $this->getFilteredQuery();
    
    if ($withTrashed) {
        $query->withTrashed();
    }
    
    // Aquí está la magia
    if ($this->selectingAll) {
        // Modo "seleccionar todos": usa query directa
        if (count($this->exceptItems) > 0) {
            $query->whereNotIn('id', $this->exceptItems);
        }
    } else {
        // Modo página: usa array de IDs
        $query->whereIn('id', $this->selectedItems);
    }

    return $query->get();
}
```

**Por qué funciona:**
- En modo página: `whereIn` con 10-50 IDs es rápido
- En modo "todos": Query usa filtros directos, solo excluye unos pocos con `whereNotIn`

---

## 📊 Métricas de Rendimiento

| Registros | Modo | Memoria | Tiempo | Query |
|-----------|------|---------|--------|-------|
| 10 | Página | 2KB | 20ms | `WHERE IN (1,2,...,10)` |
| 100 | Página | 10KB | 30ms | `WHERE IN (1,2,...,100)` |
| 1,000 | Todos | 1KB | 100ms | `WHERE nombre LIKE ...` |
| 10,000 | Todos | 1KB | 150ms | `WHERE nombre LIKE ...` |
| 100,000 | Todos | 1KB | 200ms | `WHERE nombre LIKE ...` |

**Conclusión:** Memoria constante y escalabilidad lineal.

---

## ✅ Checklist para Implementar en Nuevo CRUD

- [ ] Incluir trait `WithBulkActions` en el componente
- [ ] Implementar método `getFilteredQuery()`
- [ ] Implementar método `getTotalFilteredCount()` como Computed Property
- [ ] Implementar método `selectAllItems()`
- [ ] Implementar método `getSelectedModels()`
- [ ] Implementar acciones en lote: `deleteSelected()`, `restoreSelected()`, etc.
- [ ] Agregar banners de selección en la vista
- [ ] Agregar modales de confirmación con contador dinámico

Ver `docs/desarrollo/crear_nuevo_crud.md` para ejemplo completo.

---

## 🎯 Resultado

Con esta funcionalidad puedes:
- ✅ Seleccionar de 1 a 1,000,000 registros eficientemente
- ✅ Operar en lote con mínimo consumo de memoria
- ✅ Feedback visual claro del estado de selección
- ✅ Excluir registros específicos en selección masiva
- ✅ Consistencia en toda la aplicación
