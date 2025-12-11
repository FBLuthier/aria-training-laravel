<?php

namespace App\Data;

use App\Enums\UserRole;

readonly class UserData
{
    public function __construct(
        public string $usuario,
        public string $correo,
        public string $nombre_1,
        public string $apellido_1,
        public UserRole $tipo_usuario_id,
        public ?string $nombre_2 = null,
        public ?string $apellido_2 = null,
        public ?string $telefono = null,
        public ?string $fecha_nacimiento = null,
        public ?int $entrenador_id = null,
        public int $estado = 1,
        public ?string $contrasena = null,
        public ?string $profile_photo_path = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            usuario: $data['usuario'],
            correo: $data['correo'],
            nombre_1: $data['nombre_1'],
            apellido_1: $data['apellido_1'],
            tipo_usuario_id: $data['tipo_usuario_id'] instanceof UserRole
                ? $data['tipo_usuario_id']
                : UserRole::from($data['tipo_usuario_id']),
            nombre_2: $data['nombre_2'] ?? null,
            apellido_2: $data['apellido_2'] ?? null,
            telefono: $data['telefono'] ?? null,
            fecha_nacimiento: $data['fecha_nacimiento'] ?? null,
            entrenador_id: $data['entrenador_id'] ?? null,
            estado: $data['estado'] ?? 1,
            contrasena: $data['contrasena'] ?? null,
            profile_photo_path: $data['profile_photo_path'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'usuario' => $this->usuario,
            'correo' => $this->correo,
            'nombre_1' => $this->nombre_1,
            'apellido_1' => $this->apellido_1,
            'tipo_usuario_id' => $this->tipo_usuario_id->value,
            'nombre_2' => $this->nombre_2,
            'apellido_2' => $this->apellido_2,
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'entrenador_id' => $this->entrenador_id,
            'estado' => $this->estado,
            'contrasena' => $this->contrasena,
            'profile_photo_path' => $this->profile_photo_path,
        ], fn ($value) => ! is_null($value));
    }
}
