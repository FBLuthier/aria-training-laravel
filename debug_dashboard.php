$user = App\Models\User::where('nombre_1', 'like', '%fernando%')->first();
echo "User: " . $user->id . " - " . $user->nombre_1 . "\n";

$rutina = App\Models\Rutina::where('atleta_id', $user->id)->first();
if ($rutina) {
    echo "Rutina: " . $rutina->id . " - " . $rutina->nombre . " (Estado: " . $rutina->estado . ")\n";
    $dias = $rutina->dias()->whereDate('fecha', '2025-12-10')->get();
    echo "Dias hoy (2025-12-10): " . $dias->count() . "\n";
    foreach($dias as $d) {
        echo " - Dia ID: " . $d->id . " Name: " . $d->nombre_dia . "\n";
    }
    
    // Check all days to see if dates are set
    $allDays = $rutina->dias()->get();
    echo "Total days in routine: " . $allDays->count() . "\n";
    foreach($allDays as $d) {
        echo " - ID: " . $d->id . " Date: " . ($d->fecha ? $d->fecha->format('Y-m-d') : 'NULL') . "\n";
    }

} else {
    echo "No routine found for athlete_id: " . $user->id . "\n";
}
