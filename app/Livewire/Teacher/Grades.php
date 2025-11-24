<?php

namespace App\Livewire\Teacher;

use App\Models\Enrollment;
use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Grades extends Component
{
    public $selectedSessionId;
    public $enrollments = [];
    public $gradesData = [];

    public function render()
    {
        // El docente solo puede calificar sus propias clases
        $sessions = TrainingSession::where('instructor', Auth::user()->name)
            ->where('date', '<=', now()) // Solo sesiones que ya han comenzado
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.teacher.grades', [
            'sessions' => $sessions,
        ]);
    }

    // Se ejecuta cuando el docente selecciona una sesión
    public function updatedSelectedSessionId($sessionId)
    {
        if (empty($sessionId)) {
            $this->reset(['enrollments', 'gradesData']);
            return;
        }

        $this->enrollments = Enrollment::where('training_session_id', $sessionId)
            ->with('user')
            ->get();

        // Preparamos el array con los datos para los inputs del formulario
        $this->gradesData = $this->enrollments->mapWithKeys(function ($enrollment) {
            return [$enrollment->id => [
                'grade' => $enrollment->grade,
            ]];
        })->toArray();
    }

    public function save()
    {
        $this->validate([
            'gradesData.*.grade' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($this->gradesData as $enrollmentId => $data) {
            $enrollment = Enrollment::find($enrollmentId);
            if ($enrollment) {
                $grade = $data['grade'] ?: null;
                $status = $enrollment->status; // Mantenemos el estado actual por defecto

                // Solo actualizamos el estado si se ha introducido una nota
                if ($grade !== null) {
                    $status = $grade >= 70 ? 'Aprobado' : 'Reprobado';
                }

                $enrollment->update([
                    'grade' => $grade,
                    'status' => $status,
                ]);
            }
        }

        session()->flash('success', '¡Calificaciones guardadas correctamente!');

        // Recargamos los datos para que la vista refleje el nuevo estado (Aprobado/Reprobado)
        $this->updatedSelectedSessionId($this->selectedSessionId);
    }

    // Función de ayuda para los badges de estado
    public function getStatusBadgeClass(string $status): string
    {
        return [
            'Aprobado' => 'bg-green-100 text-green-800',
            'Reprobado' => 'bg-red-100 text-red-800',
        ][$status] ?? 'bg-yellow-100 text-yellow-800';
    }
}
