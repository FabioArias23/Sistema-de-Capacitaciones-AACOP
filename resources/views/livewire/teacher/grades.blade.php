<div class="space-y-6">

    <!-- 1. Encabezado con Botón Guardar -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold text-gray-900">Gestión de Calificaciones</h2>
            <p class="text-muted-foreground">Registra las notas finales (1-10). Aprobación con 6.</p>
        </div>
        @if($selectedSessionId)
            <button wire:click="save" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium bg-blue-900 text-white hover:bg-blue-800 h-10 px-6 py-2 shadow-sm transition-colors">
                <x-lucide-save class="w-4 h-4" />
                Guardar Notas
            </button>
        @endif
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- 2. Tarjetas de Métricas (Solo visibles si hay sesión) -->
    @if($selectedSessionId)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Promedio -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Promedio de Clase</h3>
                <div class="text-4xl font-bold text-amber-400">{{ $this->metrics['average'] }}</div>
            </div>

            <!-- Tasa de Aprobación -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Tasa de Aprobación</h3>
                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-bold text-emerald-500">{{ $this->metrics['passed'] }}</span>
                    <span class="text-gray-400 text-xl">/ {{ $this->metrics['total'] }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Selector de Clase -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="mb-4">
            <h3 class="font-bold text-gray-900">Seleccionar Clase</h3>
            <p class="text-sm text-gray-500">Elige la capacitación para calificar</p>
        </div>
        <div class="relative">
            <select wire:model.live="selectedSessionId" class="w-full appearance-none bg-white border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500 cursor-pointer">
                <option value="">Selecciona una clase...</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">
                        {{ $session->training_title }} - {{ $session->campus_name }} ({{ \Carbon\Carbon::parse($session->date)->format('d M') }})
                    </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>

    <!-- 4. Lista de Estudiantes -->
    @if($selectedSessionId)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-900">Calificaciones de Estudiantes</h3>
                <p class="text-sm text-gray-500">Ingresa las notas finales (mínimo 6 para aprobar)</p>
            </div>

            <!-- Filas -->
            <div class="divide-y divide-gray-100">
                @forelse ($enrollments as $enrollment)
                    @php
                        $grade = $gradesData[$enrollment->id]['grade'];
                        $isGraded = !is_null($grade) && $grade !== '';

                        // Lógica visual del estado
                        $badgeColor = 'bg-gray-100 text-gray-500'; // Pendiente
                        $statusText = 'Pendiente';

                        if ($isGraded) {
                            if ($grade >= 6) {
                                $badgeColor = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
                                $statusText = 'Aprobado';
                            } else {
                                $badgeColor = 'bg-red-100 text-red-700 border border-red-200';
                                $statusText = 'Reprobado';
                            }
                        }
                    @endphp

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 hover:bg-gray-50 transition-colors gap-4">
                        <!-- Nombre -->
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-base">{{ $enrollment->user->name }}</h4>
                            <p class="text-xs text-gray-400">{{ $enrollment->user->email }}</p>
                        </div>

                        <!-- Input de Nota y Badge -->
                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">

                            <!-- Input -->
                            <div class="flex items-center gap-2">
                                <label for="grade-{{ $enrollment->id }}" class="text-sm text-gray-400 font-medium">Nota:</label>
                                <input
    id="grade-{{ $enrollment->id }}"
    type="number"
    min="0"
    max="10"
    step="0.1"
    placeholder="-"
    wire:model="gradesData.{{ $enrollment->id }}.grade"
    oninput="if(parseFloat(this.value) > 10) this.value = 10; if(parseFloat(this.value) < 0) this.value = 0;"
    class="w-16 text-center font-bold text-gray-900 border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent p-1 transition-colors placeholder-gray-300"
>
                            </div>

                            <!-- Badge de Estado -->
                            <div class="w-28 text-right">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeColor }} transition-all duration-300">
                                    @if($statusText === 'Aprobado') <x-lucide-check class="w-3 h-3 mr-1" /> @endif
                                    @if($statusText === 'Reprobado') <x-lucide-x class="w-3 h-3 mr-1" /> @endif
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-gray-500 font-medium">No hay estudiantes inscritos en esta sesión.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
