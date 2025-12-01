<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class TeacherManagement extends Component
{
    public function render()
    {
        $docentes = User::where('role', 'docente')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.teacher-management', [
            'docentes' => $docentes,
        ]);
    }
}
