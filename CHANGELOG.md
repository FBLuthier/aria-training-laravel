# Release Notes

## [Unreleased](https://github.com/laravel/laravel/compare/v12.4.0...12.x)

### 2025-10-17 - Sistema Completo de Loading States y Notificaciones Toast (v1.5)

**🎨 Sistema de Loading States:**
- Componente `<x-spinner>`: Indicador SVG en 5 tamaños (xs, sm, md, lg, xl) y 5 colores (current, white, gray, primary, red)
- Componente `<x-loading-overlay>`: Overlay de pantalla completa con mensaje personalizable para operaciones largas
- Componente `<x-loading-state>`: Estados de carga flexibles inline (para inputs) o bloque (para tablas)
- Actualización de botones (primary, secondary, danger) con prop `loadingTarget` para spinner automático
- Prevención de doble-click: botones se deshabilitan automáticamente y muestran "Procesando..."

**🔔 Sistema de Notificaciones Toast:**
- Componente `<x-toast-container>`: Sistema completo con Alpine.js para notificaciones elegantes
- 4 tipos de notificaciones: success (verde), error (rojo), warning (amarillo), info (azul)
- Auto-dismiss configurable con barra de progreso animada (duración por defecto: 4 segundos)
- Apilamiento inteligente: múltiples notificaciones se organizan verticalmente sin superponerse
- Cierre manual mediante botón X en cualquier momento
- Componente `<x-toast-trigger>`: Helper para mostrar toasts desde session flash después de redirecciones
- Helpers globales JavaScript: `notify()`, `notifySuccess()`, `notifyError()`, `notifyWarning()`, `notifyInfo()`
- Integración completa con Livewire via `$this->dispatch('notify')` y Alpine.js
- Compatible con dark mode y totalmente responsivo

**🔧 Implementaciones Completas:**
- **GestionarEquipos:**
  - Spinner en campo de búsqueda en tiempo real
  - Loading state en toggle de papelera
  - Spinners en todas las acciones de tabla (editar, eliminar, restaurar)
  - Loading states en modales de confirmación
  - Overlay para operaciones masivas (eliminar, restaurar, force delete)
  - Notificaciones toast en todas las operaciones CRUD y masivas
- **GestionarAuditoria:**
  - Spinner en búsqueda general
  - Loading states en todos los filtros
  - Spinners en botón "Limpiar Filtros"
  - Overlay para proceso de exportación
  - Notificación toast al limpiar filtros

**📚 Documentación Exhaustiva:**
- Guía completa: `docs/desarrollo/guias/loading_states.md` (483 líneas)
- Guía completa: `docs/desarrollo/guias/toast_notifications.md` (629 líneas)
- Ejemplos prácticos de uso en diferentes contextos
- Mejores prácticas y patrones recomendados
- Sección de debugging y troubleshooting
- Quick reference para desarrollo rápido
- Actualizado `docs/definicion_proyecto.md` a versión 1.5
- Actualizado `docs/arquitectura/componentes_reutilizables.md` (18 componentes totales)
- Actualizado `docs/arquitectura/componentes_blade.md` con sección UX v1.5

**🐛 Correcciones:**
- Solucionado error `PublicPropertyNotFoundException` en modal de equipos
- Corregida referencia hardcoded a `showExportModal` en `modal.blade.php`
- Agregado soporte para propiedad `entangleProperty` dinámica en modales
- Mejorada compatibilidad con diferentes tipos de modales (form-modal, confirmation-modal)
- Reemplazado `wire:loading.remove` por overlay semitransparente en tabla de auditoría
- Corregido problema con botón "Ver Detalles" en vista de auditoría

**📊 Impacto Medible:**
- ✅ Experiencia de usuario significativamente mejorada con feedback visual inmediato
- ✅ Prevención automática de doble-click en todas las operaciones
- ✅ Reducción de frustración del usuario al saber que el sistema está procesando
- ✅ Percepción de velocidad mejorada con indicadores visuales
- ✅ Notificaciones elegantes y no intrusivas sin código adicional
- ✅ Sistema completamente reutilizable para futuras funcionalidades
- ✅ 5 nuevos componentes Blade disponibles (8 componentes de UI totales)
- ✅ Helpers globales disponibles en toda la aplicación

## [v12.4.0](https://github.com/laravel/laravel/compare/v12.3.1...v12.4.0) - 2025-08-29

* [12.x] Add default Redis retry configuration by [@mateusjatenee](https://github.com/mateusjatenee) in https://github.com/laravel/laravel/pull/6666

## [v12.3.1](https://github.com/laravel/laravel/compare/v12.3.0...v12.3.1) - 2025-08-21

* [12.x] Bump Pint version by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6653
* [12.x] Making sure all related processed are closed when terminating the currently command by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6654
* [12.x] Use application name from configuration by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6655
* Bring back postAutoloadDump script by [@jasonvarga](https://github.com/jasonvarga) in https://github.com/laravel/laravel/pull/6662

## [v12.3.0](https://github.com/laravel/laravel/compare/v12.2.0...v12.3.0) - 2025-08-03

* Fix Critical Security Vulnerability in form-data Dependency by [@izzygld](https://github.com/izzygld) in https://github.com/laravel/laravel/pull/6645
* Revert "fix" by [@RobertBoes](https://github.com/RobertBoes) in https://github.com/laravel/laravel/pull/6646
* Change composer post-autoload-dump script to Artisan command by [@lmjhs](https://github.com/lmjhs) in https://github.com/laravel/laravel/pull/6647

## [v12.2.0](https://github.com/laravel/laravel/compare/v12.1.0...v12.2.0) - 2025-07-11

* Add Vite 7 support by [@timacdonald](https://github.com/timacdonald) in https://github.com/laravel/laravel/pull/6639

## [v12.1.0](https://github.com/laravel/laravel/compare/v12.0.11...v12.1.0) - 2025-07-03

* [12.x] Disable nightwatch in testing by [@laserhybiz](https://github.com/laserhybiz) in https://github.com/laravel/laravel/pull/6632
* [12.x] Reorder environment variables in phpunit.xml for logical grouping by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6634
* Change to hyphenate prefixes and cookie names by [@u01jmg3](https://github.com/u01jmg3) in https://github.com/laravel/laravel/pull/6636
* [12.x] Fix type casting for environment variables in config files by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6637

## [v12.0.11](https://github.com/laravel/laravel/compare/v12.0.10...v12.0.11) - 2025-06-10

**Full Changelog**: https://github.com/laravel/laravel/compare/v12.0.10...v12.0.11

## [v12.0.10](https://github.com/laravel/laravel/compare/v12.0.9...v12.0.10) - 2025-06-09

* fix alphabetical order by [@Khuthaily](https://github.com/Khuthaily) in https://github.com/laravel/laravel/pull/6627
* [12.x] Reduce redundancy and keeps the .gitignore file cleaner by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6629
* [12.x] Fix: Add void return type to satisfy Rector analysis by [@Aluisio-Pires](https://github.com/Aluisio-Pires) in https://github.com/laravel/laravel/pull/6628

## [v12.0.9](https://github.com/laravel/laravel/compare/v12.0.8...v12.0.9) - 2025-05-26

* [12.x] Remove apc by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6611
* [12.x] Add JSON Schema to package.json by [@martinbean](https://github.com/martinbean) in https://github.com/laravel/laravel/pull/6613
* Minor language update by [@woganmay](https://github.com/woganmay) in https://github.com/laravel/laravel/pull/6615
* Enhance .gitignore to exclude common OS and log files by [@mohammadRezaei1380](https://github.com/mohammadRezaei1380) in https://github.com/laravel/laravel/pull/6619

## [v12.0.8](https://github.com/laravel/laravel/compare/v12.0.7...v12.0.8) - 2025-05-12

* [12.x] Clean up URL formatting in README by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6601

## [v12.0.7](https://github.com/laravel/laravel/compare/v12.0.6...v12.0.7) - 2025-04-15

* Add `composer run test` command by [@crynobone](https://github.com/crynobone) in https://github.com/laravel/laravel/pull/6598
* Partner Directory Changes in ReadME by [@joshcirre](https://github.com/joshcirre) in https://github.com/laravel/laravel/pull/6599

## [v12.0.6](https://github.com/laravel/laravel/compare/v12.0.5...v12.0.6) - 2025-04-08

**Full Changelog**: https://github.com/laravel/laravel/compare/v12.0.5...v12.0.6

## [v12.0.5](https://github.com/laravel/laravel/compare/v12.0.4...v12.0.5) - 2025-04-02

* [12.x] Update `config/mail.php` to match the latest core configuration by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6594

## [v12.0.4](https://github.com/laravel/laravel/compare/v12.0.3...v12.0.4) - 2025-03-31

* Bump vite from 6.0.11 to 6.2.3 - Vulnerability patch by [@abdel-aouby](https://github.com/abdel-aouby) in https://github.com/laravel/laravel/pull/6586
* Bump vite from 6.2.3 to 6.2.4 by [@thinkverse](https://github.com/thinkverse) in https://github.com/laravel/laravel/pull/6590

## [v12.0.3](https://github.com/laravel/laravel/compare/v12.0.2...v12.0.3) - 2025-03-17

* Remove reverted change from CHANGELOG.md by [@AJenbo](https://github.com/AJenbo) in https://github.com/laravel/laravel/pull/6565
* Improves clarity in app.css file by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6569
* [12.x] Refactor: Structural improvement for clarity by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6574
* Bump axios from 1.7.9 to 1.8.2 - Vulnerability patch by [@abdel-aouby](https://github.com/abdel-aouby) in https://github.com/laravel/laravel/pull/6572
* [12.x] Remove Unnecessarily [@source](https://github.com/source) by [@AhmedAlaa4611](https://github.com/AhmedAlaa4611) in https://github.com/laravel/laravel/pull/6584

## [v12.0.2](https://github.com/laravel/laravel/compare/v12.0.1...v12.0.2) - 2025-03-04

* Make the github test action run out of the box independent of the choice of testing framework by [@ndeblauw](https://github.com/ndeblauw) in https://github.com/laravel/laravel/pull/6555

## [v12.0.1](https://github.com/laravel/laravel/compare/v12.0.0...v12.0.1) - 2025-02-24

* [12.x] prefer stable stability by [@pataar](https://github.com/pataar) in https://github.com/laravel/laravel/pull/6548

## [v12.0.0 (2025-??-??)](https://github.com/laravel/laravel/compare/v11.0.2...v12.0.0)

Laravel 12 includes a variety of changes to the application skeleton. Please consult the diff to see what's new.
