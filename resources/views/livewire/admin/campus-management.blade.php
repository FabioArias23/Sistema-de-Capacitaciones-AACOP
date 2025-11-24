<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold">Gestión de Sedes</h2>
            <p class="text-muted-foreground">Administra las ubicaciones</p>
        </div>

        <!-- ESTE ES EL BOTÓN QUE DETONA LA ACCIÓN -->
        <button wire:click="create" class="inline-flex items-center justify-center gap-2 rounded-xl text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 shadow transition-colors">
            <x-lucide-plus class="w-4 h-4" />
            Nueva Sede
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <!-- Buscador y Tabla (Simplificado para el ejemplo) -->
    <div class="relative">
        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Buscar sedes..." class="flex h-9 w-full rounded-xl border border-input bg-transparent px-3 py-1 pl-10 text-sm shadow-sm">
    </div>

    <!-- Lista de Sedes -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($campuses as $campus)
            <div class="bg-card p-6 rounded-2xl border hover:shadow-lg transition-all">
                <h4 class="font-bold text-lg">{{ $campus->name }}</h4>
                <p class="text-sm text-gray-500">{{ $campus->city }}</p>
                <div class="mt-4 flex gap-2">
                    <button wire:click="edit({{ $campus->id }})" class="text-sm border px-3 py-1 rounded hover:bg-gray-100">Editar</button>
                    <button wire:click="delete({{ $campus->id }})" wire:confirm="¿Eliminar?" class="text-sm border border-red-200 text-red-600 px-3 py-1 rounded hover:bg-red-50">Eliminar</button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- IMPORTANTE: Aquí insertamos el componente del Modal -->
    <livewire:admin.campus-form />
</div>
