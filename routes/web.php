<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\CampusManagement;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Student\Dashboard as StudentDashboard;

use App\Http\Controllers\Admin\DocenteController;

// Redirige la ruta raíz a la página de login
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta de dashboard principal - redirige según el rol del usuario
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'docente':
            return redirect()->route('teacher.dashboard');
        case 'participante':
        default:
            return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


// --- RUTAS PARA ADMINISTRADOR ---
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard del administrador (Livewire)
        Route::get('/dashboard/{section?}', AdminDashboard::class)
            ->name('dashboard')
            ->where(
                'section',
                'dashboard|catalog|campus|schedule|participants|teachers|attendance|certificates|reports'
            );

        // Gestión de campus (ruta que ya tenías)
        Route::get('/campus-management', CampusManagement::class)
            ->name('campus-management');

        // NUEVO: CRUD de Docentes
        Route::resource('docentes', DocenteController::class)->except(['show']);
    });


// --- RUTAS PARA DOCENTE ---
Route::middleware(['auth', 'verified'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard/{section?}', TeacherDashboard::class)
            ->name('dashboard')
            ->where('section', 'dashboard|courses|attendance|grades');
    });


// --- RUTAS PARA ESTUDIANTE ---
Route::middleware(['auth', 'verified'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard/{section?}', StudentDashboard::class)
            ->name('dashboard')
            ->where('section', 'dashboard|courses|enrollments|progress');
    });


// RUTAS DE PERFIL
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
