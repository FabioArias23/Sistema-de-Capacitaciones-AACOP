<div class="space-y-6">
    <!-- Encabezado y Búsqueda -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Capacitaciones Disponibles</h2>
        <p class="text-muted-foreground">Explora y inscríbete en las capacitaciones disponibles.</p>
    </div>

    <div class="relative">
        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <input wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar capacitaciones..." class="border-input flex h-9 w-full rounded-xl border bg-transparent px-3 py-1 text-sm shadow-sm pl-10">
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

    <!-- Grid de Cursos Disponibles -->
    <div class="grid gap-6 sm:grid-cols-2">
        @forelse ($sessions as $session)
            @php
                $isFull = $session->registered >= $session->capacity;
            @endphp
            <div class="bg-card text-card-foreground flex flex-col gap-0 rounded-2xl border-2 hover:shadow-lg transition-all">
                <div class="p-6 pb-2">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-secondary text-secondary-foreground">{{ $session->training->category }}</span>
                        <span class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getLevelColorClass($session->training->level) }}">{{ $session->training->level }}</span>
                    </div>
                    <h4 class="font-heading text-lg font-semibold">{{ $session->training_title }}</h4>
                    <p class="text-muted-foreground text-sm">{{ $session->training->description }}</p>
                </div>
                <div class="p-6 pt-4 flex flex-col flex-1">
                    <div class="space-y-2 text-sm text-muted-foreground flex-1">
                        <div class="flex items-center gap-2"><x-lucide-user class="w-4 h-4" /><span>Instructor: {{ $session->instructor }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-clock class="w-4 h-4" /><span>{{ $session->training->duration }} • {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-map-pin class="w-4 h-4" /><span>{{ $session->campus_name }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-calendar class="w-4 h-4" /><span>{{ \Carbon\Carbon::parse($session->date)->translatedFormat('d \\de F, Y') }}</span></div>
                        <div class="flex items-center gap-2">
                            <x-lucide-users class="w-4 h-4" />
                            <span class="{{ $isFull ? 'text-destructive' : 'text-green-600' }}">
                                {{ $session->registered }}/{{ $session->capacity }} -
                                @if($isFull)
                                    Cupo Lleno
                                @else
                                    {{ $session->capacity - $session->registered }} cupos disponibles
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button
                            wire:click="enroll({{ $session->id }})"
                            wire:loading.attr="disabled"
                            wire:confirm="¿Confirmas tu inscripción en '{{ $session->training_title }}'?"
                            {{ $isFull ? 'disabled' : '' }}
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 disabled:opacity-50"
                        >
                            @if($isFull)
                                Cupo Lleno
                            @else
                                <x-lucide-check-circle-2 class="w-4 h-4 mr-2" />
                                Inscribirme
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 sm:col-span-2">
                <x-lucide-book-open class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
                <p class="text-muted-foreground">No se encontraron capacitaciones disponibles en este momento.</p>
            </div>
        @endforelse
    </div>
</div>
