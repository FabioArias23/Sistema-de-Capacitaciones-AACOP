<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Gestión de Calificaciones</h2>
            <p class="text-muted-foreground">Registra las notas finales de tus estudiantes.</p>
        </div>
        @if($selectedSessionId)
            <button wire:click="save" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                <x-lucide-save class="w-4 h-4 mr-2" />
                Guardar Notas
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
        <p class="text-muted-foreground text-sm mb-4">Elige la capacitación para registrar calificaciones.</p>
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
                <h3 class="font-heading font-semibold">Calificaciones de Estudiantes</h3>
                <p class="text-muted-foreground text-sm">Ingresa las notas finales (mínimo 70% para aprobar).</p>
            </div>
            <div class="p-6 pt-0">
                <div class="rounded-xl border border-border overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="p-3 text-left text-sm font-semibold text-foreground">Estudiante</th>
                                <th class="p-3 text-center text-sm font-semibold text-foreground w-48">Nota Final (%)</th>
                                <th class="p-3 text-center text-sm font-semibold text-foreground w-48">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr class="border-b border-border last:border-b-0">
                                    <td class="p-3 font-medium">{{ $enrollment->user->name }}</td>
                                    <td class="p-3 text-center">
                                        <input type="number" min="0" max="100"
                                               wire:model="gradesData.{{ $enrollment->id }}.grade"
                                               class="w-24 mx-auto rounded-lg text-center border-input bg-transparent">
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-semibold {{ $this->getStatusBadgeClass($enrollment->status) }}">
                                            @if($enrollment->status === 'Aprobado') <x-lucide-award class="w-3 h-3 mr-1" /> @endif
                                            {{ $enrollment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-6 text-center text-muted-foreground">No hay estudiantes inscritos en esta sesión.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
