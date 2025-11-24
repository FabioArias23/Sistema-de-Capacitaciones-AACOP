<div class="space-y-6">
    <!-- Encabezado -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Mi Progreso</h2>
        <p class="text-muted-foreground">Seguimiento de tu desempeño académico.</p>
    </div>

    <!-- Grid de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Promedio General -->
        <div class="bg-card border-2 rounded-2xl p-6">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-[#00A885]/10 flex items-center justify-center">
                    <x-lucide-award class="w-5 h-5 text-[#00A885]" />
                </div>
                <h4 class="text-sm font-semibold">Promedio General</h4>
            </div>
            <span class="mt-4 block text-3xl font-bold text-[#00A885] font-heading">{{ $averageGrade }}%</span>
        </div>

        <!-- Asistencia -->
        <div class="bg-card border-2 rounded-2xl p-6">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <x-lucide-trending-up class="w-5 h-5 text-primary" />
                </div>
                <h4 class="text-sm font-semibold">Asistencia Promedio</h4>
            </div>
            <span class="mt-4 block text-3xl font-bold text-primary font-heading">{{ $averageAttendance }}%</span>
        </div>

        <!-- Cursos Completados -->
        <div class="bg-card border-2 rounded-2xl p-6">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-[#FFD700]/10 flex items-center justify-center">
                    <x-lucide-check-circle-2 class="w-5 h-5 text-[#B8860B] dark:text-[#FFD700]" />
                </div>
                <h4 class="text-sm font-semibold">Cursos Completados</h4>
            </div>
            <span class="mt-4 block text-3xl font-bold text-[#FFD700] font-heading">{{ $completedCourses }}</span>
        </div>
    </div>

    <!-- Historial de Capacitaciones -->
    <div class="bg-card border-2 rounded-2xl">
        <div class="p-6">
            <h3 class="font-heading font-semibold">Historial de Capacitaciones</h3>
            <p class="text-muted-foreground text-sm">Revisa tu desempeño en cada curso.</p>
        </div>
        <div class="p-6 pt-0">
            <div class="space-y-4">
                @forelse ($progressHistory as $enrollment)
                    <div class="p-4 bg-muted/50 rounded-xl space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium">{{ $enrollment->trainingSession->training_title }}</h4>
                                @if($enrollment->status === 'Aprobado' || $enrollment->status === 'Reprobado')
                                    <p class="text-sm text-muted-foreground flex items-center gap-1 mt-1">
                                        <x-lucide-calendar class="w-3 h-3" />
                                        Completado: {{ \Carbon\Carbon::parse($enrollment->trainingSession->date)->translatedFormat('d \\de F, Y') }}
                                    </p>
                                @endif
                            </div>
                            <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getStatusBadgeClass($enrollment->status) }}">
                                {{ $enrollment->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-muted-foreground">Asistencia</p>
                                <p class="font-semibold text-primary">{{ $enrollment->attendance }}%</p>
                            </div>
                            @if($enrollment->grade !== null)
                                <div>
                                    <p class="text-muted-foreground">Calificación</p>
                                    <p class="font-semibold text-[#00A885]">{{ $enrollment->grade }}%</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted-foreground py-8">Aún no tienes un historial de capacitaciones.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
