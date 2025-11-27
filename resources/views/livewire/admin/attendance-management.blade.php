<div class="space-y-6">

    <!-- 1. Encabezado y Botón Guardar -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold text-gray-900 dark:text-white">Gestión de Asistencia y Notas</h2>
            <p class="text-muted-foreground text-sm text-gray-500 dark:text-gray-400">Registra la asistencia y calificaciones de los estudiantes</p>
        </div>
        @if($selectedSessionId)
            <button wire:click="save" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium bg-blue-900 text-white hover:bg-blue-800 h-9 px-4 py-2 shadow-sm transition-colors">
                <x-lucide-save class="w-4 h-4" />
                Guardar Cambios
            </button>
        @endif
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-r-lg animate-fade-in-up">
            <p class="flex items-center gap-2"><x-lucide-check-circle class="w-5 h-5" /> {{ session('success') }}</p>
        </div>
    @endif

    <!-- 2. Tarjetas de Métricas (Solo visibles si hay sesión seleccionada) -->
    @if($selectedSessionId)
        @php
            $totalStudents = $enrollments->count();
            // Contamos presentes si attendance es 100 (o true)
            $presentStudents = $enrollments->where('attendance', '>=', 1)->count();
            $attendanceRate = $totalStudents > 0 ? round(($presentStudents / $totalStudents) * 100) : 0;

            $gradedStudents = $enrollments->whereNotNull('grade');
            $averageGrade = $gradedStudents->count() > 0 ? round($gradedStudents->avg('grade'), 1) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Presentes -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Estudiantes Presentes</h3>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-emerald-600">{{ $presentStudents }}</span>
                    <span class="text-gray-400 text-lg">/ {{ $totalStudents }}</span>
                </div>
            </div>

            <!-- Tasa -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Tasa de Asistencia</h3>
                <div class="text-3xl font-bold text-blue-900 dark:text-blue-400">{{ $attendanceRate }}%</div>
            </div>

            <!-- Promedio -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Promedio de Notas</h3>
                <div class="text-3xl font-bold text-yellow-500">{{ $averageGrade }}</div>
            </div>
        </div>
    @endif

    <!-- 3. Selector de Sesión -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="mb-4">
            <h3 class="font-bold text-gray-900 dark:text-white">Seleccionar Sesión</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Elige la sesión para registrar asistencia</p>
        </div>

        <div class="relative">
            <select wire:model.live="selectedSessionId" class="w-full appearance-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white dark:focus:bg-gray-900 focus:border-blue-500 cursor-pointer transition-all">
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

        @if($selectedSessionId)
            @php
                $selectedSession = $sessions->find($selectedSessionId);
            @endphp
            <div class="mt-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-lucide-calendar class="w-4 h-4" />
                <span>Fecha: {{ \Carbon\Carbon::parse($selectedSession->date)->translatedFormat('d \d\e F \d\e Y') }}</span>
            </div>
        @endif
    </div>

    <!-- 4. Tabla de Estudiantes -->
    @if($selectedSessionId)
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                <h3 class="font-bold text-gray-900 dark:text-white">Lista de Estudiantes</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-4 w-1/3">Estudiante</th>
                            <th class="px-6 py-4 text-center w-1/4">Asistencia</th>
                            <th class="px-6 py-4 text-center w-1/4">Nota (1-10)</th>
                            <th class="px-6 py-4 text-center w-1/6">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($enrollments as $enrollment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $enrollment->user->name }}</td>

                                <!-- Checkbox Asistencia -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <!-- Usamos value="100" para simular presente -->
                                            <input type="checkbox"
                                                wire:model="attendanceData.{{ $enrollment->id }}.attendance"
                                                value="100"
                                                class="sr-only peer"
                                                @if(($attendanceData[$enrollment->id]['attendance'] ?? 0) == 100) checked @endif
                                            >
                                            <!-- Diseño custom del checkbox -->
                                            <div class="w-6 h-6 bg-white dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-600 rounded peer-checked:bg-blue-800 peer-checked:border-blue-800 peer-focus:ring-2 peer-focus:ring-blue-500/50 transition-all flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </td>

                                <!-- Input Nota (Sin %) -->
                                <td class="px-6 py-4 text-center">
                                    <input
                                        type="number"
                                        min="0"
                                        max="10"
                                        step="0.1"
                                        wire:model="attendanceData.{{ $enrollment->id }}.grade"
                                        oninput="if(parseFloat(this.value) > 10) this.value = 10; if(parseFloat(this.value) < 0) this.value = 0;"
                                        class="w-20 text-center text-sm border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="-"
                                    >
                                </td>

                                <!-- Badge Estado (Presente/Ausente) -->
                                <td class="px-6 py-4 text-center">
                                    @php
                                        // Obtenemos el valor actual del array de datos
                                        $att = $attendanceData[$enrollment->id]['attendance'] ?? 0;
                                        // Normalizamos a booleano
                                        $isPresent = $att == 100 || $att === true || $att === '100';
                                    @endphp

                                    @if($isPresent)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                            <x-lucide-check-circle-2 class="w-3 h-3" /> Presente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100 opacity-70 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                            <x-lucide-x-circle class="w-3 h-3" /> Ausente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    No hay estudiantes inscritos en esta sesión.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
