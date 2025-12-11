<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usuario' => $this->usuario,
            'nombre_completo' => trim($this->nombre_1 . ' ' . $this->apellido_1),
            'correo' => $this->correo,
            'rol' => [
                'id' => $this->tipo_usuario_id->value,
                'nombre' => $this->tipo_usuario_id->name,
            ],
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento ? $this->fecha_nacimiento->format('Y-m-d') : null,
            'estado' => $this->deleted_at ? 'inactivo' : 'activo',
            'creado_en' => $this->created_at->toIso8601String(),
            'actualizado_en' => $this->updated_at->toIso8601String(),
        ];
    }
}
