<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Control de Asistencia</h2>
            <p class="text-muted-foreground">Registra la asistencia de tus estudiantes.</p>
        </div>
        @if($selectedSessionId)
            <button wire:click="save" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <x-lucide-save class="w-4 h-4 mr-2" />
                Guardar Asistencia
            </button>
        @endif
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Selector de Clase -->
    <div class="bg-card border-2 rounded-2xl p-6">
        <h3 class="font-heading font-semibold">Seleccionar Clase</h3>
        <p class="text-muted-foreground text-sm mb-4">Elige la sesión para registrar la asistencia.</p>
        <select wire:model.live="selectedSessionId" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
            <option value="">Selecciona una clase...</option>
            @foreach($sessions as $session)
                <option value="{{ $session->id }}">
                    {{ $session->training_title }} - ({{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tabla de Estudiantes -->
    @if($selectedSessionId)
        <div class="bg-card border-2 rounded-2xl">
            <div class="p-6">
                <h3 class="font-heading font-semibold">Lista de Estudiantes</h3>
                <p class="text-muted-foreground text-sm">Ingresa el porcentaje de asistencia para cada estudiante.</p>
            </div>
            <div class="p-6 pt-0">
                <div class="rounded-xl border border-border overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="p-3 text-left text-sm font-semibold text-foreground">Estudiante</th>
                                <th class="p-3 text-center text-sm font-semibold text-foreground w-48">Asistencia (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr class="border-b border-border last:border-b-0">
                                    <td class="p-3 font-medium">{{ $enrollment->user->name }}</td>
                                    <td class="p-3 text-center">
                                        <input type="number" min="0" max="100"
                                               wire:model="attendanceData.{{ $enrollment->id }}.attendance"
                                               class="w-24 mx-auto rounded-lg text-center border-input bg-transparent">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="p-6 text-center text-muted-foreground">No hay estudiantes inscritos en esta sesión.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
