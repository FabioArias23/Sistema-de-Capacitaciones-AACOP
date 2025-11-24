<?php

namespace App\Livewire\Student;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Progress extends Component
{
    // Métricas para las tarjetas
    public $averageGrade = 0;
    public $averageAttendance = 0;
    public $completedCourses = 0;

    // Historial de cursos
    public $progressHistory = [];

    public function mount()
    {
        // Obtenemos todas las inscripciones del usuario con sus relaciones
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('trainingSession.training')
            ->get();

        // --- Cálculos para las tarjetas de resumen ---

        // Cursos completados (Aprobados o Reprobados)
        $this->completedCourses = $enrollments->whereIn('status', ['Aprobado', 'Reprobado'])->count();

        // Promedio de notas (solo de los cursos que tienen nota)
        $enrollmentsWithGrades = $enrollments->whereNotNull('grade');
        if ($enrollmentsWithGrades->isNotEmpty()) {
            $this->averageGrade = round($enrollmentsWithGrades->avg('grade'));
        }

        // Promedio de asistencia (de todos los cursos inscritos)
        if ($enrollments->isNotEmpty()) {
            $this->averageAttendance = round($enrollments->avg('attendance'));
        }

        // Asignamos el historial para la vista
        $this->progressHistory = $enrollments->sortByDesc('trainingSession.date');
    }

    public function render()
    {
        return view('livewire.student.progress');
    }

    // Función de ayuda para los badges de estado
    public function getStatusBadgeClass(string $status): string
    {
        return [
            'Aprobado' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'Reprobado' => 'bg-[#ED1C24]/10 text-[#ED1C24] dark:bg-[#ED1C24]/20',
            'En progreso' => 'bg-[#FFD700]/10 text-[#B8860B] dark:bg-[#FFD700]/20 dark:text-[#FFD700]',
            'Inscrito' => 'bg-[#38C0E3]/10 text-[#38C0E3] dark:bg-[#38C0E3]/20',
        ][$status] ?? 'bg-muted text-muted-foreground';
    }
}
