<?php

namespace App\Actions\Users;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(UserData $data): User
    {
        $attributes = $data->toArray();

        // Hash de contraseña si no viene hasheada
        if (isset($attributes['contrasena'])) {
            $attributes['contrasena'] = Hash::make($attributes['contrasena']);
        } else {
            $attributes['contrasena'] = Hash::make('password'); // Default password
        }

        $user = User::create($attributes);

        // Enviar correo de bienvenida en segundo plano
        // Pasamos 'password' hardcoded porque es la temporal por defecto
        \App\Jobs\SendWelcomeEmail::dispatch($user, 'password');

        return $user;
    }
}
