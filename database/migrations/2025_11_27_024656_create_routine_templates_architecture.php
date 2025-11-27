<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Crear tabla de Plantillas de Días (Banco de Días)
        Schema::create('plantillas_dias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // Ej: "Pierna Hipertrofia A"
            $table->foreignId('usuario_id')->constrained('usuarios'); // Dueño (Atleta)
            $table->foreignId('entrenador_id')->nullable()->constrained('usuarios'); // Creador
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Crear tabla de Ejercicios de Plantilla
        Schema::create('plantilla_dia_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantilla_dia_id')->constrained('plantillas_dias')->onDelete('cascade');
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->onDelete('cascade');
            $table->integer('series')->default(3);
            $table->string('repeticiones', 50)->nullable(); // Ej: "12-15"
            $table->string('peso_sugerido', 50)->nullable();
            $table->integer('descanso_segundos')->nullable();
            $table->text('indicaciones')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // 3. Modificar tabla rutina_dias para soportar calendario y origen
        Schema::table('rutina_dias', function (Blueprint $table) {
            $table->foreignId('plantilla_dia_id')->nullable()->constrained('plantillas_dias')->onDelete('set null');
            $table->date('fecha')->nullable()->after('nombre_dia'); // Para agendar en calendario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutina_dias', function (Blueprint $table) {
            $table->dropForeign(['plantilla_dia_id']);
            $table->dropColumn(['plantilla_dia_id', 'fecha']);
        });

        Schema::dropIfExists('plantilla_dia_ejercicios');
        Schema::dropIfExists('plantillas_dias');
    }
};
