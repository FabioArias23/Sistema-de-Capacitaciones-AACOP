<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Url;

class Dashboard extends Component
{
    #[Url]
    public string $activeSection = 'dashboard';

    public $user;
    public array $navItems = [];

    public function mount($section = 'dashboard')
    {
        $this->user = Auth::user();
        $this->activeSection = $section;

        $this->navItems = [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
            ['id' => 'catalog', 'label' => 'Capacitaciones', 'icon' => 'graduation-cap'],
            ['id' => 'campus', 'label' => 'Sedes', 'icon' => 'building-2'],
            ['id' => 'schedule', 'label' => 'Calendario', 'icon' => 'calendar'],
            ['id' => 'participants', 'label' => 'Participantes', 'icon' => 'users'],
            ['id' => 'attendance', 'label' => 'Asistencia', 'icon' => 'clipboard-check'],
            ['id' => 'certificates', 'label' => 'Certificados', 'icon' => 'award'],
            ['id' => 'reports', 'label' => 'Reportes', 'icon' => 'bar-chart-3'],
        ];
    }

    public function changeSection(string $sectionId)
    {
        $this->activeSection = $sectionId;
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function render()
    {
        // ESTA ES LA LÍNEA IMPORTANTE QUE CORRIGE EL ERROR:
        return view('livewire.admin.dashboard')
             ->layout('layouts.panel');
    }
}
