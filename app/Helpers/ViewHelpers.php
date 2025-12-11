<?php

/**
 * =======================================================================
 * HELPERS GLOBALES PARA VISTAS Y COMPONENTES
 * =======================================================================
 *
 * Este archivo contiene funciones helper reutilizables en todo el sistema.
 * Están diseñadas para mantener consistencia en el formateo y presentación.
 *
 * CATEGORÍAS:
 * - Formateo de fechas (formatDate, formatDateTime, formatDateTimeFull)
 * - Nombres de modelos (modelName, modelNamePlural, modelTitle)
 * - Mensajes (successMessage, errorMessage, confirmMessage)
 * - UI Components (statusBadge, yesNoBadge)
 * - Utilidades de texto (truncate)
 *
 * REGISTRO:
 * Estas funciones se cargan automáticamente en composer.json:
 * "files": ["app/Helpers/ViewHelpers.php"]
 *
 * USO:
 * Puedes usar estas funciones en cualquier parte del código:
 * - Vistas Blade: {{ formatDate($equipo->created_at) }}
 * - Controllers: return successMessage('creado', $equipo);
 * - Components: $badge = statusBadge($model);
 *
 * @since 1.6
 */

// =======================================================================
//  FORMATEO DE FECHAS
// =======================================================================

if (! function_exists('formatDate')) {
    /**
     * Formatea una fecha al formato español corto (dd/mm/yyyy).
     *
     * Ejemplos:
     * - formatDate('2025-10-17') → '17/10/2025'
     * - formatDate($model->created_at) → '17/10/2025'
     * - formatDate(null) → '-'
     *
     * @param  \Carbon\Carbon|string|null  $date  Fecha a formatear
     * @return string Fecha formateada o '-' si es null
     */
    function formatDate($date): string
    {
        // Si no hay fecha, retornar guion
        if (! $date) {
            return '-';
        }

        // Convertir a Carbon y formatear
        return \Carbon\Carbon::parse($date)->format('d/m/Y');
    }
}

if (! function_exists('formatDateTime')) {
    /**
     * Formatea una fecha y hora al formato español.
     *
     * @param  \Carbon\Carbon|string|null  $datetime
     */
    function formatDateTime($datetime): string
    {
        if (! $datetime) {
            return '-';
        }

        return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i');
    }
}

if (! function_exists('formatDateTimeFull')) {
    /**
     * Formatea una fecha y hora al formato español completo con segundos.
     *
     * @param  \Carbon\Carbon|string|null  $datetime
     */
    function formatDateTimeFull($datetime): string
    {
        if (! $datetime) {
            return '-';
        }

        return \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i:s');
    }
}

if (! function_exists('modelName')) {
    /**
     * Convierte el nombre de clase del modelo a minúsculas.
     *
     * @param  string|object  $model
     */
    function modelName($model): string
    {
        $className = is_object($model) ? class_basename($model) : $model;

        return strtolower($className);
    }
}

if (! function_exists('modelNamePlural')) {
    /**
     * Convierte el nombre de clase del modelo a plural en minúsculas.
     * Reglas simples de pluralización en español.
     *
     * @param  string|object  $model
     */
    function modelNamePlural($model): string
    {
        $singular = modelName($model);

        // Reglas simples de pluralización en español
        if (str_ends_with($singular, 'z')) {
            return substr($singular, 0, -1).'ces';
        }

        if (str_ends_with($singular, ['a', 'e', 'i', 'o', 'u'])) {
            return $singular.'s';
        }

        return $singular.'es';
    }
}

if (! function_exists('modelTitle')) {
    /**
     * Convierte el nombre de clase del modelo a título capitalizado.
     *
     * @param  string|object  $model
     */
    function modelTitle($model): string
    {
        $className = is_object($model) ? class_basename($model) : $model;

        return ucfirst($className);
    }
}

if (! function_exists('successMessage')) {
    /**
     * Genera un mensaje de éxito consistente.
     *
     * @param  string  $action  ('creado', 'actualizado', 'eliminado', 'restaurado')
     * @param  string|object  $model
     */
    function successMessage(string $action, $model): string
    {
        $modelTitle = modelTitle($model);

        return "{$modelTitle} {$action} exitosamente.";
    }
}

if (! function_exists('errorMessage')) {
    /**
     * Genera un mensaje de error consistente.
     *
     * @param  string|object  $model
     */
    function errorMessage(string $action, $model): string
    {
        $modelTitle = modelTitle($model);

        return "Error al {$action} el {$modelName}.";
    }
}

if (! function_exists('confirmMessage')) {
    /**
     * Genera un mensaje de confirmación consistente.
     *
     * @param  string|object  $model
     */
    function confirmMessage(string $action, $model): string
    {
        $modelName = modelName($model);

        return "¿Estás seguro de que deseas {$action} este {$modelName}?";
    }
}

if (! function_exists('statusBadge')) {
    /**
     * Genera un badge HTML para el estado de un modelo.
     *
     * @param  mixed  $model
     */
    function statusBadge($model): string
    {
        if (method_exists($model, 'trashed') && $model->trashed()) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Eliminado</span>';
        }

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Activo</span>';
    }
}

if (! function_exists('yesNoBadge')) {
    /**
     * Genera un badge HTML para valores booleanos.
     */
    function yesNoBadge(bool $value, string $yesText = 'Sí', string $noText = 'No'): string
    {
        if ($value) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">'.$yesText.'</span>';
        }

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">'.$noText.'</span>';
    }
}

if (! function_exists('truncate')) {
    /**
     * Trunca un texto a una longitud específica.
     */
    function truncate(?string $text, int $length = 50, string $suffix = '...'): string
    {
        if (! $text || strlen($text) <= $length) {
            return $text ?? '';
        }

        return substr($text, 0, $length).$suffix;
    }
}
