<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             wire:click="$set('showModal', false)">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" @click.stop>
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900"> {{ isset($campus['id']) && $campus['id'] ? 'Editar Sede' : 'Nueva Sede' }}
                    </h3>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Nombre *</label>
                        <input type="text" wire:model="campus.name" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                        @error('campus.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Dirección *</label>
                        <input type="text" wire:model="campus.address" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                        @error('campus.address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Ciudad *</label>
                        <input type="text" wire:model="campus.city" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                        @error('campus.city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">Capacidad *</label>
                            <input type="number" wire:model="campus.capacity" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                            @error('campus.capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">Estado *</label>
                            <select wire:model="campus.status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            {{ isset($campus['id']) && $campus['id'] ? 'Guardar Cambios' : 'Crear Sede' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
