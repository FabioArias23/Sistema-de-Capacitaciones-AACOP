<div class="space-y-6">
    <!-- Encabezado -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Mis Clases</h2>
        <p class="text-muted-foreground">Gestiona tus capacitaciones asignadas.</p>
    </div>

    <!-- Tarjeta de Próximas Clases -->
    <div class="bg-card border-2 rounded-2xl">
        <div class="p-6">
            <h3 class="font-heading font-semibold">Próximas Clases</h3>
            <p class="text-muted-foreground text-sm">Capacitaciones programadas que aún no han finalizado.</p>
        </div>
        <div class="p-6 pt-0">
            <div class="grid gap-4">
                @forelse ($upcomingClasses as $class)
                    <div class="p-4 bg-muted/50 rounded-xl space-y-3">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <x-lucide-graduation-cap class="w-6 h-6 text-primary" />
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $class->training_title }}</h4>
                                    <p class="text-sm text-muted-foreground flex items-center gap-1 mt-1">
                                        <x-lucide-map-pin class="w-3 h-3" />
                                        {{ $class->campus_name }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getStatusBadgeClass($class->status) }}">
                                {{ $class->status }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm text-muted-foreground">
                            <div class="flex items-center gap-2"><x-lucide-calendar class="w-4 h-4" /><span>{{ \Carbon\Carbon::parse($class->date)->translatedFormat('d M, Y') }}</span></div>
                            <div class="flex items-center gap-2"><x-lucide-clock class="w-4 h-4" /><span>{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}</span></div>
                            <div class="flex items-center gap-2"><x-lucide-users class="w-4 h-4" /><span>{{ $class->registered }}/{{ $class->capacity }} estudiantes</span></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-muted-foreground text-center py-4">No tienes clases próximas asignadas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tarjeta de Clases Completadas -->
    <div class="bg-card border-2 rounded-2xl">
        <div class="p-6">
            <h3 class="font-heading font-semibold">Clases Completadas</h3>
            <p class="text-muted-foreground text-sm">Historial de capacitaciones que has impartido.</p>
        </div>
        <div class="p-6 pt-0">
            <div class="grid gap-4">
                @forelse ($completedClasses as $class)
                    <div class="p-4 bg-muted/50 rounded-xl space-y-3">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-[#00A885]/10 flex items-center justify-center flex-shrink-0">
                                    <x-lucide-graduation-cap class="w-6 h-6 text-[#00A885]" />
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $class->training_title }}</h4>
                                    <p class="text-sm text-muted-foreground flex items-center gap-1 mt-1">
                                        <x-lucide-map-pin class="w-3 h-3" />
                                        {{ $class->campus_name }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getStatusBadgeClass($class->status) }}">
                                {{ $class->status }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm text-muted-foreground">
                            <div class="flex items-center gap-2"><x-lucide-calendar class="w-4 h-4" /><span>{{ \Carbon\Carbon::parse($class->date)->translatedFormat('d M, Y') }}</span></div>
                            <div class="flex items-center gap-2"><x-lucide-clock class="w-4 h-4" /><span>{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}</span></div>
                            <div class="flex items-center gap-2"><x-lucide-users class="w-4 h-4" /><span>{{ $class->registered }} estudiantes</span></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-muted-foreground text-center py-4">Aún no has completado ninguna clase.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
