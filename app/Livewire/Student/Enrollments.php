<?php

namespace App\Livewire\Student;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Enrollments extends Component
{
    public $enrollments;

    public function mount()
    {
        $this->loadEnrollments();
    }

    public function loadEnrollments()
    {
        // Cargamos todas las inscripciones del usuario actual,
        // incluyendo la información de la sesión de capacitación.
        $this->enrollments = Enrollment::where('user_id', Auth::id())
            ->with('trainingSession')
            ->get()
            ->sortByDesc('trainingSession.date'); // Ordenamos por fecha de la sesión
    }

    public function render()
    {
        return view('livewire.student.enrollments');
    }

    // Acción para cancelar una inscripción
    public function unenroll(Enrollment $enrollment)
    {
        // Por seguridad, verificamos que la inscripción pertenezca al usuario actual
        // y que el estado sea 'Inscrito' (no puede desinscribirse de un curso ya iniciado o completado)
        if ($enrollment->user_id !== Auth::id() || $enrollment->status !== 'Inscrito') {
            session()->flash('error', 'No puedes desinscribirte de esta capacitación.');
            return;
        }

        // Decrementamos el contador de inscritos en la sesión
        $enrollment->trainingSession->decrement('registered');

        // Eliminamos la inscripción
        $enrollment->delete();

        // Recargamos la lista de inscripciones para que el cambio se refleje en la vista
        $this->loadEnrollments();

        session()->flash('success', 'Te has desinscrito correctamente.');
    }

    // Función de ayuda para los colores de los badges de estado
    public function getStatusBadgeClass(string $status): string
    {
        return [
            'En progreso' => 'bg-[#FFD700]/10 text-[#B8860B] dark:bg-[#FFD700]/20 dark:text-[#FFD700]',
            'Completado' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'Aprobado' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'Reprobado' => 'bg-[#ED1C24]/10 text-[#ED1C24] dark:bg-[#ED1C24]/20',
            'Inscrito' => 'bg-[#38C0E3]/10 text-[#38C0E3] dark:bg-[#38C0E3]/20',
        ][$status] ?? 'bg-muted text-muted-foreground';
    }
}
