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

    public array $sessionForm = [
        'training_id' => null,
        'campus_id' => null,
        'instructor' => '',
        'date' => '',
        'start_time' => '09:00',
        'end_time' => '13:00',
        'capacity' => 20,
    ];

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $date = Carbon::parse($this->selectedDate);

        $sessionsOnSelectedDate = TrainingSession::whereDate('date', $date)->orderBy('start_time')->get();

        $upcomingSessions = TrainingSession::where('date', '>=', now()->startOfDay())
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $trainings = Training::where('status', 'Activo')->pluck('title', 'id');
        $campuses = Campus::where('status', 'Activo')->pluck('name', 'id');

        return view('livewire.admin.training-schedule', [
            'sessionsOnSelectedDate' => $sessionsOnSelectedDate,
            'upcomingSessions' => $upcomingSessions,
            'trainings' => $trainings,
            'campuses' => $campuses,
        ]);
    }

    public function updatedSelectedDate($value)
    {
        $this->selectedDate = $value;
    }

    public function create()
    {
        $this->reset('sessionForm');
        $this->sessionForm['date'] = $this->selectedDate;
        $this->dialogOpen = true;
    }

    public function save()
    {
        $this->validate([
            'sessionForm.training_id' => 'required|exists:trainings,id',
            'sessionForm.campus_id' => 'required|exists:campuses,id',
            'sessionForm.date' => 'required|date',
            'sessionForm.start_time' => 'required',
            'sessionForm.end_time' => 'required|after:sessionForm.start_time',
            'sessionForm.capacity' => 'required|integer|min:1',
        ]);

        $training = Training::find($this->sessionForm['training_id']);
        $campus = Campus::find($this->sessionForm['campus_id']);

        TrainingSession::create([
            'training_id' => $training->id,
            'campus_id' => $campus->id,
            'training_title' => $training->title,
            'campus_name' => $campus->name,
            'instructor' => $training->instructor,
            'date' => $this->sessionForm['date'],
            'start_time' => $this->sessionForm['start_time'],
            'end_time' => $this->sessionForm['end_time'],
            'capacity' => $this->sessionForm['capacity'],
        ]);

        $this->dialogOpen = false;
    }
}
