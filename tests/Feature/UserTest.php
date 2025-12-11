<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UserTest extends TestCase
{
    // Usamos DatabaseTransactions para no borrar la BD, solo rollback
    use DatabaseTransactions;

    public function test_admin_can_create_user()
    {
        // 1. Arrange: Crear Admin
        Queue::fake();

        $admin = User::factory()->create([
            'tipo_usuario_id' => UserRole::Admin,
            'usuario' => 'adm'.rand(100, 999),
            'correo' => 'admin_'.time().'@test.com',
        ]);

        // 2. Act: Livewire request o Action directa
        // Probamos la Action directamente para aislar la lógica
        $action = new \App\Actions\Users\CreateUserAction;
        $userData = \App\Data\UserData::fromArray([
            'usuario' => 'usr'.rand(100, 999),
            'correo' => 'newuser_'.time().'@test.com',
            'nombre_1' => 'Test',
            'apellido_1' => 'User',
            'tipo_usuario_id' => UserRole::Atleta->value,
            'telefono' => '1234567890',
            'fecha_nacimiento' => '2000-01-01',
        ]);

        $newUser = $action->execute($userData);

        // 3. Assert
        $this->assertDatabaseHas('usuarios', [
            'usuario' => $userData->usuario,
            'tipo_usuario_id' => UserRole::Atleta->value,
        ]);

        // Verificar que se despachó el Job de correo
        Queue::assertPushed(SendWelcomeEmail::class);
    }

    public function test_trainer_can_only_see_own_athletes()
    {
        // 1. Arrange
        $trainer = User::factory()->create([
            'tipo_usuario_id' => UserRole::Entrenador,
            'usuario' => 'tr'.rand(100, 999),
        ]);
        $otherTrainer = User::factory()->create([
            'tipo_usuario_id' => UserRole::Entrenador,
            'usuario' => 'otr'.rand(100, 999),
        ]);

        $myAthlete = User::factory()->create([
            'tipo_usuario_id' => UserRole::Atleta,
            'entrenador_id' => $trainer->id,
            'usuario' => 'ath'.rand(100, 999),
        ]);

        $otherAthlete = User::factory()->create([
            'tipo_usuario_id' => UserRole::Atleta,
            'entrenador_id' => $otherTrainer->id,
            'usuario' => 'oath'.rand(100, 999),
        ]);

        // 2. Act: Usar el Query Builder
        $visibleUsers = User::query()->visibleTo($trainer)->get();

        // 3. Assert
        $this->assertTrue($visibleUsers->contains($myAthlete));
        $this->assertFalse($visibleUsers->contains($otherAthlete));
    }

    public function test_user_observer_logs_creation()
    {
        // 1. Arrange
        $admin = User::factory()->create([
            'tipo_usuario_id' => UserRole::Admin,
            'usuario' => 'adm'.rand(100, 999),
        ]);
        $this->actingAs($admin); // Necesario para que el Observer registre el user_id

        // 2. Act
        $user = User::factory()->create(['usuario' => 'aud'.rand(100, 999)]);

        // 3. Assert
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'created',
            'model_type' => User::class,
            'model_id' => $user->id,
            'user_id' => $admin->id,
        ]);
    }
}
