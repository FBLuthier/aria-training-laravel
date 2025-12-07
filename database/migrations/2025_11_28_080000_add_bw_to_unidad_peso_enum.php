<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar columna en rutina_ejercicios
        DB::statement("ALTER TABLE rutina_ejercicios MODIFY COLUMN unidad_peso ENUM('kg', 'lbs', 'bw') DEFAULT 'kg'");
        
        // Modificar columna en plantilla_dia_ejercicios
        DB::statement("ALTER TABLE plantilla_dia_ejercicios MODIFY COLUMN unidad_peso ENUM('kg', 'lbs', 'bw') DEFAULT 'kg'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a solo kg y lbs (Cuidado: esto fallará si hay datos 'bw', pero es aceptable para down)
        DB::statement("ALTER TABLE rutina_ejercicios MODIFY COLUMN unidad_peso ENUM('kg', 'lbs') DEFAULT 'kg'");
        DB::statement("ALTER TABLE plantilla_dia_ejercicios MODIFY COLUMN unidad_peso ENUM('kg', 'lbs') DEFAULT 'kg'");
    }
};
