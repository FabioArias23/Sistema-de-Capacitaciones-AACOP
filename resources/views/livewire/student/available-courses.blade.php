<div class="space-y-6 relative" x-data="{ confirmModal: false, sessionToEnroll: null, sessionTitle: '' }">

    <!-- Encabezado y Búsqueda (Sin cambios) -->
    <div>
        <h2 class="font-heading text-2xl font-semibold text-gray-900 dark:text-white">Capacitaciones Disponibles</h2>
        <p class="text-muted-foreground text-sm text-gray-500 dark:text-gray-400">Explora e inscríbete en las capacitaciones disponibles.</p>
    </div>

    <div class="relative">
        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <input wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar capacitaciones..." class="border-input flex h-10 w-full rounded-xl border bg-white dark:bg-gray-800 dark:border-gray-700 px-3 py-1 text-sm shadow-sm pl-10 transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500 dark:text-white">
    </div>

    <!-- Mensajes de feedback -->
    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-r-lg animate-fade-in-up">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-lucide-check-circle class="h-5 w-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Grid de Cursos Disponibles -->
    <div class="grid gap-6 sm:grid-cols-2">
        @forelse ($sessions as $session)
            @php $isFull = $session->registered >= $session->capacity; @endphp
            <div class="bg-white dark:bg-gray-800 text-card-foreground flex flex-col gap-0 rounded-2xl border dark:border-gray-700 hover:shadow-lg transition-all hover:-translate-y-1 duration-300">

                <!-- Header de la Tarjeta -->
                <div class="p-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">{{ $session->training->category }}</span>
                        <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-medium {{ $this->getLevelColorClass($session->training->level) }}">{{ $session->training->level }}</span>
                    </div>
                    <h4 class="font-heading text-lg font-bold text-gray-900 dark:text-white leading-tight">{{ $session->training_title }}</h4>
                </div>

                <!-- Cuerpo de la Tarjeta -->
                <div class="p-6 pt-4 flex flex-col flex-1">
                    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300 flex-1">
                        <div class="flex items-center gap-2"><x-lucide-user class="w-4 h-4 text-gray-400" /><span>{{ $session->instructor }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-clock class="w-4 h-4 text-gray-400" /><span>{{ $session->training->duration }} • {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-map-pin class="w-4 h-4 text-gray-400" /><span>{{ $session->campus_name }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-calendar class="w-4 h-4 text-gray-400" /><span class="font-medium text-blue-600 dark:text-blue-400">{{ \Carbon\Carbon::parse($session->date)->translatedFormat('d \de F, Y') }}</span></div>

                        <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-lucide-users class="w-4 h-4 text-gray-400" />
                                <span class="text-xs font-medium">Cupos:</span>
                            </div>
                            <span class="text-xs font-bold {{ $isFull ? 'text-red-500' : 'text-green-600 dark:text-green-400' }}">
                                {{ $session->registered }} / {{ $session->capacity }}
                                ({{ $isFull ? 'Lleno' : ($session->capacity - $session->registered) . ' libres' }})
                            </span>
                        </div>
                    </div>

                    <!-- Botón de Acción -->
                    <div class="mt-6">
                        <button
                            @if(!$isFull)
                                @click="confirmModal = true; sessionToEnroll = {{ $session->id }}; sessionTitle = '{{ $session->training_title }}'"
                            @endif
                            {{ $isFull ? 'disabled' : '' }}
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl text-sm font-bold h-10 px-4 transition-all duration-200
                            {{ $isFull
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500'
                                : 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-md hover:shadow-blue-500/20 active:scale-95 dark:bg-blue-500 dark:hover:bg-blue-400' }}"
                        >
                            @if($isFull)
                                <span>Cupo Completo</span>
                            @else
                                <x-lucide-plus-circle class="w-4 h-4" />
                                <span>Inscribirme</span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 sm:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <x-lucide-search-x class="w-8 h-8 text-gray-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No se encontraron cursos</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Intenta ajustar tu búsqueda o vuelve más tarde.</p>
            </div>
        @endforelse
    </div>

    <!-- MODAL DE CONFIRMACIÓN PERSONALIZADO -->
    <div
        x-show="confirmModal"
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm transition-opacity"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-gray-100 dark:border-gray-700"
            @click.away="confirmModal = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        >
            <div class="p-6 text-center">
                <!-- Icono animado -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-6">
                    <x-lucide-graduation-cap class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                </div>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">¿Confirmar inscripción?</h3>

                <p class="text-sm text-gray-500 dark:text-gray-300">
                    Estás a punto de inscribirte en el curso:
                    <br>
                    <span class="font-bold text-blue-600 dark:text-blue-400 mt-1 block text-base" x-text="sessionTitle"></span>
                </p>

                <div class="mt-8 flex gap-3 justify-center">
                    <button
                        @click="confirmModal = false"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>

                    <button
                        @click="$wire.enroll(sessionToEnroll); confirmModal = false"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5"
                    >
                        Confirmar Inscripción
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
