# Aria Training

Sistema de gestión de rutinas de entrenamiento personalizado para gimnasios y entrenadores.

## 📋 Descripción

Aria Training es una aplicación web que permite a entrenadores crear y gestionar rutinas de ejercicio personalizadas para sus atletas, con seguimiento de progreso, auditoría completa de acciones y gestión integral de equipamiento y ejercicios.

## 🚀 Stack Tecnológico

- **Backend:** Laravel 11
- **Frontend:** Livewire 3, Alpine.js, TailwindCSS
- **Base de datos:** MySQL 8.0
- **PHP:** 8.2+

## ✨ Características Principales

- ✅ Gestión completa de usuarios (Administradores, Entrenadores, Atletas)
- ✅ CRUD de equipos, ejercicios, rutinas y registros de series
- ✅ Sistema de auditoría completo con exportación avanzada
- ✅ Selección masiva optimizada para grandes volúmenes de datos
- ✅ Arquitectura modular con componentes reutilizables
- ✅ Query optimization con eager loading (prevención N+1)
- ✅ Autorización granular con políticas de Laravel
- ✅ UI responsive con dark mode

## 📚 Documentación

La documentación completa del proyecto está en la carpeta `/docs`:

- **[Índice de Documentación](docs/INDICE.md)** - Punto de entrada a toda la documentación
- **[Definición del Proyecto](docs/definicion_proyecto.md)** - Visión general y alcance
- **[Crear Nuevo CRUD](docs/desarrollo/crear_nuevo_crud.md)** - Guía práctica
- **[Componentes Reutilizables](docs/arquitectura/componentes_reutilizables.md)** - Referencia de Actions, Traits, Builders
- **[Buenas Prácticas](docs/desarrollo/buenas_practicas.md)** - Filosofía y patrones del código

## 🛠️ Instalación

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- MySQL 8.0+
- XAMPP (opcional, para desarrollo local)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd aria-training
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar el entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos**

Edita el archivo `.env` con tus credenciales de base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aria_training
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

6. **Compilar assets**
```bash
npm run dev
```

7. **Iniciar el servidor**
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

### Usuarios de Prueba

Después de ejecutar los seeders:

- **Administrador:**
  - Email: `admin@aria.com`
  - Password: `password`

## 🏗️ Arquitectura del Sistema

El sistema utiliza una arquitectura modular con componentes reutilizables:

- **Actions:** Lógica de negocio encapsulada (Delete, Restore, ForceDelete)
- **Traits:** Funcionalidad compartida (WithCrudOperations, WithAuditLogging, WithBulkActions)
- **Query Builders:** Queries reutilizables y optimizadas
- **Componentes Blade:** UI consistente y reutilizable

Ver [Componentes Reutilizables](docs/arquitectura/componentes_reutilizables.md) para más detalles.

## 📦 Estructura del Proyecto

```
aria-training/
├── app/
│   ├── Actions/              # Lógica de negocio reutilizable
│   ├── Livewire/
│   │   ├── Forms/           # Forms de Livewire
│   │   └── Traits/          # Traits compartidos
│   ├── Models/
│   │   └── Builders/        # Query Builders personalizados
│   └── Policies/            # Autorización
├── docs/                    # Documentación completa
│   ├── arquitectura/        # Docs de arquitectura
│   ├── desarrollo/          # Guías de desarrollo
│   ├── funcionalidades/     # Docs de features
│   └── INDICE.md           # Índice maestro
├── resources/
│   └── views/
│       ├── components/      # Componentes Blade reutilizables
│       └── livewire/        # Vistas de componentes Livewire
└── tests/                   # Tests unitarios y de integración
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con coverage
php artisan test --coverage
```

## 🤝 Contribuir

1. Lee la [documentación de desarrollo](docs/desarrollo/)
2. Revisa las [buenas prácticas](docs/desarrollo/buenas_practicas.md)
3. Sigue la [guía de crear CRUD](docs/desarrollo/crear_nuevo_crud.md) para nuevas funcionalidades
4. Asegúrate de que los tests pasen antes de hacer commit

## 📝 Convenciones de Código

- **PSR-12** para PHP
- **Nombres en español** para documentación
- **Componentes reutilizables** para evitar duplicación
- **Type hints** en todos los métodos
- **Computed Properties** de Livewire v3 para performance
- **Eager Loading** para prevenir problemas N+1

Ver [Buenas Prácticas](docs/desarrollo/buenas_practicas.md) para más detalles.

## 📄 Licencia

Este proyecto es propietario y confidencial.

## 👥 Equipo

Desarrollado como proyecto de entrenamiento para FBLuthier.
