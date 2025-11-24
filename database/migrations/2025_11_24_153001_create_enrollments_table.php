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
    Schema::create('enrollments', function (Blueprint $table) {
        $table->id();

        // Vincula la inscripción con un usuario (el participante)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Vincula la inscripción con una sesión de capacitación
        $table->foreignId('training_session_id')->constrained()->onDelete('cascade');

        // Estado de la inscripción
        $table->enum('status', ['Inscrito', 'En progreso', 'Aprobado', 'Reprobado', 'Cancelado'])->default('Inscrito');

        // Métricas de progreso
        $table->unsignedTinyInteger('attendance')->default(0); // Porcentaje de 0 a 100
        $table->unsignedTinyInteger('grade')->nullable(); // Nota final de 0 a 100

        $table->timestamps();

        // Asegurarse de que un usuario no pueda inscribirse dos veces en la misma sesión
        $table->unique(['user_id', 'training_session_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
