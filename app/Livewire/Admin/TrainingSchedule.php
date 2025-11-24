<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use App\Models\Training;
use App\Models\TrainingSession;
use Carbon\Carbon;
use Livewire\Component;

class TrainingSchedule extends Component
{
    public $selectedDate;
    public bool $dialogOpen = false;

    // Estado del formulario
    public $training_id = '';
    public $campus_id = '';
    public $date = '';
    public $start_time = '09:00';
    public $end_time = '13:00';
    public $capacity = 20;
    public $instructor = ''; // Se autocompletará al elegir el training

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->date = $this->selectedDate;
    }

    // Cuando cambia el Training ID, autocompletamos instructor y capacidad sugerida
    public function updatedTrainingId($value)
    {
        $training = Training::find($value);
        if ($training) {
            $this->instructor = $training->instructor;
            $this->capacity = $training->capacity;
        }
    }

    public function updatedSelectedDate($value)
    {
        $this->date = $value;
    }

    public function create()
    {
        $this->reset(['training_id', 'campus_id', 'start_time', 'end_time', 'instructor']);
        $this->date = $this->selectedDate;
        $this->dialogOpen = true;
    }

    public function save()
    {
        $this->validate([
            'training_id' => 'required|exists:trainings,id',
            'campus_id' => 'required|exists:campuses,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|integer|min:1',
        ]);

        $training = Training::find($this->training_id);
        $campus = Campus::find($this->campus_id);

        TrainingSession::create([
            'training_id' => $training->id,
            'campus_id' => $campus->id,
            // Desnormalización requerida por la migración original:
            'training_title' => $training->title,
            'campus_name' => $campus->name,
            'instructor' => $this->instructor ?: $training->instructor,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'capacity' => $this->capacity,
            'status' => 'Programada'
        ]);

        session()->flash('success', 'Sesión programada exitosamente.');
        $this->dialogOpen = false;
    }

    public function render()
    {
        $date = Carbon::parse($this->selectedDate);

        // Obtener sesiones de la fecha seleccionada
        $sessionsOnSelectedDate = TrainingSession::whereDate('date', $date)
            ->orderBy('start_time')
            ->get();

        // Obtener próximas sesiones
        $upcomingSessions = TrainingSession::where('date', '>=', now()->startOfDay())
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        // Datos para los select del formulario
        $trainings = Training::where('status', 'Activo')->get();
        $campuses = Campus::where('status', 'Activo')->get();

        return view('livewire.admin.training-schedule', [
            'sessionsOnSelectedDate' => $sessionsOnSelectedDate,
            'upcomingSessions' => $upcomingSessions,
            'trainings' => $trainings,
            'campuses' => $campuses,
        ]);
    }
}
