<?php

namespace App\Livewire\Student;

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
            ['id' => 'courses', 'label' => 'Disponibles', 'icon' => 'book-open'],
            ['id' => 'enrollments', 'label' => 'Mis Cursos', 'icon' => 'graduation-cap'],
            ['id' => 'progress', 'label' => 'Mi Progreso', 'icon' => 'trending-up'],
        ];
    }
      public function changeSection($sectionId)
    {
        $this->activeSection = $sectionId;
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
        return view('livewire.student.dashboard')->layout('layouts.panel');
    }
}
