<?php

namespace App\Enums;

enum UserRole: int
{
    case Admin = 1;
    case Entrenador = 2;
    case Atleta = 3;

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Entrenador => 'Entrenador',
            self::Atleta => 'Atleta',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Admin => 'red',
            self::Entrenador => 'green',
            self::Atleta => 'blue',
        };
    }
}
