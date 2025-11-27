<div class="space-y-6">

    <!-- 1. Tarjetas de Métricas (Solo visibles si hay una sesión seleccionada) -->
    @if($selectedSessionId)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Tarjeta Presentes -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Presentes</h3>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-emerald-600">{{ $this->metrics['present'] }}</span>
                    <span class="text-gray-400 text-lg">/ {{ $this->metrics['total'] }}</span>
                </div>
            </div>

            <!-- Tarjeta Tasa de Asistencia -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Tasa de Asistencia</h3>
                <div class="text-3xl font-bold text-blue-900">{{ $this->metrics['rate'] }}%</div>
            </div>
        </div>
    @endif

    <!-- 2. Selector de Clase -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="mb-4">
            <h3 class="font-bold text-gray-900">Seleccionar Clase</h3>
            <p class="text-sm text-gray-500">Elige la sesión para registrar asistencia</p>
        </div>

        <div class="relative">
            <select wire:model.live="selectedSessionId" class="w-full appearance-none bg-white border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-pointer transition-all">
                <option value="">Selecciona una sesión...</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">
                        {{ $session->training_title }} - {{ $session->campus_name }} ({{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }})
                    </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>

    <!-- 3. Lista de Estudiantes -->
    @if($selectedSessionId)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header de la Lista -->
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-900">Lista de Estudiantes</h3>
                    <p class="text-sm text-gray-500">Haz clic en la fila para marcar asistencia</p>
                </div>
                <!-- Botón Guardar (Visible en Desktop) -->
                <button wire:click="save" class="hidden sm:inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm">
                    <x-lucide-save class="w-4 h-4" /> Guardar Cambios
                </button>
            </div>

            <!-- Filas de Estudiantes -->
            <div class="divide-y divide-gray-50">
                @forelse ($enrollments as $enrollment)
                    @php
                        // Determinamos si está presente (valor 100)
                        $isPresent = ($attendanceData[$enrollment->id]['attendance'] ?? 0) == 100;
                    @endphp

                    <div
                        wire:click="toggleAttendance({{ $enrollment->id }})"
                        class="group flex items-center justify-between p-4 hover:bg-blue-50/50 transition-colors cursor-pointer select-none"
                    >
                        <!-- Información del Estudiante y Checkbox -->
                        <div class="flex items-center gap-4">
                            <!-- Checkbox personalizado -->
                            <div class="relative flex items-center justify-center w-6 h-6 flex-shrink-0">
                                <div class="w-6 h-6 border-2 rounded-md transition-all duration-200 flex items-center justify-center
                                    {{ $isPresent ? 'bg-blue-600 border-blue-600 shadow-sm' : 'border-gray-300 bg-white group-hover:border-blue-400' }}">
                                    @if($isPresent)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900 {{ $isPresent ? '' : 'text-gray-500' }}">
                                    {{ $enrollment->user->name }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $enrollment->user->email }}</span>
                            </div>
                        </div>

                        <!-- Badge de Estado (Visible) -->
                        <div>
                            @if($isPresent)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <x-lucide-check-circle-2 class="w-3.5 h-3.5" />
                                    <span class="hidden sm:inline">Presente</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100 opacity-70">
                                    <x-lucide-x-circle class="w-3.5 h-3.5" />
                                    <span class="hidden sm:inline">Ausente</span>
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <x-lucide-users class="w-8 h-8 text-gray-400" />
                        </div>
                        <p class="text-gray-500 font-medium">No hay estudiantes inscritos en esta sesión.</p>
                    </div>
                @endforelse
            </div>

            <!-- Botón Guardar Flotante (Solo Móvil) -->
            <div class="p-4 border-t border-gray-100 sm:hidden sticky bottom-0 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                <button wire:click="save" class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg active:scale-95 transition-transform">
                    Guardar Asistencia
                </button>
            </div>
        </div>
    @endif

    <!-- Notificación Flotante de Éxito (Toast) -->
    <div
        x-data="{ show: false, message: '' }"
        x-on:attendance-saved.window="show = true; message = 'Asistencia guardada correctamente'; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 right-6 bg-gray-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 z-50"
        style="display: none;"
    >
        <x-lucide-check-circle class="w-5 h-5 text-emerald-400" />
        <span class="font-medium text-sm" x-text="message"></span>
    </div>
</div>
