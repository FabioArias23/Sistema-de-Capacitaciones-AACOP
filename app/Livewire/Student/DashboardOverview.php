<?php

namespace App\Livewire\Student;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardOverview extends Component
{
    public array $metrics = [];
    public $upcomingEnrollments;

    public function mount()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())->with('trainingSession')->get();

        // Obtenemos las próximas 2 inscripciones
        $this->upcomingEnrollments = $enrollments
            ->where('trainingSession.date', '>=', now()->startOfDay())
            ->sortBy('trainingSession.date')
            ->take(2);

        // Calculamos las métricas
        $completedCount = $enrollments->whereIn('status', ['Aprobado', 'Reprobado'])->count();
        $averageGrade = $enrollments->whereNotNull('grade')->avg('grade');

        $this->metrics = [
            ['label' => 'Cursos Inscritos', 'value' => $enrollments->count(), 'color' => 'text-primary'],
            ['label' => 'Completados', 'value' => $completedCount, 'color' => 'text-[#00A885]'],
            ['label' => 'Promedio', 'value' => $averageGrade ? round($averageGrade) . '%' : 'N/A', 'color' => 'text-[#FFD700]'],
        ];
    }

    public function render()
    {
        return view('livewire.student.dashboard-overview');
    }
}
