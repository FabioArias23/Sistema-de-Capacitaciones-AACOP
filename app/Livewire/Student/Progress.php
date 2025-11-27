<?php

namespace App\Livewire\Student;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Progress extends Component
{
    public $averageGrade = 0;
    public $averageAttendance = 0;
    public $completedCourses = 0;
    public $progressHistory = [];

    public function mount()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('trainingSession.training')
            ->get();

        $this->completedCourses = $enrollments->whereIn('status', ['Aprobado', 'Reprobado', 'Completado'])->count();

        $enrollmentsWithGrades = $enrollments->whereNotNull('grade');

        if ($enrollmentsWithGrades->isNotEmpty()) {
            // CAMBIO: round(..., 1) mantiene 1 decimal (ej: 8.5)
            $this->averageGrade = round($enrollmentsWithGrades->avg('grade'), 1);
        }

        if ($enrollments->isNotEmpty()) {
            $this->averageAttendance = round($enrollments->avg('attendance'));
        }

        $this->progressHistory = $enrollments->sortByDesc('trainingSession.date');
    }

    public function render()
    {
        return view('livewire.student.progress');
    }
}
