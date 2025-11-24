<?php

namespace App\Livewire\Teacher;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ClassList extends Component
{
    public $upcomingClasses;
    public $completedClasses;

    public function mount()
    {
        $teacherName = Auth::user()->name;

        // Obtenemos todas las sesiones asignadas a este docente
        $allClasses = TrainingSession::where('instructor', $teacherName)
            ->orderBy('date', 'desc')
            ->get();

        // Filtramos las clases en "próximas" y "completadas"
        $this->upcomingClasses = $allClasses->filter(function ($class) {
            return $class->status === 'Programada' || $class->status === 'En curso';
        });

        $this->completedClasses = $allClasses->filter(function ($class) {
            return $class->status === 'Completada' || $class->status === 'Cancelada';
        });
    }

    public function render()
    {
        return view('livewire.teacher.class-list');
    }

    // Función de ayuda para los colores de los badges
    public function getStatusBadgeClass(string $status): string
    {
        return [
            'Programada' => 'bg-[#38C0E3]/10 text-[#38C0E3] dark:bg-[#38C0E3]/20',
            'En curso' => 'bg-[#FFD700]/10 text-[#B8860B] dark:bg-[#FFD700]/20 dark:text-[#FFD700]',
            'Completada' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'Cancelada' => 'bg-muted text-muted-foreground',
        ][$status] ?? 'bg-muted text-muted-foreground';
    }
}
