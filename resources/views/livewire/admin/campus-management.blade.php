<!-- resources/views/livewire/admin/campus-management.blade.php -->
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-semibold text-foreground">Gestión de Sedes</h2>
            <p class="text-muted-foreground">Administra las ubicaciones donde se imparten las capacitaciones</p>
        </div>
        <button
            wire:click="create"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <path d="M5 12h14"/><path d="M12 5v14"/>
            </svg>
            Nueva Sede
        </button>
    </div>

    <!-- Búsqueda -->
    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Buscar sedes..."
            class="border-input flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-10 rounded-xl"
        >
    </div>

    <!-- Grid de Sedes -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($campuses as $campus)
            <div class="bg-card text-card-foreground flex flex-col gap-0 rounded-2xl border-2 hover:shadow-lg transition-all hover:border-primary/20">
                <div class="grid items-start gap-1.5 p-6">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <h4 class="font-heading text-lg font-semibold flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary">
                                    <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>
                                </svg>
                                {{ $campus->name }}
                            </h4>
                            <p class="text-muted-foreground flex items-center gap-1 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                                </svg>
                                {{ $campus->city }}
                            </p>
                        </div>
                        <div class="inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $campus->status === 'Activo' ? 'border-transparent bg-primary text-primary-foreground shadow' : 'border-transparent bg-secondary text-secondary-foreground' }}">
                            {{ $campus->status }}
                        </div>
                    </div>
                </div>
                <div class="p-6 pt-0 space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start gap-2 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mt-0.5 flex-shrink-0">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span>{{ $campus->address }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <span>Capacidad: {{ $campus->capacity }} personas</span>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-3 border-t border-border">
                        <button
                            wire:click="edit({{ $campus->id }})"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 border bg-background text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground h-8 px-3 flex-1 rounded-xl"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>
                            </svg>
                            Editar
                        </button>
                        <button
                            wire:click="delete({{ $campus->id }})"
                            wire:confirm="¿Estás seguro de que deseas eliminar esta sede?"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 border bg-background shadow-sm h-8 px-3 text-[#ED1C24] hover:text-[#ED1C24] hover:bg-[#ED1C24]/10 border-[#ED1C24]/20 rounded-xl"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 col-span-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 mx-auto text-muted-foreground mb-4">
                    <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>
                </svg>
                <p class="text-muted-foreground">No se encontraron sedes</p>
            </div>
        @endforelse
    </div>

    <!-- Modal / Dialog -->
    @if($dialogOpen)
        <!-- Overlay con backdrop blur -->
        {{-- <div
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 transition-opacity"
            wire:click="$set('dialogOpen', false)"
        ></div> --}}

        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
            <div
                class="bg-background border border-border rounded-lg shadow-2xl w-full max-w-md pointer-events-auto transform transition-all"
                wire:click.stop
            >
                <form wire:submit.prevent="save" class="space-y-4 p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-border">
                        <h3 class="font-heading text-lg font-semibold text-foreground">
                            {{ isset($this->campusForm['id']) ? 'Editar Sede' : 'Nueva Sede' }}
                        </h3>
                        <button
                            type="button"
                            wire:click="$set('dialogOpen', false)"
                            class="rounded-md p-1 hover:bg-accent transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-foreground mb-1.5">
                                Nombre de la Sede <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                type="text"
                                wire:model="campusForm.name"
                                class="mt-1 block w-full border-input rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Ej: Sede Central"
                            >
                            @error('campusForm.name')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-foreground mb-1.5">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="address"
                                type="text"
                                wire:model="campusForm.address"
                                class="mt-1 block w-full border-input rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Ej: Av. Principal 123"
                            >
                            @error('campusForm.address')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-foreground mb-1.5">
                                Ciudad <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="city"
                                type="text"
                                wire:model="campusForm.city"
                                class="mt-1 block w-full border-input rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Ej: Buenos Aires"
                            >
                            @error('campusForm.city')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-medium text-foreground mb-1.5">
                                Capacidad <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="capacity"
                                type="number"
                                min="1"
                                wire:model="campusForm.capacity"
                                class="mt-1 block w-full border-input rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                placeholder="100"
                            >
                            @error('campusForm.capacity')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-foreground mb-1.5">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="status"
                                wire:model="campusForm.status"
                                class="mt-1 block w-full border-input rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-ring"
                            >
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                            @error('campusForm.status')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-border">
                        <button
                            type="button"
                            wire:click="$set('dialogOpen', false)"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 border bg-background text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2"
                        >
                            {{ isset($this->campusForm['id']) ? 'Guardar Cambios' : 'Crear Sede' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
