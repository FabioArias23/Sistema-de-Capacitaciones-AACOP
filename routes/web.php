<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\CampusManagement;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;

use App\Livewire\Student\Dashboard as StudentDashboard;
// Redirige la ruta raíz a la página de login
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta de dashboard principal - redirige según el rol del usuario
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirigir según el rol del usuario
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }
    if ($user->role === 'student') {
        return redirect()->route('student.dashboard'); // <-- Añade esta redirección
    }
    // Para estudiantes u otros roles (puedes agregar más condiciones aquí)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Esta es la ruta para acceder a la gestión de sedes
    Route::get('/sedes', CampusManagement::class)->name('campuses');

});
// Rutas de administrador
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard/{section?}', AdminDashboard::class)
        ->name('dashboard')
        ->where('section', 'dashboard|catalog|campus|schedule|participants|attendance|certificates|reports');

    Route::get('/sedes', CampusManagement::class)->name('campuses');
});

// Rutas de docente
Route::middleware(['auth', 'verified'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard/{section?}', TeacherDashboard::class)
        ->name('dashboard')
        ->where('section', 'dashboard|classes|attendance|grades');
});
// --- NUEVO GRUPO DE RUTAS PARA ESTUDIANTE ---
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard/{section?}', StudentDashboard::class)
        ->name('dashboard')
        ->where('section', 'dashboard|courses|enrollments|progress');
});

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
