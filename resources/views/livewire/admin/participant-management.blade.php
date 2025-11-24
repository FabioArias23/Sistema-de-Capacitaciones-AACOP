<div class="space-y-6">
    <!-- Encabezado y Búsqueda -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Gestión de Participantes</h2>
            <p class="text-muted-foreground">Administra los estudiantes inscritos en las capacitaciones</p>
        </div>
        <div class="relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <input wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar participantes..." class="border-input flex h-9 w-full rounded-xl border bg-transparent px-3 py-1 text-sm shadow-sm pl-10">
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Lista de Participantes -->
    <div class="grid gap-6">
        @forelse ($participants as $participant)
            <div class="bg-card text-card-foreground flex flex-col gap-0 rounded-2xl border-2 hover:shadow-lg transition-all">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div>
                            <h4 class="font-heading text-lg font-semibold flex items-center gap-2">{{ $participant->name }}</h4>
                            <p class="text-sm text-muted-foreground mt-1">{{ $participant->email }}</p>
                        </div>
                        <button wire:click="openEnrollDialog({{ $participant->id }})" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                            <x-lucide-user-plus class="w-4 h-4 mr-2" />
                            Inscribir
                        </button>
                    </div>
                </div>
                <div class="p-6 pt-0">
                    @if ($participant->enrollments->isNotEmpty())
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-foreground">Inscripciones ({{ $participant->enrollments->count() }})</h4>
                            <div class="grid gap-3">
                                @foreach ($participant->enrollments as $enrollment)
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 p-4 bg-muted/50 rounded-xl">
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $enrollment->trainingSession->training_title }}</p>
                                            <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($enrollment->trainingSession->date)->translatedFormat('d \\de F, Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getStatusBadgeClass($enrollment->status) }}">
                                                {{ $enrollment->status }}
                                            </span>
                                            <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-secondary text-secondary-foreground">Nota: {{ $enrollment->grade ?? 'N/A' }}%</span>
                                            <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-secondary text-secondary-foreground">Asistencia: {{ $enrollment->attendance }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-muted-foreground text-center py-4">Sin inscripciones activas.</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 col-span-full">
                <x-lucide-users class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
                <p class="text-muted-foreground">No se encontraron participantes.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal de Inscripción -->
    @if($dialogOpen)
        <div class="fixed inset-0 bg-black/50 z-50" x-data @click="$wire.set('dialogOpen', false)">
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-card p-6 rounded-2xl shadow-lg w-full max-w-lg" @click.stop>
                <form wire:submit="enroll" class="space-y-4">
                    <h3 class="font-heading text-lg font-semibold">Nueva Inscripción</h3>
                    <p class="text-sm text-muted-foreground">Inscribe a <span class="font-bold">{{ $selectedParticipant?->name }}</span> en una sesión de capacitación.</p>

                    <div>
                        <label class="text-sm font-medium">Sesión de Capacitación *</label>
                        <select wire:model="trainingSessionId" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                            <option value="">Selecciona una sesión</option>
                            @foreach($availableSessions as $session)
                                <option value="{{ $session->id }}">{{ $session->training_title }} - {{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                        @error('trainingSessionId') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="$wire.set('dialogOpen', false)" class="inline-flex items-center justify-center rounded-md text-sm font-medium border bg-background h-9 px-4 py-2">Cancelar</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground h-9 px-4 py-2">Inscribir</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
