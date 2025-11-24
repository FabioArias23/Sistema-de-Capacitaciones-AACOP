<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use Livewire\Component;

class CampusManagement extends Component
{
    public string $searchTerm = '';
    public bool $dialogOpen = false;

    // Usamos un array para manejar el formulario del modal
    public array $campusForm = [
        'id' => null,
        'name' => '',
        'address' => '',
        'city' => '',
        'capacity' => 100,
        'status' => 'Activo',
    ];

    public function render()
    {
        // La búsqueda se actualiza automáticamente gracias a wire:model.live
        $campuses = Campus::where('name', 'like', '%'.$this->searchTerm.'%')
            ->orWhere('city', 'like', '%'.$this->searchTerm.'%')
            ->orWhere('address', 'like', '%'.$this->searchTerm.'%')
            ->get();

        // Usamos el layout principal que configuramos
        return view('livewire.admin.campus-management', [
            'campuses' => $campuses,
        ])->layout('layouts.app');
    }

    // Resetea el formulario y abre el modal
    public function create()
    {
        $this->reset('campusForm');
        $this->dialogOpen = true;
    }

    // Carga los datos de una sede en el formulario y abre el modal
    public function edit(Campus $campus)
    {
        $this->campusForm = $campus->toArray();
        $this->dialogOpen = true;
    }

    // Guarda una sede nueva o actualiza una existente
    public function save()
    {
        $data = validator($this->campusForm, [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:Activo,Inactivo',
        ])->validate();

        if (isset($this->campusForm['id'])) {
            Campus::find($this->campusForm['id'])->update($data);
        } else {
            Campus::create($data);
        }

        $this->dialogOpen = false;
    }

    // Elimina una sede
    public function delete(Campus $campus)
    {
        $campus->delete();
    }
}
