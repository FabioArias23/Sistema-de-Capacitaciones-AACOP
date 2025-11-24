<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // El nombre de la tabla ahora es 'training_sessions'
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->string('training_title');
            $table->string('campus_name');
            $table->string('instructor');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('capacity');
            $table->integer('registered')->default(0);
            $table->enum('status', ['Programada', 'En curso', 'Completada', 'Cancelada'])->default('Programada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
