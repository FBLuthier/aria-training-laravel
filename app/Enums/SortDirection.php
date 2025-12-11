<?php

namespace App\Enums;

/**
 * Enum para las direcciones de ordenamiento.
 * Proporciona una forma type-safe de manejar el ordenamiento ASC/DESC.
 */
enum SortDirection: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    /**
     * Retorna la dirección opuesta.
     * Si es ASC retorna DESC, y viceversa.
     */
    public function opposite(): self
    {
        return match ($this) {
            self::ASC => self::DESC,
            self::DESC => self::ASC,
        };
    }

    /**
     * Retorna el ícono correspondiente para mostrar en la UI.
     */
    public function icon(): string
    {
        return match ($this) {
            self::ASC => '↑',
            self::DESC => '↓',
        };
    }
}
