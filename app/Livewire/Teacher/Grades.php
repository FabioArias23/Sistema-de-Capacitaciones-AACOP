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

    // Propiedad computada para las métricas (estilo tarjeta superior)
    public function getMetricsProperty()
    {
        if (empty($this->enrollments)) {
            return ['average' => 0, 'passed' => 0, 'total' => 0];
        }

        // Filtramos solo los que tienen nota cargada para el promedio
        $gradedStudents = collect($this->gradesData)->whereNotNull('grade');
        $totalGraded = $gradedStudents->count();

        // Promedio
        $average = $totalGraded > 0 ? round($gradedStudents->avg('grade'), 1) : 0;

        // Aprobados (Nota >= 6)
        $passed = $gradedStudents->where('grade', '>=', 6)->count();

        return [
            'average' => $average,
            'passed' => $passed,
            'total' => $this->enrollments->count()
        ];
    }

    public function render()
    {
        $sessions = TrainingSession::where('instructor', Auth::user()->name)
            ->where('date', '<=', now())
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.teacher.grades', [
            'sessions' => $sessions,
        ]);
    }

    public function updatedSelectedSessionId($sessionId)
    {
        if (empty($sessionId)) {
            $this->reset(['enrollments', 'gradesData']);
            return;
        }

        $this->enrollments = Enrollment::where('training_session_id', $sessionId)
            ->with('user')
            ->get();

        // Mapeamos las notas. Si no tiene nota, lo dejamos como null para que el input esté vacío
        $this->gradesData = $this->enrollments->mapWithKeys(function ($enrollment) {
            return [$enrollment->id => [
                'grade' => $enrollment->grade,
            ]];
        })->toArray();
    }

    public function save()
    {
        // Validación escala 0 a 10, permitimos decimales (numeric)
        $this->validate([
            'gradesData.*.grade' => 'nullable|numeric|min:0|max:10',
        ]);

        foreach ($this->gradesData as $enrollmentId => $data) {
            $enrollment = Enrollment::find($enrollmentId);
            if ($enrollment) {
                $grade = $data['grade'];

                // Determinamos el estado
                // Si la nota es null (vacía), el estado se queda en "Inscrito" o "En progreso"
                if ($grade === null || $grade === '') {
                     // Opcional: Podrías regresarlo a 'Inscrito' si borran la nota
                     $status = $enrollment->status === 'Inscrito' ? 'Inscrito' : $enrollment->status;
                     $gradeToSave = null;
                } else {
                    // Escala 1-10: 6 o más aprueba
                    $status = $grade >= 6 ? 'Aprobado' : 'Reprobado';
                    $gradeToSave = $grade;
                }

                $enrollment->update([
                    'grade' => $gradeToSave,
                    'status' => $status,
                ]);
            }
        }

        session()->flash('success', '¡Calificaciones guardadas correctamente!');

        // Recargamos para actualizar las métricas
        $this->updatedSelectedSessionId($this->selectedSessionId);
    }
}
