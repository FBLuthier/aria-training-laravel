<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'usuario' => ['required', 'string', 'lowercase', 'between:3,15', 'unique:usuarios', 'regex:/^\S*$/'],
            'contrasena' => ['required','confirmed',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(), // Opcional: comprueba si la contraseña ha sido filtrada
            ],
            'correo' => ['required', 'string', 'lowercase', 'email', 'max:45', 'unique:usuarios', 'before_or_equal:' . now()->subYears(8)->format('Y-m-d')],
            'nombre_1' => ['required', 'string', 'alpha', 'max:15'],
            'nombre_2' => ['nullable', 'string', 'alpha', 'max:15'], // Regla para campo opcional
            'apellido_1' => ['required', 'string', 'alpha', 'max:15'],
            'apellido_2' => ['nullable', 'string', 'alpha', 'max:15'], // Regla para campo opcional
            'telefono' => ['required', 'numeric', 'digits_between:7,15'],
            'fecha_nacimiento' => ['required', 'date'],
            
            
        ]);

        $user = User::create([
            'usuario' => $request->usuario,
            'contrasena' => Hash::make($request->contrasena),
            'correo'=> $request-> correo,
            'nombre_1' => $request->nombre_1,
            'nombre_2' => $request->nombre_2,
            'apellido_1' => $request->apellido_1,
            'apellido_2' => $request->apellido_2,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,

            // --- VALORES POR DEFECTO ---
            'estado' => 1, // 1 = Activo
            'tipo_usuario_id' => 3, // Asumimos ID 3 = Atleta
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}