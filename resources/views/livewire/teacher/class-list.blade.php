<div class="space-y-8">
    <!-- Encabezado -->
    <div>
        <h2 class="font-heading text-2xl font-semibold text-gray-900">Mis Clases</h2>
        <p class="text-gray-500">Gestiona tus capacitaciones asignadas.</p>
    </div>

    <!-- Sección: Próximas Clases -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
            <h3 class="text-lg font-semibold text-gray-800">Próximas Clases</h3>
        </div>

        <div class="grid gap-4">
            @forelse ($upcomingClasses as $class)
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row justify-between gap-4 group">

                    <!-- Info Principal -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <x-lucide-calendar class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">{{ $class->training_title }}</h4>
                            <div class="flex flex-wrap gap-3 text-sm text-gray-500 mt-1">
                                <span class="flex items-center gap-1">
                                    <x-lucide-map-pin class="w-4 h-4" /> {{ $class->campus_name }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-lucide-users class="w-4 h-4" /> {{ $class->registered }}/{{ $class->capacity }} Inscritos
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha y Estado -->
                    <div class="flex flex-col md:items-end justify-center gap-2 min-w-[150px]">
                        <div class="text-right">
                            <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($class->date)->translatedFormat('d \d\e F, Y') }}</p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $this->getStatusBadgeClass($class->status) }}">
                            {{ $class->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                    <x-lucide-calendar-off class="w-10 h-10 mx-auto text-gray-400 mb-3" />
                    <p class="text-gray-500 font-medium">No tienes clases próximas asignadas.</p>
                    <p class="text-sm text-gray-400">Cuando un administrador te asigne una capacitación, aparecerá aquí.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Sección: Clases Completadas -->
    <div class="space-y-4 pt-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="h-8 w-1 bg-gray-300 rounded-full"></div>
            <h3 class="text-lg font-semibold text-gray-600">Historial de Clases</h3>
        </div>

        <div class="grid gap-4">
            @forelse ($completedClasses as $class)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row justify-between gap-4 opacity-75 hover:opacity-100 transition-opacity">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center flex-shrink-0">
                            <x-lucide-check-circle class="w-5 h-5" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $class->training_title }}</h4>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($class->date)->format('d/m/Y') }} • {{ $class->campus_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center md:justify-end">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $this->getStatusBadgeClass($class->status) }}">
                            {{ $class->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic">Aún no has completado ninguna clase.</p>
            @endforelse
        </div>
    </div>
</div>
