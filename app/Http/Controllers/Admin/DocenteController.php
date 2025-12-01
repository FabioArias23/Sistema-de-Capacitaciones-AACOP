<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    // Crear nuevo docente
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'docente',
        ]);

        return redirect()
            ->route('admin.dashboard', ['section' => 'teachers'])
            ->with('success', 'Docente creado correctamente.');
    }

    // Eliminar docente
    public function destroy(User $docente)
    {
        if ($docente->role === 'docente') {
            $docente->delete();
        }

        return redirect()
            ->route('admin.dashboard', ['section' => 'teachers'])
            ->with('success', 'Docente eliminado correctamente.');
    }
}
