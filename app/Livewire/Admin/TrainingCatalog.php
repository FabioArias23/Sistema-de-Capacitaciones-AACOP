<?php

namespace App\Livewire\Admin;

use App\Models\Training;
use Livewire\Component;

class TrainingCatalog extends Component
{
    public string $searchTerm = '';
    public bool $dialogOpen = false;

    public array $trainingForm = [
        'id' => null,
        'title' => '',
        'description' => '',
        'category' => '',
        'duration' => '',
        'capacity' => 20,
        'level' => 'Básico',
        'instructor' => '',
        'status' => 'Activo',
    ];

    // Reglas de validación para el formulario
    protected function rules()
    {
        return [
            'trainingForm.title' => 'required|string|max:255',
            'trainingForm.description' => 'required|string',
            'trainingForm.category' => 'required|string|max:255',
            'trainingForm.duration' => 'required|string|max:255',
            'trainingForm.capacity' => 'required|integer|min:1',
            'trainingForm.level' => 'required|in:Básico,Intermedio,Avanzado',
            'trainingForm.instructor' => 'required|string|max:255',
            'trainingForm.status' => 'required|in:Activo,Inactivo',
        ];
    }

    public function render()
    {
        $trainings = Training::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('title', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('category', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('instructor', 'like', '%' . $this->searchTerm . '%');
            })
            ->get();

        // Nota: No especificamos un layout aquí porque este componente
        // se renderiza dentro del Dashboard, que ya tiene un layout.
        return view('livewire.admin.training-catalog', [
            'trainings' => $trainings,
        ]);
    }

    public function create()
    {
        $this->reset('trainingForm');
        $this->dialogOpen = true;
    }

    public function edit(Training $training)
    {
        $this->trainingForm = $training->toArray();
        $this->dialogOpen = true;
    }

    public function save()
    {
        $this->validate();

        if (isset($this->trainingForm['id'])) {
            $training = Training::find($this->trainingForm['id']);
            $training->update($this->trainingForm);
        } else {
            Training::create($this->trainingForm);
        }

        $this->dialogOpen = false;
    }

    public function delete(Training $training)
    {
        $training->delete();
    }

    // Función de ayuda para obtener la clase CSS del badge según el nivel
    public function getLevelColorClass(string $level): string
    {
        return [
            'Básico' => 'bg-[#00A885]/10 text-[#00A885] dark:bg-[#00A885]/20',
            'Intermedio' => 'bg-[#FFD700]/10 text-[#B8860B] dark:bg-[#FFD700]/20 dark:text-[#FFD700]',
            'Avanzado' => 'bg-[#ED1C24]/10 text-[#ED1C24] dark:bg-[#ED1C24]/20',
        ][$level] ?? 'bg-muted text-muted-foreground';
    }
}
