<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Calendario</h2>
            <p class="text-muted-foreground">Programación de sesiones</p>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary/90">
            <x-lucide-calendar-plus class="w-4 h-4" /> Programar Sesión
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Calendario y Lista del Día -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Selector de Fecha Simple -->
            <div class="bg-white p-6 rounded-2xl border shadow-sm">
                <label class="block text-sm font-medium mb-2">Seleccionar fecha para ver agenda:</label>
                <input type="date" wire:model.live="selectedDate" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Agenda del Día Seleccionado -->
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b font-semibold">
                    Agenda del {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d \d\e F, Y') }}
                </div>
                <div class="p-4 space-y-4">
                    @forelse($sessionsOnSelectedDate as $session)
                        <div class="flex items-start gap-4 p-4 border rounded-xl hover:bg-gray-50 transition">
                            <div class="bg-blue-100 text-blue-700 p-3 rounded-lg flex flex-col items-center min-w-[80px]">
                                <span class="text-sm font-bold">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</span>
                                <span class="text-xs">a</span>
                                <span class="text-sm font-bold">{{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">{{ $session->training_title }}</h4>
                                <div class="text-sm text-gray-600 space-y-1 mt-1">
                                    <div class="flex items-center gap-2"><x-lucide-map-pin class="w-3 h-3"/> {{ $session->campus_name }}</div>
                                    <div class="flex items-center gap-2"><x-lucide-user class="w-3 h-3"/> {{ $session->instructor }}</div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium">{{ $session->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">No hay sesiones programadas para este día.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Próximas Sesiones (Sidebar) -->
        <div class="bg-white rounded-2xl border shadow-sm h-fit">
            <div class="p-4 border-b font-semibold">Próximas Sesiones</div>
            <div class="divide-y">
                @foreach($upcomingSessions as $upcoming)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="text-xs text-blue-600 font-bold mb-1">
                            {{ \Carbon\Carbon::parse($upcoming->date)->format('d/m/Y') }} • {{ \Carbon\Carbon::parse($upcoming->start_time)->format('H:i') }}
                        </div>
                        <div class="font-medium text-sm">{{ $upcoming->training_title }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $upcoming->campus_name }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal de Programación -->
    @if($dialogOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" wire:click="$set('dialogOpen', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" @click.stop>
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">Programar Nueva Sesión</h3>
                </div>
                <form wire:submit="save" class="p-6 space-y-4">
                    <!-- Selección de Capacitación -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Capacitación</label>
                        <select wire:model.live="training_id" class="w-full rounded-lg border-gray-300">
                            <option value="">Seleccionar...</option>
                            @foreach($trainings as $t)
                                <option value="{{ $t->id }}">{{ $t->title }}</option>
                            @endforeach
                        </select>
                        @error('training_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Selección de Sede -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Sede</label>
                        <select wire:model="campus_id" class="w-full rounded-lg border-gray-300">
                            <option value="">Seleccionar...</option>
                            @foreach($campuses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->city }})</option>
                            @endforeach
                        </select>
                        @error('campus_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Fecha y Horarios -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1">Fecha</label>
                            <input type="date" wire:model="date" class="w-full rounded-lg border-gray-300">
                            @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Inicio</label>
                            <input type="time" wire:model="start_time" class="w-full rounded-lg border-gray-300">
                            @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Fin</label>
                            <input type="time" wire:model="end_time" class="w-full rounded-lg border-gray-300">
                            @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Instructor y Capacidad -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Instructor</label>
                            <input type="text" wire:model="instructor" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Capacidad</label>
                            <input type="number" wire:model="capacity" class="w-full rounded-lg border-gray-300">
                            @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('dialogOpen', false)" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Programar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
