<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\TrainingSession;
use App\Models\Enrollment;
use Livewire\Component;

class ParticipantManagement extends Component
{
    public string $searchTerm = '';
    public bool $dialogOpen = false;
    public ?User $selectedParticipant = null;

    // Propiedades para el formulario de inscripción
    public $trainingSessionId;

    public function render()
    {
        $participants = User::where('role', 'student')
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->with(['enrollments.trainingSession']) // Carga anticipada para optimizar consultas
            ->get();

        // Obtenemos las sesiones disponibles para el modal de inscripción
        $availableSessions = TrainingSession::where('status', 'Programada')->get();

        return view('livewire.admin.participant-management', [
            'participants' => $participants,
            'availableSessions' => $availableSessions,
        ]);
    }

    // Abre el modal de inscripción para un participante específico
    public function openEnrollDialog(User $participant)
    {
        $this->selectedParticipant = $participant;
        $this->reset('trainingSessionId');
        $this->dialogOpen = true;
    }

    // Guarda la nueva inscripción
    public function enroll()
    {
        $this->validate([
            'trainingSessionId' => 'required|exists:training_sessions,id'
        ]);

        // Verificar si ya está inscrito
        $alreadyEnrolled = Enrollment::where('user_id', $this->selectedParticipant->id)
            ->where('training_session_id', $this->trainingSessionId)
            ->exists();

        if ($alreadyEnrolled) {
            // Podríamos mostrar un mensaje de error aquí
            session()->flash('error', 'Este participante ya está inscrito en esta sesión.');
            $this->dialogOpen = false;
            return;
        }

        Enrollment::create([
            'user_id' => $this->selectedParticipant->id,
            'training_session_id' => $this->trainingSessionId,
        ]);

        session()->flash('success', '¡Participante inscrito correctamente!');
        $this->dialogOpen = false;
    }

    // Función de ayuda para los colores de los badges
    public function getStatusBadgeClass(string $status): string
    {
        return [
            'Inscrito' => 'bg-[#38C0E3]/10 text-[#38C0E3] dark:bg-[#38C0E3]/20',
            'Completado' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'En progreso' => 'bg-[#FFD700]/10 text-[#B8860B] dark:bg-[#FFD700]/20 dark:text-[#FFD700]',
            'Cancelado' => 'bg-[#ED1C24]/10 text-[#ED1C24] dark:bg-[#ED1C24]/20',
        ][$status] ?? 'bg-muted text-muted-foreground';
    }
}
