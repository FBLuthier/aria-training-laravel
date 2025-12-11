<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $password
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulación de envío de correo
        // En producción, aquí usarías Mail::to($this->user)->send(new WelcomeEmail($this->user));

        Log::info("Enviando correo de bienvenida a {$this->user->email} con contraseña temporal: {$this->password}");

        // Simular retardo de red
        sleep(2);

        Log::info("Correo enviado exitosamente a {$this->user->email}");
    }
}
