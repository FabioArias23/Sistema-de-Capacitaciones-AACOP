<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\TrainingSession;
use Livewire\Component;

class AttendanceManagement extends Component
{
    public $selectedSessionId;
    public $enrollments = [];

    // Usamos un array para manejar las actualizaciones de forma masiva
    public $attendanceData = [];

    public function render()
    {
        // Obtenemos las sesiones que ya han ocurrido o están en curso para gestionar la asistencia
        $sessions = TrainingSession::where('date', '<=', now())
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.admin.attendance-management', [
            'sessions' => $sessions,
        ]);
    }

    // Este "hook" de Livewire se ejecuta cada vez que la propiedad $selectedSessionId cambia.
    public function updatedSelectedSessionId($sessionId)
    {
        if (empty($sessionId)) {
            $this->reset(['enrollments', 'attendanceData']);
            return;
        }

        // Cargamos las inscripciones de la sesión seleccionada, incluyendo la información del usuario
        $this->enrollments = Enrollment::where('training_session_id', $sessionId)
            ->with('user')
            ->get();

        // Preparamos el array con los datos para el formulario
        $this->attendanceData = $this->enrollments->mapWithKeys(function ($enrollment) {
            return [$enrollment->id => [
                'attendance' => $enrollment->attendance,
                'grade' => $enrollment->grade,
            ]];
        })->toArray();
    }

    public function save()
    {
        $this->validate([
            'attendanceData.*.attendance' => 'required|integer|min:0|max:100',
            'attendanceData.*.grade' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($this->attendanceData as $enrollmentId => $data) {
            $enrollment = Enrollment::find($enrollmentId);
            if ($enrollment) {
                $enrollment->update([
                    'attendance' => $data['attendance'],
                    'grade' => $data['grade'] ?: null, // Guarda null si el campo está vacío
                    // Actualizamos el estado basado en la nota
                    'status' => ($data['grade'] !== null && $data['grade'] >= 70) ? 'Aprobado' : (($data['grade'] !== null) ? 'Reprobado' : $enrollment->status),
                ]);
            }
        }

        session()->flash('success', '¡Asistencia y notas guardadas correctamente!');

        // Opcional: Recargar los datos para reflejar los cambios de estado
        $this->updatedSelectedSessionId($this->selectedSessionId);
    }
}
