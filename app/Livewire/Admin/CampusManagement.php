<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use Livewire\Attributes\On;
use Livewire\Component;

class CampusManagement extends Component
{
    public string $searchTerm = '';

    // 1. Escucha cuando el formulario guarda para recargar la lista
    #[On('campus-saved')]
    public function refreshList()
    {
        // Livewire renderiza automáticamente, esto refresca la tabla
    }

    // 2. Esta función se ejecuta al hacer clic en el botón "Nueva Sede"
    public function create()
    {
        // Emite un evento llamado 'createCampus' que escuchará el componente hijo
        $this->dispatch('createCampus');
    }

    public function edit($id)
    {
        $this->dispatch('editCampus', id: $id);
    }

    public function delete(Campus $campus)
    {
        $campus->delete();
        session()->flash('success', 'Sede eliminada correctamente.');
    }

    public function render()
    {
        $campuses = Campus::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('city', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.campus-management', [
            'campuses' => $campuses
        ]);
    }
}
