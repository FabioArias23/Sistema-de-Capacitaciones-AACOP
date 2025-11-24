<?php

namespace App\Livewire\Teacher;

use App\Models\Enrollment;
use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Attendance extends Component
{
    public $selectedSessionId;
    public $enrollments = [];
    public $attendanceData = [];

    public function render()
    {
        // El docente solo puede pasar lista de sus propias clases
        $sessions = TrainingSession::where('instructor', Auth::user()->name)
            ->where('date', '<=', now()) // Solo sesiones pasadas o del día de hoy
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.teacher.attendance', [
            'sessions' => $sessions,
        ]);
    }

    // Se ejecuta cuando el docente selecciona una sesión del desplegable
    public function updatedSelectedSessionId($sessionId)
    {
        if (empty($sessionId)) {
            $this->reset(['enrollments', 'attendanceData']);
            return;
        }

        $this->enrollments = Enrollment::where('training_session_id', $sessionId)
            ->with('user')
            ->get();

        // Preparamos el array con los datos para los inputs del formulario
        $this->attendanceData = $this->enrollments->mapWithKeys(function ($enrollment) {
            return [$enrollment->id => [
                'attendance' => $enrollment->attendance,
            ]];
        })->toArray();
    }

    public function save()
    {
        $this->validate([
            'attendanceData.*.attendance' => 'required|integer|min:0|max:100',
        ]);

        foreach ($this->attendanceData as $enrollmentId => $data) {
            $enrollment = Enrollment::find($enrollmentId);
            if ($enrollment) {
                $enrollment->update([
                    'attendance' => $data['attendance'],
                ]);
            }
        }

        session()->flash('success', '¡Asistencia guardada correctamente!');
    }
}
