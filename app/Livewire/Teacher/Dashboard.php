<?php

namespace App\Livewire\Teacher;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class Dashboard extends Component
{
    #[Url]
    public string $activeSection = 'dashboard';

    public $user;
    public array $navItems = [];

    public function mount(string $section = 'dashboard')
    {
        $this->user = Auth::user();
        $this->activeSection = $section;

        $this->navItems = [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
            ['id' => 'classes', 'label' => 'Mis Clases', 'icon' => 'graduation-cap'],
            ['id' => 'attendance', 'label' => 'Asistencia', 'icon' => 'clipboard-check'],
            ['id' => 'grades', 'label' => 'Notas', 'icon' => 'file-text'],
        ];
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return $this->redirect('/');
    }

    public function render()
    {
        return view('livewire.teacher.dashboard')->layout('layouts.app');
    }
}
