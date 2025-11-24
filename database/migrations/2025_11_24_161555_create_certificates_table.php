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
    Schema::create('certificates', function (Blueprint $table) {
        $table->id();

        // Un número de certificado único y legible
        $table->string('certificate_number')->unique();

        // Relaciones
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('training_id')->constrained()->onDelete('cascade');
        $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');

        // Datos "congelados" al momento de la emisión
        $table->string('student_name');
        $table->string('training_title');
        $table->string('instructor_name');
        $table->date('completion_date');
        $table->unsignedTinyInteger('grade');

        $table->timestamps(); // Para saber cuándo se generó el certificado
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
