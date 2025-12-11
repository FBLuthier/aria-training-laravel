<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    public function impersonate($userId)
    {
        $user = User::findOrFail($userId);
        
        // Seguridad: Solo admins pueden impersonar
        if (!auth()->user()->esAdmin()) {
            abort(403);
        }

        // Seguridad: No impersonar a otros admins (opcional, pero recomendado)
        if ($user->esAdmin()) {
            return back()->with('error', 'No puedes impersonar a otro administrador.');
        }

        // Guardar ID original
        Session::put('impersonator_id', auth()->id());

        // Loguear como el usuario objetivo
        Auth::login($user);

        return redirect('/dashboard')->with('status', "Has iniciado sesión como {$user->nombre_1}");
    }

    public function stop()
    {
        if (!Session::has('impersonator_id')) {
            return redirect('/dashboard');
        }

        $adminId = Session::get('impersonator_id');
        
        // Limpiar sesión
        Session::forget('impersonator_id');

        // Reloguear como admin
        Auth::loginUsingId($adminId);

        return redirect()->route('admin.usuarios.index')->with('status', 'Has vuelto a tu cuenta de Administrador.');
    }
}
