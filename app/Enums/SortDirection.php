<?php

namespace App\Enums;

enum SortDirection: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    /**
     * Devuelve la dirección de ordenación opuesta.
     */
    public function opposite(): self
    {
        return match ($this) {
            self::ASC => self::DESC,
            self::DESC => self::ASC,
        };
    }
}
