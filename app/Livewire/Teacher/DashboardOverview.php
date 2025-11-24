<?php

namespace App\Livewire\Teacher;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardOverview extends Component
{
    public array $metrics = [];
    public $upcomingClasses;

    public function mount()
    {
        $teacherName = Auth::user()->name;

        // Obtenemos todas las sesiones asignadas al docente
        $allClasses = TrainingSession::where('instructor', $teacherName)->get();

        // Obtenemos las próximas 3 clases
        $this->upcomingClasses = $allClasses
            ->where('status', 'Programada')
            ->sortBy('date')
            ->take(3);

        // Calculamos las métricas
        $totalStudents = $allClasses->sum('registered');

        $this->metrics = [
            ['label' => 'Clases Asignadas', 'value' => $allClasses->count(), 'color' => 'text-primary'],
            ['label' => 'Estudiantes Totales', 'value' => $totalStudents, 'color' => 'text-[#00A885]'],
            ['label' => 'Próximas Clases', 'value' => $this->upcomingClasses->count(), 'color' => 'text-[#FFD700]'],
        ];
    }

    public function render()
    {
        return view('livewire.teacher.dashboard-overview');
    }
}
