<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Gestión de Asistencia y Notas</h2>
            <p class="text-muted-foreground">Registra la asistencia y calificaciones de los estudiantes</p>
        </div>
        @if($selectedSessionId)
            <button wire:click="save" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <x-lucide-save class="w-4 h-4 mr-2" />
                Guardar Cambios
            </button>
        @endif
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Selector de Sesión -->
    <div class="bg-card border-2 rounded-2xl p-6">
        <h3 class="font-heading font-semibold">Seleccionar Sesión</h3>
        <p class="text-muted-foreground text-sm mb-4">Elige la sesión para registrar asistencia y notas.</p>
        <select wire:model.live="selectedSessionId" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
            <option value="">Selecciona una sesión...</option>
            @foreach($sessions as $session)
                <option value="{{ $session->id }}">
                    {{ $session->training_title }} - {{ $session->campus_name }} ({{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tabla de Estudiantes (se muestra solo si se ha seleccionado una sesión) -->
    @if($selectedSessionId)
        <div class="bg-card border-2 rounded-2xl">
            <div class="p-6">
                <h3 class="font-heading font-semibold">Lista de Estudiantes</h3>
            </div>
            <div class="p-6 pt-0">
                <div class="rounded-xl border border-border overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="p-3 text-left text-sm font-semibold text-foreground">Estudiante</th>
                                <th class="p-3 text-center text-sm font-semibold text-foreground">Asistencia (%)</th>
                                <th class="p-3 text-center text-sm font-semibold text-foreground">Nota Final (%)</th>
                                <th class="p-3 text-center text-sm font-semibold text-foreground">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr class="border-b border-border last:border-b-0">
                                    <td class="p-3 font-medium">{{ $enrollment->user->name }}</td>
                                    <td class="p-3 text-center">
                                        <input type="number" min="0" max="100"
                                               wire:model="attendanceData.{{ $enrollment->id }}.attendance"
                                               class="w-20 mx-auto rounded-lg text-center border-input bg-transparent">
                                    </td>
                                    <td class="p-3 text-center">
                                        <input type="number" min="0" max="100"
                                               wire:model="attendanceData.{{ $enrollment->id }}.grade"
                                               class="w-20 mx-auto rounded-lg text-center border-input bg-transparent">
                                    </td>
                                    <td class="p-3 text-center">
                                        @if($enrollment->status === 'Aprobado')
                                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-semibold bg-green-100 text-green-800">
                                                <x-lucide-check-circle-2 class="w-3 h-3 mr-1" /> Aprobado
                                            </span>
                                        @elseif($enrollment->status === 'Reprobado')
                                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-semibold bg-red-100 text-red-800">
                                                <x-lucide-x-circle class="w-3 h-3 mr-1" /> Reprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                {{ $enrollment->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-muted-foreground">No hay estudiantes inscritos en esta sesión.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
