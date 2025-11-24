<?php

namespace App\Livewire\Student;

use App\Models\Enrollment;
use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AvailableCourses extends Component
{
    public string $searchTerm = '';

    public function render()
    {
        // Obtenemos los IDs de las sesiones en las que el usuario ya está inscrito
        $enrolledSessionIds = Enrollment::where('user_id', Auth::id())->pluck('training_session_id');

        $availableSessions = TrainingSession::query()
            // Solo sesiones programadas
            ->where('status', 'Programada')
            // Excluimos las sesiones en las que ya está inscrito
            ->whereNotIn('id', $enrolledSessionIds)
            // Aplicamos el filtro de búsqueda
            ->when($this->searchTerm, function ($query) {
                $query->where('training_title', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('campus_name', 'like', '%' . $this->searchTerm . '%');
            })
            ->with('training') // Carga anticipada para obtener el nivel
            ->orderBy('date')
            ->get();

        return view('livewire.student.available-courses', [
            'sessions' => $availableSessions,
        ]);
    }

    // Acción para inscribir al estudiante en una sesión
    public function enroll(TrainingSession $session)
    {
        // Doble verificación para evitar inscripciones duplicadas
        $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('training_session_id', $session->id)
            ->exists();

        if ($alreadyEnrolled) {
            session()->flash('error', 'Ya estás inscrito en esta capacitación.');
            return;
        }

        // Verificar si hay cupo disponible
        if ($session->registered >= $session->capacity) {
            session()->flash('error', 'Esta capacitación ya no tiene cupos disponibles.');
            return;
        }

        // Crear la inscripción
        Enrollment::create([
            'user_id' => Auth::id(),
            'training_session_id' => $session->id,
        ]);

        // Incrementar el contador de inscritos en la sesión
        $session->increment('registered');

        session()->flash('success', '¡Inscripción exitosa en ' . $session->training_title . '!');
    }

    // Función de ayuda para los colores de los badges de nivel
    public function getLevelColorClass(string $level): string
    {
        return [
            'Básico' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'Intermedio' => 'bg-[#FFD700]/10 text-[#B8860B] dark:bg-[#FFD700]/20 dark:text-[#FFD700]',
            'Avanzado' => 'bg-[#ED1C24]/10 text-[#ED1C24] dark:bg-[#ED1C24]/20',
        ][$level] ?? 'bg-muted text-muted-foreground';
    }
}
