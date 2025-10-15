# Estructura de Base de Datos - Aria Training

## 🗄️ Diseño del Modelo de Datos

### Información General
**Versión del esquema:** 1.2 (actualizado con pruebas)
**Motor de BD:** MariaDB 10.6+
**Arquitectura:** Relacional con relaciones Eloquent
**Convenciones:** Nombres en minúsculas, singular

## 📋 Tablas Principales

### 1. Tabla: usuarios
**Propósito:** Gestión de usuarios del sistema

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint | Primary Key, Auto-increment | Identificador único |
| `tipo_usuario_id` | bigint | Foreign Key → tipo_usuarios.id | Rol del usuario |
| `usuario` | varchar(255) | Not Null | Nombre de usuario único |
| `correo` | varchar(255) | Not Null, Unique | Correo electrónico único |
| `contrasena` | varchar(255) | Not Null | Hash de contraseña (Bcrypt) |
| `nombre_1` | varchar(255) | Not Null | Primer nombre |
| `nombre_2` | varchar(255) | Nullable | Segundo nombre |
| `apellido_1` | varchar(255) | Not Null | Primer apellido |
| `apellido_2` | varchar(255) | Nullable | Segundo apellido |
| `telefono` | varchar(20) | Nullable | Número de teléfono |
| `fecha_nacimiento` | date | Nullable | Fecha de nacimiento |
| `estado` | tinyint | Default 1 | Estado activo/inactivo |
| `created_at` | timestamp | Not Null | Fecha de creación |
| `updated_at` | timestamp | Not Null | Última actualización |
| `deleted_at` | timestamp | Nullable | Soft delete |

### 2. Tabla: tipo_usuarios
**Propósito:** Definición de roles de usuario

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint | Primary Key, Auto-increment | Identificador único |
| `rol` | varchar(50) | Not Null, Unique | Nombre del rol |

**Datos Iniciales:**
- `id: 1, rol: 'Administrador'`
- `id: 2, rol: 'Entrenador'`
- `id: 3, rol: 'Atleta'`

### 3. Tabla: equipos
**Propósito:** Catálogo de equipamiento disponible

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id` | bigint | Primary Key, Auto-increment | Identificador único |
| `nombre` | varchar(45) | Not Null, Unique | Nombre del equipo |
| `created_at` | timestamp | Not Null | Fecha de creación |
| `updated_at` | timestamp | Not Null | Última actualización |
| `deleted_at` | timestamp | Nullable | Soft delete |

## 🔗 Relaciones entre Tablas

### Relaciones Implementadas

#### Relación Usuario-TipoUsuario
```
usuarios (N) ────◄─── (1) tipo_usuarios
   │                    │
   └─── tipo_usuario_id ─┘
```

**Tipo de relación:** Muchos a Uno
**Cardinalidad:** N usuarios pueden tener 1 tipo de usuario
**Restricciones:** Foreign Key con CASCADE

#### Relación Ejercicios-Equipos (Futura)
```
ejercicios (N) ────►─── (N) equipos
      │                       │
      └─── equipo_id           │
                              │
                     ┌─────────┴─────────┐
                     │                   │
                ejercicios_equipos      │
                (Tabla pivote futura)   │
                     │                   │
                     └───────────────────┘
```

## 📊 Índices y Optimización

### Índices Implementados

#### Índices de Rendimiento
```sql
-- Índice para búsquedas por correo (usuarios frecuentes)
ALTER TABLE usuarios ADD INDEX idx_usuarios_correo (correo);

-- Índice para búsquedas por usuario (login frecuente)
ALTER TABLE usuarios ADD INDEX idx_usuarios_usuario (usuario);

-- Índice compuesto para búsquedas administrativas
ALTER TABLE usuarios ADD INDEX idx_usuarios_tipo_estado (tipo_usuario_id, estado, deleted_at);
```

#### Índices de Integridad
```sql
-- Foreign Key constraints automáticas por Laravel
-- Índices únicos automáticos en campos unique
```

## 🛠️ Convenciones de Nomenclatura

### Nombres de Tablas
- **Minúsculas:** Todas las tablas en minúsculas
- **Singular:** Nombres en singular (`users`, no `usuarios`)
- **Descriptivos:** Nombres que indican claramente el propósito

### Nombres de Campos
- **snake_case:** `tipo_usuario_id` en lugar de `tipoUsuarioId`
- **Claridad:** Campos que indican relación (`usuario_id`, `equipo_id`)
- **Consistencia:** Mismos nombres para campos similares

### Nombres de Claves Foráneas
| Patrón Anterior | Patrón Correcto | Ejemplo |
|-----------------|-----------------|---------|
| `id_tipo_usuario` | `tipo_usuario_id` | ✅ `tipo_usuario_id` |
| `id_equipment` | `equipo_id` | ✅ `equipo_id` |
| `id_user_type` | `tipo_usuario_id` | ✅ `tipo_usuario_id` |

## 📋 Migraciones Implementadas

### Migraciones de Versión 1.2

#### 1. Creación de Tablas Base
```php
// database/migrations/2025_10_15_000001_create_tipo_usuarios_table.php
Schema::create('tipo_usuarios', function (Blueprint $table) {
    $table->id();
    $table->string('rol', 50)->unique();
    $table->timestamps();
});

// database/migrations/2025_10_15_000002_create_usuarios_table.php
Schema::create('usuarios', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tipo_usuario_id')->constrained('tipo_usuarios');
    $table->string('usuario', 255)->unique();
    $table->string('correo', 255)->unique();
    $table->string('contrasena');
    $table->string('nombre_1', 255);
    $table->string('nombre_2', 255)->nullable();
    $table->string('apellido_1', 255);
    $table->string('apellido_2', 255)->nullable();
    $table->string('telefono', 20)->nullable();
    $table->date('fecha_nacimiento')->nullable();
    $table->boolean('estado')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

#### 2. Migración de Equipamiento
```php
// database/migrations/2025_10_15_000003_create_equipos_table.php
Schema::create('equipos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 45)->unique();
    $table->timestamps();
    $table->softDeletes();
});
```

#### 3. Migración de Refactorización (Claves Foráneas)
```php
// database/migrations/2025_10_15_022526_rename_foreign_keys_in_rutina_dias_table.php
// (Migración específica para corrección de claves foráneas)
```

## 🎯 Seeders y Datos Iniciales

### Seeders Implementados

#### TipoUsuariosSeeder
```php
// database/seeders/TipoUsuarioSeeder.php
public function run(): void
{
    TipoUsuario::create(['id' => 1, 'rol' => 'Administrador']);
    TipoUsuario::create(['id' => 2, 'rol' => 'Entrenador']);
    TipoUsuario::create(['id' => 3, 'rol' => 'Atleta']);
}
```

#### EquiposSeeder (Datos de Ejemplo)
```php
// database/seeders/EquipoSeeder.php
public function run(): void
{
    Equipo::create(['nombre' => 'Mancuernas 10kg']);
    Equipo::create(['nombre' => 'Banca olímpica']);
    Equipo::create(['nombre' => 'Barra olímpica']);
    Equipo::create(['nombre' => 'Cinta de correr']);
}
```

## 🔒 Restricciones y Validaciones

### Restricciones de Base de Datos
| Tabla | Restricción | Tipo | Descripción |
|-------|-------------|------|-------------|
| `usuarios` | `usuario` | UNIQUE | Nombre de usuario único |
| `usuarios` | `correo` | UNIQUE | Correo electrónico único |
| `equipos` | `nombre` | UNIQUE | Nombre único por equipo |
| `tipo_usuarios` | `rol` | UNIQUE | Rol único por tipo |

### Validaciones de Aplicación
```php
// app/Models/Usuario.php
protected $rules = [
    'tipo_usuario_id' => 'required|exists:tipo_usuarios,id',
    'usuario' => 'required|string|max:255|unique:usuarios,usuario',
    'correo' => 'required|email|max:255|unique:usuarios,correo',
    'contrasena' => 'required|string|min:8',
    'nombre_1' => 'required|string|max:255',
    'apellido_1' => 'required|string|max:255'
];
```

## 📈 Estrategias de Optimización

### Índices Estratégicos
- **Búsquedas frecuentes:** Índices en campos consultados comúnmente
- **Relaciones:** Índices en claves foráneas para JOINs eficientes
- **Filtros:** Índices en campos usados para filtrado

### Consultas Optimizadas
```php
// ✅ Consulta optimizada con índices
$equipos = Equipo::where('nombre', 'like', '%mancuerna%')
                ->orderBy('nombre')
                ->paginate(10);

// ❌ Consulta no optimizada
$equipos = Equipo::all()->filter(function($e) {
    return str_contains($e->nombre, 'mancuerna');
});
```

## 🚀 Expansión Futura

### Nuevas Tablas Planificadas
| Tabla | Propósito | Relaciones |
|-------|-----------|------------|
| `ejercicios` | Catálogo de ejercicios | grupos_musculares, equipos |
| `grupos_musculares` | Categorías musculares | ejercicios |
| `rutinas` | Plantillas de entrenamiento | usuarios (creador) |
| `rutina_dias` | Días de rutina | rutinas, ejercicios |
| `registro_series` | Registro de entrenamientos | usuarios, rutina_dias |

### Nuevas Relaciones
```
rutinas ────┐
            ├── N:N ─── ejercicios (con series específicas)
usuarios ───┤
            └── 1:N ─── registro_series (progreso personal)
```

## 📋 Procedimientos de Mantenimiento

### Backup y Recuperación
- **Backups automáticos:** Configurar en servidor de producción
- **Esquema versionado:** Migraciones mantienen historial
- **Datos de prueba:** Seeders para recrear estado inicial

### Monitoreo de Rendimiento
- **Consulta lenta:** Logs de consultas > 500ms
- **Índices faltantes:** Análisis de consultas frecuentes
- **Espacio utilizado:** Monitoreo de crecimiento de tablas

---

*Esta documentación establece las convenciones y estructura de la base de datos de Aria Training, asegurando consistencia y mantenibilidad.*
