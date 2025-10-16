# Carga Anticipada de Relaciones (Eager Loading)

Esta funcionalidad previene problemas de rendimiento N+1 mediante la carga eficiente de relaciones de base de datos.

---

## 🎯 Qué Resuelve

**Problema N+1:** Cuando cargas un listado y luego accedes a sus relaciones, se genera una consulta adicional por cada elemento.

```php
// ❌ PROBLEMA N+1
$rutinas = Rutina::all(); // 1 consulta

foreach ($rutinas as $rutina) {
    echo $rutina->usuario->nombre; // +1 consulta por cada rutina
    echo $rutina->objetivo->nombre; // +1 consulta más por cada rutina
}

// Resultado: 1 + (100 * 2) = 201 consultas para 100 rutinas
```

**Solución:** Carga anticipada (eager loading) carga todas las relaciones de una vez.

```php
// ✅ SOLUCIÓN CON EAGER LOADING
$rutinas = Rutina::withRelations()->all(); // 3 consultas totales (rutinas + usuarios + objetivos)

foreach ($rutinas as $rutina) {
    echo $rutina->usuario->nombre; // Sin consulta adicional
    echo $rutina->objetivo->nombre; // Sin consulta adicional
}

// Resultado: 3 consultas sin importar cuántas rutinas
```

**Impacto:** De 201 consultas a 3 consultas = **98% menos consultas**.

---

## 🔧 Cómo Usar

### Scopes Disponibles

Cada modelo tiene un scope `withRelations()` que carga sus relaciones más comunes:

```php
// Cargar con relaciones básicas
$auditLogs = AuditLog::withRelations()->get();
$rutinas = Rutina::withRelations()->get();
$ejercicios = Ejercicio::withRelations()->get();
```

Algunos modelos tienen scopes adicionales para casos específicos:

```php
// Rutina con todos sus detalles anidados
$rutina = Rutina::withFullDetails()->find($id);
```

---

## 📋 Scopes por Modelo

### AuditLog
```php
AuditLog::withRelations()->get();
// Carga: user
```

### Rutina
```php
// Básico
Rutina::withRelations()->get();
// Carga: usuario, objetivo

// Completo (para vista de detalle)
Rutina::withFullDetails()->find($id);
// Carga: usuario, objetivo, días, ejercicios, equipos, grupos musculares, bloques
```

### RutinaDia
```php
RutinaDia::withRelations()->get();
// Carga: rutina.usuario, bloques.tipoBloque, ejercicios.equipo, ejercicios.gruposMusculares
```

### Ejercicio
```php
Ejercicio::withRelations()->get();
// Carga: equipo, gruposMusculares
```

### RutinaEjercicio
```php
RutinaEjercicio::withRelations()->get();
// Carga: rutinaDia.rutina, ejercicio.equipo, ejercicio.gruposMusculares, bloque.tipoBloque, registros.unidadMedida
```

### RegistroSerie
```php
RegistroSerie::withRelations()->get();
// Carga: rutinaEjercicio.ejercicio.equipo, rutinaEjercicio.rutinaDia.rutina, unidadMedida
```

---

## 💡 Casos de Uso

### Caso 1: Listado de Auditoría

```php
// ❌ Sin eager loading - problema N+1
$logs = AuditLog::paginate(50); // 1 consulta

// En vista:
@foreach($logs as $log)
    {{ $log->user->nombre }} // +50 consultas adicionales
@endforeach
// Total: 51 consultas

// ✅ Con eager loading
$logs = AuditLog::withRelations()->paginate(50); // 2 consultas

// En vista:
@foreach($logs as $log)
    {{ $log->user->nombre }} // Sin consultas adicionales
@endforeach
// Total: 2 consultas
```

### Caso 2: Detalle de Rutina

```php
// ❌ Sin eager loading - problema N+1 severo
$rutina = Rutina::find($id); // 1 consulta

foreach ($rutina->dias as $dia) {              // +1 consulta
    foreach ($dia->rutinaEjercicios as $ej) {  // +N consultas
        echo $ej->ejercicio->nombre;            // +N consultas
        echo $ej->ejercicio->equipo->nombre;    // +N consultas
    }
}
// Total: Cientos de consultas

// ✅ Con eager loading
$rutina = Rutina::withFullDetails()->find($id); // ~5-7 consultas

foreach ($rutina->dias as $dia) {
    foreach ($dia->rutinaEjercicios as $ej) {
        echo $ej->ejercicio->nombre;            // Sin consultas
        echo $ej->ejercicio->equipo->nombre;    // Sin consultas
    }
}
// Total: 5-7 consultas
```

### Caso 3: Exportación de Datos

```php
// ❌ Exportar 1000 registros sin eager loading
$registros = RegistroSerie::whereBetween('created_at', [$inicio, $fin])->get();

foreach ($registros as $registro) {
    $data[] = [
        'ejercicio' => $registro->rutinaEjercicio->ejercicio->nombre,  // +1000 consultas
        'equipo' => $registro->rutinaEjercicio->ejercicio->equipo->nombre, // +1000 consultas
        'unidad' => $registro->unidadMedida->simbolo, // +1000 consultas
    ];
}
// Total: 3001 consultas

// ✅ Con eager loading
$registros = RegistroSerie::withRelations()
    ->whereBetween('created_at', [$inicio, $fin])
    ->get();

foreach ($registros as $registro) {
    $data[] = [
        'ejercicio' => $registro->rutinaEjercicio->ejercicio->nombre,
        'equipo' => $registro->rutinaEjercicio->ejercicio->equipo->nombre,
        'unidad' => $registro->unidadMedida->simbolo,
    ];
}
// Total: 4 consultas
```

---

## 🔧 Combinando con Otras Operaciones

### Con Filtros
```php
$rutinas = Rutina::withRelations()
    ->where('estado', 'activo')
    ->orderBy('created_at', 'desc')
    ->get();
```

### Con Paginación
```php
$rutinas = Rutina::withRelations()
    ->paginate(15);
```

### Con Query Builders Personalizados
```php
$equipos = Equipo::query()
    ->filtered($search, $showTrash, $sortField, $sortDirection)
    ->with('ejercicios') // Agrega relación adicional si necesitas
    ->paginate(10);
```

### Con Búsqueda
```php
$ejercicios = Ejercicio::withRelations()
    ->where('nombre', 'like', "%{$search}%")
    ->get();
```

---

## ⚙️ Cómo Crear Scopes en Nuevos Modelos

Cuando crees un modelo nuevo que tenga relaciones:

```php
class NuevoModelo extends Model
{
    // Define las relaciones
    public function relacion1()
    {
        return $this->belongsTo(OtroModelo::class);
    }
    
    public function relacion2()
    {
        return $this->hasMany(TercerModelo::class);
    }
    
    // Crea el scope para eager loading
    public function scopeWithRelations($query)
    {
        return $query->with([
            'relacion1:id,campo1,campo2', // Solo campos necesarios
            'relacion2',
        ]);
    }
}
```

**Uso:**
```php
$modelos = NuevoModelo::withRelations()->get();
```

---

## 💡 Por Qué Funciona

### Técnica 1: Carga en Lote
```php
// Laravel ejecuta:
// SELECT * FROM rutinas
// SELECT * FROM usuarios WHERE id IN (1, 2, 3, ..., 100)
// SELECT * FROM objetivos WHERE id IN (5, 8, 12, ..., 50)

// 3 consultas en lugar de 201
```

### Técnica 2: Selección de Campos Específicos
```php
// Solo carga campos necesarios
->with('user:id,nombre_1,apellido_1,correo')

// En lugar de todos los campos de la tabla users
```

**Beneficio:** Reduce transferencia de datos de la base de datos.

---

## 📊 Impacto en Rendimiento

| Operación | Sin Eager Loading | Con Eager Loading | Mejora |
|-----------|-------------------|-------------------|--------|
| Listar 100 rutinas | 201 consultas | 3 consultas | 98% |
| Detalle de rutina (10 días, 50 ejercicios) | 500+ consultas | 7 consultas | 99% |
| Exportar 1000 registros | 3001 consultas | 4 consultas | 99.9% |

**Tiempo de respuesta:**
- Sin eager loading: 2-5 segundos
- Con eager loading: 50-200ms

**Mejora: 10-100x más rápido** 🚀

---

## ✅ Buenas Prácticas

### 1. Usa Scopes en Listados
```php
// ✅ SIEMPRE que iteres sobre relaciones en la vista
$items = Modelo::withRelations()->get();

// ❌ NUNCA sin scope si vas a acceder relaciones
$items = Modelo::all(); // Causará N+1
```

### 2. Elige el Scope Apropiado
```php
// Para listados simples
Rutina::withRelations()->get();

// Para detalles completos
Rutina::withFullDetails()->find($id);
```

### 3. No Sobre-cargar
```php
// ❌ No uses withFullDetails() para un listado simple
Rutina::withFullDetails()->paginate(100); // Carga demasiado

// ✅ Usa el scope básico
Rutina::withRelations()->paginate(100);
```

### 4. Verifica con Debugbar (en desarrollo)
```bash
composer require barryvdh/laravel-debugbar --dev
```

Revisa el panel de queries para detectar problemas N+1.

---

## 🎯 Checklist para Nuevos Desarrollos

Cuando trabajes con datos que tienen relaciones:

- [ ] ¿El modelo tiene relaciones? → Crea scope `withRelations()`
- [ ] ¿Vas a iterar sobre una colección? → Usa `withRelations()`
- [ ] ¿Accedes a relaciones en la vista? → Usa `withRelations()`
- [ ] ¿Necesitas múltiples niveles de relaciones? → Considera scope específico como `withFullDetails()`
- [ ] ¿Exportas o procesas datos? → SIEMPRE usa eager loading
- [ ] Verifica con Debugbar que no hay N+1

---

## 🎉 Resultado

Con eager loading correctamente implementado:
- ✅ 98-99% menos consultas a base de datos
- ✅ 10-100x mejor rendimiento
- ✅ Aplicación lista para escalar
- ✅ Mejor experiencia de usuario
- ✅ Scopes reutilizables en todo el sistema
