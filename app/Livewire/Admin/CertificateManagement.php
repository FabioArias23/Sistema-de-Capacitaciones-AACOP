<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Enrollment;
use Livewire\Component;

class CertificateManagement extends Component
{
    public string $searchTerm = '';
    public ?Certificate $previewCertificate = null;
    public bool $previewDialogOpen = false;

    public function render()
    {
        // Buscamos los certificados existentes
        $certificates = Certificate::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('student_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('training_title', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('certificate_number', 'like', '%' . $this->searchTerm . '%');
            })
            ->with('user', 'training') // Carga anticipada
            ->latest() // Ordenar por los más recientes
            ->get();

        // Buscamos inscripciones aprobadas que aún no tienen certificado
        $pendingEnrollments = Enrollment::where('status', 'Aprobado')
            ->whereDoesntHave('certificate')
            ->with(['user', 'trainingSession.training'])
            ->get();

        return view('livewire.admin.certificate-management', [
            'certificates' => $certificates,
            'pendingEnrollments' => $pendingEnrollments,
        ]);
    }

    // Genera un certificado para una inscripción aprobada
    public function generateCertificate(Enrollment $enrollment)
    {
        // Generar un número de certificado único
        $certificateNumber = 'CERT-' . now()->year . '-' . str_pad(Certificate::count() + 1, 4, '0', STR_PAD_LEFT);

        $certificate = Certificate::create([
            'certificate_number' => $certificateNumber,
            'user_id' => $enrollment->user_id,
            'training_id' => $enrollment->trainingSession->training_id,
            'enrollment_id' => $enrollment->id,
            'student_name' => $enrollment->user->name,
            'training_title' => $enrollment->trainingSession->training_title,
            'instructor_name' => $enrollment->trainingSession->instructor,
            'completion_date' => $enrollment->trainingSession->date,
            'grade' => $enrollment->grade,
        ]);

        // Asociar el certificado con la inscripción para que no aparezca más como pendiente
        $enrollment->certificate_id = $certificate->id;
        $enrollment->save();

        session()->flash('success', 'Certificado ' . $certificateNumber . ' generado exitosamente.');
    }

    // Abre el modal de previsualización
    public function showPreview(Certificate $certificate)
    {
        $this->previewCertificate = $certificate;
        $this->previewDialogOpen = true;
    }
}
