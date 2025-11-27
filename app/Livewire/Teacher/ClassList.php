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
        // Obtenemos el nombre del usuario logueado (el docente)
        $teacherName = Auth::user()->name;

        // 1. Obtener TODAS las sesiones donde el instructor sea el usuario actual
        $allSessions = TrainingSession::where('instructor', $teacherName)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. Filtrar Próximas Clases:
        // Status es 'Programada' o 'En curso', Y la fecha es hoy o futura
        $this->upcomingClasses = $allSessions->filter(function ($session) {
            return ($session->status === 'Programada' || $session->status === 'En curso')
                && $session->date >= now()->format('Y-m-d');
        });

        // 3. Filtrar Clases Completadas:
        // Status es 'Completada', 'Cancelada' O la fecha ya pasó
        $this->completedClasses = $allSessions->filter(function ($session) {
            return $session->status === 'Completada'
                || $session->status === 'Cancelada'
                || ($session->status === 'Programada' && $session->date < now()->format('Y-m-d'));
        })->sortByDesc('date'); // Las completadas las mostramos de la más reciente a la más antigua
    }

    public function render()
    {
        return view('livewire.teacher.class-list');
    }

    // Helper para colores de estado
    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'Programada' => 'bg-blue-100 text-blue-700 border-blue-200',
            'En curso' => 'bg-amber-100 text-amber-700 border-amber-200',
            'Completada' => 'bg-green-100 text-green-700 border-green-200',
            'Cancelada' => 'bg-red-100 text-red-700 border-red-200',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
