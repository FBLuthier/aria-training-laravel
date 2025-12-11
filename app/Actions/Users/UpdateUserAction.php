<?php

namespace App\Actions\Users;

use App\Data\UserData;
use App\Models\User;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        // Aquí se podría agregar lógica adicional antes de actualizar
        $user->update($data->toArray());

        return $user;
    }
}
