<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class DashboardOverview extends Component
{
    public array $metrics = [];
    public array $recentActivities = [];

    // El método mount() es perfecto para inicializar los datos del componente.
    public function mount()
    {
        $this->metrics = [
            ['label' => 'Total Capacitaciones', 'value' => '24', 'trend' => '+12%', 'color' => 'text-[#213D8F] dark:text-[#38C0E3]'],
            ['label' => 'Docentes Activos', 'value' => '8', 'trend' => '+2', 'color' => 'text-[#00A885]'],
            ['label' => 'Participantes', 'value' => '156', 'trend' => '+18%', 'color' => 'text-[#FFD700]'],
            ['label' => 'Certificados Emitidos', 'value' => '42', 'trend' => '+5', 'color' => 'text-[#38C0E3] dark:text-[#213D8F]'],
        ];

        $this->recentActivities = [
            ['text' => 'Nueva inscripción: Juan Pérez - Liderazgo Efectivo', 'time' => 'Hace 5 min'],
            ['text' => 'Certificado emitido: María García - Excel Avanzado', 'time' => 'Hace 15 min'],
            ['text' => 'Nueva capacitación creada: Comunicación Efectiva', 'time' => 'Hace 1 hora'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard-overview');
    }
}
