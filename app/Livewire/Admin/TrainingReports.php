<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Training;
use App\Models\TrainingSession;
use App\Models\User;
use Livewire\Component;

class TrainingReports extends Component
{
    // Métricas de las tarjetas
    public $totalParticipants;
    public $totalTrainings;
    public $completionRate;
    public $averageGrade;

    // Datos para los gráficos
    public $enrollmentsChartData;
    public $categoryChartData;

    public function mount()
    {
        // --- Cálculos para las tarjetas ---
        $this->totalParticipants = User::where('role', 'student')->count();
        $this->totalTrainings = Training::count();
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'Aprobado')->count();
        $this->completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100) : 0;
        $this->averageGrade = round(Enrollment::whereNotNull('grade')->avg('grade')) ?? 0;

        // --- Preparación de datos para los gráficos ---
        $this->prepareEnrollmentsChartData();
        $this->prepareCategoryChartData();
    }

    public function prepareEnrollmentsChartData()
    {
        $enrollments = TrainingSession::query()
            ->selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();

        $completed = TrainingSession::where('status', 'Completada')
            ->selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();

        $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $enrollmentData = [];
        $completedData = [];

        foreach (range(1, 12) as $month) {
            $enrollmentData[] = $enrollments[$month] ?? 0;
            $completedData[] = $completed[$month] ?? 0;
        }

        $this->enrollmentsChartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Inscripciones',
                    'data' => $enrollmentData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Completadas',
                    'data' => $completedData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => '#10b981',
                ]
            ]
        ];
    }

    public function prepareCategoryChartData()
    {
        $data = Training::query()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        $this->categoryChartData = [
            'labels' => $data->pluck('category'),
            'datasets' => [
                [
                    'label' => 'Capacitaciones',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#6b7280'],
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.training-reports');
    }
}
