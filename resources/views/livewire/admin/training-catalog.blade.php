<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold text-gray-900">Catálogo de Capacitaciones</h2>
            <p class="text-gray-600">Gestiona todos los cursos y programas de formación</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 px-4 py-2 shadow transition-colors">
            <x-lucide-plus class="w-4 h-4" />
            Nueva Capacitación
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="relative">
        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
        <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Buscar capacitaciones..." class="flex h-9 w-full rounded-xl border border-gray-300 bg-white px-3 py-1 pl-10 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500 text-gray-900">
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($trainings as $training)
            <div class="bg-white text-gray-900 flex flex-col rounded-2xl border border-gray-200 hover:shadow-lg transition-all">
                <div class="p-6 pb-2">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-500/10">{{ $training->category }}</span>
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-gray-500/10 {{ $this->getLevelColorClass($training->level) }}">{{ $training->level }}</span>
                    </div>
                    <h4 class="font-heading text-lg font-semibold">{{ $training->title }}</h4>
                    <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $training->description }}</p>
                </div>
                <div class="p-6 pt-4 mt-auto space-y-4">
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2"><x-lucide-clock class="w-4 h-4" /><span>{{ $training->duration }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-users class="w-4 h-4" /><span>Cupo: {{ $training->capacity }}</span></div>
                        <div class="flex items-center gap-2"><x-lucide-user class="w-4 h-4" /><span>{{ $training->instructor }}</span></div>
                    </div>
                    <div class="flex gap-2 pt-2 border-t">
                        <button wire:click="edit({{ $training->id }})" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg text-sm font-medium border border-gray-300 bg-white hover:bg-gray-100 h-8 px-3 transition-colors text-gray-700">
                            <x-lucide-edit class="w-3.5 h-3.5" /> Editar
                        </button>
                        <button wire:click="delete({{ $training->id }})" wire:confirm="¿Estás seguro?" class="inline-flex items-center justify-center rounded-lg text-sm font-medium border border-red-300 bg-red-100 text-red-600 hover:bg-red-200 h-8 px-3 transition-colors">
                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-600">
                <x-lucide-book-open class="w-12 h-12 mx-auto mb-3 opacity-50" />
                <p>No se encontraron capacitaciones.</p>
            </div>
        @endforelse
    </div>


    @if($dialogOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" wire:click="$set('dialogOpen', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $trainingForm['id'] ? 'Editar Capacitación' : 'Nueva Capacitación' }}</h3>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Título *</label>
                        <input wire:model="trainingForm.title" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                        @error('trainingForm.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Descripción *</label>
                        <textarea wire:model="trainingForm.description" rows="3" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('trainingForm.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">Categoría *</label>
                            <input wire:model="trainingForm.category" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                            @error('trainingForm.category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">Nivel *</label>
                            <select wire:model="trainingForm.level" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                <option>Básico</option>
                                <option>Intermedio</option>
                                <option>Avanzado</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">Duración *</label>
                            <input wire:model="trainingForm.duration" placeholder="Ej: 16 horas" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                            @error('trainingForm.duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">Capacidad *</label>
                            <input type="number" wire:model="trainingForm.capacity" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                            @error('trainingForm.capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium mb-1 block text-gray-700">Instructor *</label>

            <!-- CAMBIO: Input convertido a Select -->
            <select wire:model="trainingForm.instructor" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Seleccionar Instructor...</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                @endforeach
            </select>

            @error('trainingForm.instructor')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="text-sm font-medium mb-1 block text-gray-700">Estado *</label>
            <select wire:model="trainingForm.status" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
        </div>
    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('dialogOpen', false)" class="px-4 py-2 text-sm font-medium border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
