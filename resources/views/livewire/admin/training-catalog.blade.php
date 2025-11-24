<div class="space-y-6">
    <!-- Encabezado y Botón -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Catálogo de Capacitaciones</h2>
            <p class="text-muted-foreground">Gestiona todos los cursos y programas de formación</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium transition-all bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
            <x-lucide-plus class="w-4 h-4 mr-2" />
            Nueva Capacitación
        </button>
    </div>

    <!-- Búsqueda -->
    <div class="relative">
        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <input wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar capacitaciones..." class="border-input flex h-9 w-full rounded-xl border bg-transparent px-3 py-1 text-sm shadow-sm pl-10">
    </div>

    <!-- Grid de Capacitaciones -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($trainings as $training)
            <div class="bg-card text-card-foreground flex flex-col gap-0 rounded-2xl border-2 hover:shadow-lg transition-all hover:border-primary/20">
                <div class="p-6 pb-2">
                    <div class="flex justify-between items-start mb-2">
                        <div class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-secondary text-secondary-foreground">{{ $training->category }}</div>
                        <div class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold border-0 {{ $this->getLevelColorClass($training->level) }}">{{ $training->level }}</div>
                    </div>
                    <h4 class="font-heading text-lg font-semibold">{{ $training->title }}</h4>
                    <p class="text-muted-foreground text-sm">{{ $training->description }}</p>
                </div>
                <div class="p-6 pt-4 space-y-4">
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <div class="flex items-center gap-2"><x-lucide-clock class="w-4 h-4" /><span>{{ $training->duration }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-users class="w-4 h-4" /><span>Capacidad: {{ $training->capacity }} personas</span></div>
                        <div class="flex items-center gap-2"><x-lucide-book-open class="w-4 h-4" /><span>Instructor: {{ $training->instructor }}</span></div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button wire:click="edit({{ $training->id }})" class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap rounded-xl text-sm font-medium transition-all border bg-background text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground h-8 px-3 flex-1">
                            <x-lucide-edit class="w-4 h-4" /> Editar
                        </button>
                        <button wire:click="delete({{ $training->id }})" wire:confirm="¿Estás seguro de eliminar esta capacitación?" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium transition-all border bg-background shadow-sm h-8 px-3 text-destructive hover:text-destructive hover:bg-destructive/10 border-destructive/20">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 col-span-full">
                <x-lucide-book-open class="w-12 h-12 mx-auto text-muted-foreground mb-4" />
                <p class="text-muted-foreground">No se encontraron capacitaciones</p>
            </div>
        @endforelse
    </div>

    <!-- Modal / Dialog para Crear y Editar -->
    @if($dialogOpen)
        <div class="fixed inset-0 bg-black/50 z-50" x-data @click="$wire.set('dialogOpen', false)">
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-card p-6 rounded-2xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                <form wire:submit="save" class="space-y-4">
                    <h3 class="font-heading text-lg font-semibold">{{ isset($this->trainingForm['id']) ? 'Editar Capacitación' : 'Nueva Capacitación' }}</h3>
                    <p class="text-sm text-muted-foreground">{{ isset($this->trainingForm['id']) ? 'Modifica los detalles de la capacitación' : 'Completa la información para crear una nueva capacitación' }}</p>

                    <!-- Campos del formulario -->
                    <div>
                        <label class="text-sm font-medium">Título *</label>
                        <input wire:model="trainingForm.title" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                        @error('trainingForm.title') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium">Descripción *</label>
                        <textarea wire:model="trainingForm.description" rows="3" class="mt-1 border-input flex min-h-[60px] w-full rounded-md border bg-transparent px-3 py-2 text-sm"></textarea>
                        @error('trainingForm.description') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Categoría *</label>
                            <input wire:model="trainingForm.category" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                            @error('trainingForm.category') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Nivel *</label>
                            <select wire:model="trainingForm.level" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                                <option>Básico</option>
                                <option>Intermedio</option>
                                <option>Avanzado</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">Duración *</label>
                            <input wire:model="trainingForm.duration" placeholder="ej: 16 horas" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                            @error('trainingForm.duration') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Capacidad *</label>
                            <input type="number" wire:model="trainingForm.capacity" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                            @error('trainingForm.capacity') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Instructor *</label>
                        <input wire:model="trainingForm.instructor" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                        @error('trainingForm.instructor') <span class="text-destructive text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium">Estado *</label>
                        <select wire:model="trainingForm.status" class="mt-1 border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm">
                            <option>Activo</option>
                            <option>Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="$wire.set('dialogOpen', false)" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all border bg-background text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">Cancelar</button>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">{{ isset($this->trainingForm['id']) ? 'Guardar Cambios' : 'Crear Capacitación' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
