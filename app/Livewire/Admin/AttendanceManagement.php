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
        // Validamos solo lo que realmente estamos enviando
        $this->validate([
            'attendanceData.*.attendance' => 'nullable',
            'attendanceData.*.grade' => 'nullable|numeric|min:0|max:10',
        ]);

        foreach ($this->attendanceData as $enrollmentId => $data) {
            $enrollment = Enrollment::find($enrollmentId);

            if ($enrollment) {
                // 1. Manejo de Asistencia (Checkbox)
                // Si la clave existe en el array, la usamos. Si no, usamos el valor actual de la base de datos.
                // Esto evita que se borre si no se tocó el checkbox.
                $rawAtt = $data['attendance'] ?? $enrollment->attendance;

                // Normalizamos a 100 o 0
                $attendance = ($rawAtt == 100 || $rawAtt === true || $rawAtt === '100' || $rawAtt === 'on') ? 100 : 0;

                // 2. Manejo de Nota (Input)
                // Verificamos si 'grade' viene en el array. Si no, usamos el de la base de datos.
                $grade = array_key_exists('grade', $data) ? $data['grade'] : $enrollment->grade;

                // 3. Lógica de Estado (Aprobado/Reprobado por nota)
                // Si la nota es nula o vacía, mantenemos el estado 'Inscrito' (o el que tenía antes si no era aprobado/reprobado)
                if ($grade === null || $grade === '') {
                     $status = in_array($enrollment->status, ['Aprobado', 'Reprobado']) ? 'Inscrito' : $enrollment->status;
                     $gradeToSave = null;
                } else {
                    $status = $grade >= 6 ? 'Aprobado' : 'Reprobado';
                    $gradeToSave = $grade;
                }

                $enrollment->update([
                    'attendance' => $attendance,
                    'grade' => $gradeToSave,
                    'status' => $status,
                ]);
            }
        }

        session()->flash('success', '¡Asistencia y notas guardadas correctamente!');

        // Recargamos los datos para refrescar la vista
        $this->updatedSelectedSessionId($this->selectedSessionId);
    }
}
