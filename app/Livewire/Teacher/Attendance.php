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

    // Propiedad computada para las métricas en tiempo real
    public function getMetricsProperty()
    {
        if (empty($this->attendanceData)) {
            return ['present' => 0, 'total' => 0, 'rate' => 0];
        }

        $total = count($this->attendanceData);
        // Consideramos "Presente" si la asistencia es 100
        $present = collect($this->attendanceData)->where('attendance', 100)->count();

        $rate = $total > 0 ? round(($present / $total) * 100) : 0;

        return [
            'present' => $present,
            'total' => $total,
            'rate' => $rate
        ];
    }

    public function render()
    {
        $sessions = TrainingSession::where('instructor', Auth::user()->name)
            ->where('date', '<=', now())
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.teacher.attendance', [
            'sessions' => $sessions,
        ]);
    }

    public function updatedSelectedSessionId($sessionId)
    {
        if (empty($sessionId)) {
            $this->reset(['enrollments', 'attendanceData']);
            return;
        }

        $this->enrollments = Enrollment::where('training_session_id', $sessionId)
            ->with('user')
            ->get();

        // Inicializamos: Si ya tiene valor, lo usamos. Si es null, asumimos 0 (Ausente) por defecto.
        $this->attendanceData = $this->enrollments->mapWithKeys(function ($enrollment) {
            return [$enrollment->id => [
                'attendance' => $enrollment->attendance ?? 0,
            ]];
        })->toArray();
    }

    // Método para alternar asistencia (Click en el checkbox)
    public function toggleAttendance($enrollmentId)
    {
        $current = $this->attendanceData[$enrollmentId]['attendance'];
        // Si tiene algo (100), lo ponemos en 0. Si es 0, lo ponemos en 100.
        $this->attendanceData[$enrollmentId]['attendance'] = $current == 100 ? 0 : 100;
    }

    public function save()
    {
        // Validamos
        $this->validate([
            'attendanceData.*.attendance' => 'required|integer',
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
