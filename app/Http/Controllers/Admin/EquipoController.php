<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo; // <-- 1. Importamos nuestro modelo
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 2. Obtenemos todos los registros de la tabla 'equipos'
        $equipos = Equipo::all();

        // 3. Devolvemos una vista y le pasamos los datos que obtuvimos
        return view('admin.equipos.index', compact('equipos'));
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.equipos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // 1. Validar la petición
    $request->validate([
        'nombre' => ['required', 'string', 'max:45', 'unique:equipos'],
    ]);

    // 2. Crear el equipo (usando el modelo)
    Equipo::create([
        'nombre' => $request->nombre,
    ]);

    // 3. Redirigir al usuario a la lista de equipos
    return redirect()->route('admin.equipos.index');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        return view('admin.equipos.edit', compact('equipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:45', 'unique:equipos,nombre,' . $equipo->id],
        ]);

        $equipo->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('admin.equipos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return redirect()->route('admin.equipos.index');
    }
}
