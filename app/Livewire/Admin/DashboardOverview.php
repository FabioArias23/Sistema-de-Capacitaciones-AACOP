<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class DashboardOverview extends Component
{
    public array $metrics = [];
    public array $recentActivities = [];

    public function mount()
    {
        $this->loadMetrics();
        $this->loadRecentActivities();
    }

    public function loadMetrics()
    {
        // 1. Total Capacitaciones
        $totalTrainings = Training::count();
        $newTrainingsThisMonth = Training::where('created_at', '>=', now()->startOfMonth())->count();

        // 2. Docentes Activos
        $totalTeachers = User::where('role', 'teacher')->count();
        $newTeachersThisMonth = User::where('role', 'teacher')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        // 3. Participantes (Estudiantes)
        $totalStudents = User::where('role', 'student')->count();
        $newStudentsThisMonth = User::where('role', 'student')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        // 4. Certificados Emitidos
        $totalCertificates = Certificate::count();
        $newCertificatesThisMonth = Certificate::where('created_at', '>=', now()->startOfMonth())->count();

        // Estructuramos los datos para la vista
        $this->metrics = [
            [
                'label' => 'Total Capacitaciones',
                'value' => $totalTrainings,
                'trend' => $newTrainingsThisMonth > 0 ? '+' . $newTrainingsThisMonth : '0',
                'color' => 'text-[#213D8F] dark:text-[#38C0E3]'
            ],
            [
                'label' => 'Docentes Activos',
                'value' => $totalTeachers,
                'trend' => $newTeachersThisMonth > 0 ? '+' . $newTeachersThisMonth : '0',
                'color' => 'text-[#00A885]'
            ],
            [
                'label' => 'Participantes',
                'value' => $totalStudents,
                'trend' => $newStudentsThisMonth > 0 ? '+' . $newStudentsThisMonth : '0', // Muestra cuántos nuevos este mes
                'color' => 'text-[#FFD700]'
            ],
            [
                'label' => 'Certificados Emitidos',
                'value' => $totalCertificates,
                'trend' => $newCertificatesThisMonth > 0 ? '+' . $newCertificatesThisMonth : '0',
                'color' => 'text-[#38C0E3] dark:text-[#213D8F]'
            ],
        ];
    }

    public function loadRecentActivities()
    {
        // Obtenemos las últimas 3 inscripciones
        $enrollments = Enrollment::with(['user', 'trainingSession'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'text' => "Nueva inscripción: {$item->user->name} - " . ($item->trainingSession->training_title ?? 'Curso'),
                    'time' => $item->created_at->diffForHumans(), // Ej: "Hace 5 min"
                    'timestamp' => $item->created_at->timestamp,
                    'type' => 'enrollment'
                ];
            });

        // Obtenemos los últimos 3 certificados emitidos
        $certificates = Certificate::latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'text' => "Certificado emitido: {$item->student_name} - {$item->training_title}",
                    'time' => $item->created_at->diffForHumans(),
                    'timestamp' => $item->created_at->timestamp,
                    'type' => 'certificate'
                ];
            });

        // Obtenemos las últimas 3 capacitaciones creadas
        $trainings = Training::latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'text' => "Nueva capacitación creada: {$item->title}",
                    'time' => $item->created_at->diffForHumans(),
                    'timestamp' => $item->created_at->timestamp,
                    'type' => 'training'
                ];
            });

        // Fusionamos todas las colecciones, las ordenamos por fecha y tomamos las 5 más recientes
        $this->recentActivities = $enrollments->concat($certificates)
            ->concat($trainings)
            ->sortByDesc('timestamp')
            ->take(5)
            ->values() // Reindexar array
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard-overview');
    }
}
