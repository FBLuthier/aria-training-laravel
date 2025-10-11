<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo; //
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreEquipoRequest;
use App\Http\Requests\Admin\UpdateEquipoRequest;


class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('search');

        $equipos = Equipo::query()
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('nombre', 'like', "%{$busqueda}%");
            })
            // --- INICIO: CÓDIGO ORDENAMIENTO ---
            ->when($request->input('sort'), function ($query, $sort) use ($request) {
                $direction = $request->input('direction', 'asc');
                return $query->orderBy($sort, $direction);
            })
            ->paginate(20)
            ->withQueryString();

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
    public function store(StoreEquipoRequest $request)
    {
    // 2. Crear el equipo (usando el modelo)
    Equipo::create($request->validated());

    // 3. Redirigir al usuario a la lista de equipos
    return redirect()->route('admin.equipos.index')->with('status', '¡Equipo creado con éxito!');
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
    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        $equipo->update($request->validated());

        return redirect()->route('admin.equipos.index')->with('status', '¡Equipo actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

    return redirect()->route('admin.equipos.index')->with('status', '¡Equipo eliminado con éxito!');

    }

    public function trash()
    {
        $equipos = Equipo::onlyTrashed()->paginate(10);
        return view('admin.equipos.trash', compact('equipos'));
    }


    public function restore($id)
    {
        $equipo = Equipo::withTrashed()->findOrFail($id);
        $equipo->restore();
        return redirect()->route('admin.equipos.trash')->with('status', '¡Equipo restaurado con éxito!');
    }
    

    public function forceDelete($id)
    {
        $equipo = Equipo::withTrashed()->findOrFail($id);
        $equipo->forceDelete();
        return redirect()->route('admin.equipos.trash')->with('status', '¡Equipo eliminado permanentemente!');
    }
}
