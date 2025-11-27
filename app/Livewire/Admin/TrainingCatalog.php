<?php

namespace App\Livewire\Admin;

use App\Models\Training;
use App\Models\User; // <--- IMPORTANTE: Importar el modelo User
use Livewire\Component;

class TrainingCatalog extends Component
{
    public string $searchTerm = '';
    public bool $dialogOpen = false;

    // Inicializamos el array para evitar errores de "undefined index"
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
        // 1. Búsqueda de Capacitaciones
        $trainings = Training::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('title', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('category', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('instructor', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('id', 'desc')
            ->get();

        // 2. Obtener lista de Docentes disponibles
        // Filtramos por el rol 'teacher' y ordenamos alfabéticamente
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return view('livewire.admin.training-catalog', [
            'trainings' => $trainings,
            'teachers' => $teachers, // <--- Pasamos la variable a la vista
        ]);
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset('trainingForm');
        // Valores por defecto
        $this->trainingForm['capacity'] = 20;
        $this->trainingForm['status'] = 'Activo';
        $this->trainingForm['level'] = 'Básico';
        // Aseguramos que instructor esté vacío al crear
        $this->trainingForm['instructor'] = '';

        $this->dialogOpen = true;
    }

    public function edit(Training $training)
    {
        $this->resetValidation();
        $this->trainingForm = $training->toArray();
        $this->dialogOpen = true;
    }

    public function save()
    {
        $this->validate();

        if (isset($this->trainingForm['id']) && $this->trainingForm['id']) {
            $training = Training::find($this->trainingForm['id']);
            $training->update($this->trainingForm);
            session()->flash('success', 'Capacitación actualizada correctamente.');
        } else {
            Training::create($this->trainingForm);
            session()->flash('success', 'Capacitación creada correctamente.');
        }

        $this->dialogOpen = false;
    }

    public function delete(Training $training)
    {
        $training->delete();
        session()->flash('success', 'Capacitación eliminada correctamente.');
    }

    public function getLevelColorClass(string $level): string
    {
        return match ($level) {
            'Básico' => 'bg-[#00A885]/10 text-[#00A885]',
            'Intermedio' => 'bg-[#FFD700]/10 text-[#B8860B]',
            'Avanzado' => 'bg-[#ED1C24]/10 text-[#ED1C24]',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
