<div class="space-y-6">
    <!-- Encabezado -->
    <div>
        <h2 class="font-heading text-2xl font-semibold text-gray-900 dark:text-white">Mi Progreso</h2>
        <p class="text-muted-foreground text-sm text-gray-500 dark:text-gray-400">Seguimiento de tu desempeño académico.</p>
    </div>

    <!-- Grid de Métricas Superiores -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Promedio General -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <x-lucide-award class="w-5 h-5 text-emerald-500" />
                </div>
                <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Promedio General</h4>
            </div>
            <!-- CAMBIO: Se quitó el '%' y se agregó '/ 10' pequeño para contexto -->
            <div class="flex items-baseline gap-1">
                <span class="text-3xl font-bold text-emerald-500 font-heading">{{ $averageGrade }}</span>
                <span class="text-sm text-gray-400 font-medium">/ 10</span>
            </div>
        </div>

        <!-- Asistencia (Este sí se mantiene en %) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <x-lucide-trending-up class="w-5 h-5 text-blue-600" />
                </div>
                <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Asistencia</h4>
            </div>
            <span class="text-3xl font-bold text-blue-800 dark:text-blue-400 font-heading">{{ $averageAttendance }}%</span>
        </div>

        <!-- Cursos Completados -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <x-lucide-check-circle-2 class="w-5 h-5 text-amber-500" />
                </div>
                <h4 class="text-sm font-medium text-gray-600 dark:text-gray-300">Cursos Completados</h4>
            </div>
            <span class="text-3xl font-bold text-amber-500 font-heading">{{ $completedCourses }}</span>
        </div>
    </div>

    <!-- Historial -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <div class="mb-6">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Historial de Capacitaciones</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Revisa tu desempeño en cada curso</p>
        </div>

        <div class="space-y-8">
            @forelse ($progressHistory as $enrollment)
                @php
                    // Lógica de progreso visual para la barra azul
                    $progressValue = match($enrollment->status) {
                        'Inscrito' => 10,
                        'En progreso' => 50,
                        'Aprobado', 'Reprobado', 'Completado' => 100,
                        default => 0,
                    };

                    $badgeColor = match($enrollment->status) {
                        'Inscrito' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'En progreso' => 'bg-amber-50 text-amber-700 border-amber-100',
                        'Aprobado' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'Reprobado' => 'bg-red-50 text-red-700 border-red-100',
                        default => 'bg-gray-50 text-gray-600',
                    };
                @endphp

                <div class="border-b border-gray-100 dark:border-gray-700 last:border-0 pb-8 last:pb-0">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $enrollment->trainingSession->training_title }}</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeColor }}">
                            {{ $enrollment->status }}
                        </span>
                    </div>

                    @if($enrollment->status === 'Aprobado' || $enrollment->status === 'Reprobado')
                        <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 mb-4">
                            <x-lucide-calendar class="w-3 h-3" />
                            <span>Completado: {{ \Carbon\Carbon::parse($enrollment->trainingSession->date)->translatedFormat('d \d\e F \d\e Y') }}</span>
                        </div>
                    @else
                         <div class="mb-4 h-4"></div>
                    @endif

                    <!-- Barra de Progreso del Curso -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                            <span>Avance del curso</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $progressValue }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-800 h-2 rounded-full transition-all duration-500" style="width: {{ $progressValue }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nota (CAMBIO AQUÍ: Sin %) -->
                        @if($enrollment->grade !== null)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Calificación</p>
                                <p class="text-sm font-bold {{ $enrollment->grade >= 6 ? 'text-emerald-500' : 'text-red-500' }}">
                                    {{ $enrollment->grade }}
                                </p>
                            </div>
                        @endif

                        <!-- Asistencia -->
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Asistencia</p>
                            <p class="text-sm font-bold text-blue-800 dark:text-blue-400">{{ $enrollment->attendance }}%</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">Aún no tienes historial académico disponible.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
