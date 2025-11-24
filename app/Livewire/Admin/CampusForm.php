<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use Livewire\Attributes\On;
use Livewire\Component;

class CampusForm extends Component
{
    public bool $showModal = false;

    // Datos del formulario
    public array $campus = [
        'id' => null,
        'name' => '',
        'address' => '',
        'city' => '',
        'capacity' => 100,
        'status' => 'Activo',
    ];

    protected function rules()
    {
        return [
            'campus.name' => 'required|string|max:255',
            'campus.address' => 'required|string|max:255',
            'campus.city' => 'required|string|max:255',
            'campus.capacity' => 'required|integer|min:1',
            'campus.status' => 'required|in:Activo,Inactivo',
        ];
    }

    // 1. Escucha el evento del padre para abrirse en modo "Crear"
    #[On('createCampus')]
    public function create()
    {
        $this->resetValidation();
        // Reseteamos el formulario
        $this->campus = [
            'id' => null,
            'name' => '',
            'address' => '',
            'city' => '',
            'capacity' => 100,
            'status' => 'Activo',
        ];
        $this->showModal = true;
    }

    // 2. Escucha el evento del padre para abrirse en modo "Editar"
    #[On('editCampus')]
    public function edit($id)
    {
        $this->resetValidation();
        // Buscamos en la BD y llenamos el formulario
        $this->campus = Campus::findOrFail($id)->toArray();
        $this->showModal = true;
    }

    // 3. Guarda en la Base de Datos
    public function save()
    {
        $this->validate();

        if (isset($this->campus['id']) && $this->campus['id']) {
            // Actualizar existente
            $campus = Campus::find($this->campus['id']);
            $campus->update($this->campus);
            session()->flash('success', 'Sede actualizada correctamente.');
        } else {
            // Crear nueva en la base de datos
            Campus::create($this->campus);
            session()->flash('success', 'Sede creada correctamente.');
        }

        // Cerrar modal
        $this->showModal = false;

        // Avisar al padre que recargue la lista
        $this->dispatch('campus-saved');
    }

    public function render()
    {
        return view('livewire.admin.campus-form');
    }
}
