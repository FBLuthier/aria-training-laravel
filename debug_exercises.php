<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$diaId = 21;
$dia = App\Models\RutinaDia::find($diaId);

if (!$dia) {
    echo "RutinaDia ID $diaId not found.\n";
    exit;
}

echo "RutinaDia Found: " . $dia->nombre_dia . " (ID: " . $dia->id . ")\n";
echo "Fecha: " . $dia->fecha . "\n";
echo "Rutina Parent ID: " . $dia->rutina_id . "\n";

$ejercicios = $dia->rutinaEjercicios()->with('ejercicio')->orderBy('orden_en_dia')->get();

echo "Checking for orphaned exercises:\n";
foreach ($ejercicios as $re) {
    $bloqueId = $re->rutina_bloque_id;
    $status = "OK";
    if ($bloqueId) {
        $bloqueExists = App\Models\RutinaBloque::find($bloqueId);
        if (!$bloqueExists) {
            $status = "ORPHAN (Block $bloqueId missing)";
            // Uncomment to fix
            $re->delete(); 
            echo "Deleted orphaned exercise.\n";
        }
    }
    echo "- [" . $re->id . "] " . $re->ejercicio->nombre . " | Bloque: " . ($bloqueId ?? 'NULL') . " | Status: $status\n";
}
