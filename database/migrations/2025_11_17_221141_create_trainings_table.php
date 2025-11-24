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
    Schema::create('trainings', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('category');
        $table->string('duration');
        $table->integer('capacity');
        $table->enum('level', ['Básico', 'Intermedio', 'Avanzado']);
        $table->string('instructor');
        $table->string('status')->default('Activo'); // 'Activo' o 'Inactivo'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
