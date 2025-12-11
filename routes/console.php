<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Rutas de Consola (Artisan Commands)
|--------------------------------------------------------------------------
|
| Este archivo permite definir comandos de consola basados en closures
| (funciones anónimas) que se pueden ejecutar desde Artisan.
|
| Es útil para comandos simples y rápidos. Para comandos más complejos,
| es mejor crear clases de comando en app/Console/Commands/.
|
| EJECUTAR:
| php artisan inspire
|
*/

// =======================================================================
//  COMANDO: INSPIRE
// =======================================================================

/**
 * Comando de ejemplo que muestra una cita inspiradora.
 *
 * Este es un comando simple incluido por defecto en Laravel
 * que demuestra cómo crear comandos basados en closures.
 *
 * USO:
 * php artisan inspire
 *
 * SALIDA:
 * Muestra una cita aleatoria inspiradora en la consola.
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
