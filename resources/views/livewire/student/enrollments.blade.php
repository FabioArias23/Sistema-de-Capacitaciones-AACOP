<div class="space-y-6">
    <!-- Encabezado -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Mis Inscripciones</h2>
        <p class="text-muted-foreground">Gestiona las capacitaciones en las que estás inscrito.</p>
    </div>

    <!-- Mensajes de feedback -->
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

    <!-- Grid de Inscripciones -->
    <div class="grid gap-6">
        @forelse ($enrollments as $enrollment)
            <div class="bg-card border-2 hover:shadow-lg transition-all rounded-2xl">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-start gap-3 mb-2">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <x-lucide-graduation-cap class="w-6 h-6 text-primary" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-heading text-lg font-semibold">{{ $enrollment->trainingSession->training_title }}</h4>
                                    <div class="flex items-center gap-1 text-sm text-muted-foreground mt-1">
                                        <x-lucide-user class="w-3 h-3" />
                                        {{ $enrollment->trainingSession->instructor }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getStatusBadgeClass($enrollment->status) }}">
                            {{ $enrollment->status }}
                        </span>
                    </div>
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2 text-muted-foreground"><x-lucide-map-pin class="w-4 h-4" /><span>{{ $enrollment->trainingSession->campus_name }}</span></div>
                        <div class="flex items-center gap-2 text-muted-foreground"><x-lucide-calendar class="w-4 h-4" /><span>{{ \Carbon\Carbon::parse($enrollment->trainingSession->date)->translatedFormat('d \\de F, Y') }}</span></div>
                    </div>

                    @if ($enrollment->status !== 'Inscrito')
                        <div class="space-y-3 pt-3 border-t border-border">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2 text-muted-foreground"><x-lucide-trending-up class="w-4 h-4" /><span>Asistencia</span></div>
                                <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold">{{ $enrollment->attendance }}%</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2 text-muted-foreground"><x-lucide-award class="w-4 h-4" /><span>Nota</span></div>
                                <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold">{{ $enrollment->grade ?? 'N/A' }}%</span>
                            </div>
                        </div>
                    @endif

                    @if ($enrollment->status === 'Inscrito')
                        <div class="pt-3 border-t border-border">
                            <button
                                wire:click="unenroll({{ $enrollment->id }})"
                                wire:confirm="¿Estás seguro de que deseas desinscribirte de esta capacitación?"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium border text-destructive hover:text-destructive hover:bg-destructive/10 border-destructive/20 h-8 px-3"
                            >
                                <x-lucide-x-circle class="w-4 h-4 mr-2" />
                                Desinscribirme
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <x-lucide-graduation-cap class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
                <p class="text-muted-foreground">No tienes inscripciones activas. ¡Explora los cursos disponibles!</p>
            </div>
        @endforelse
    </div>
</div>
