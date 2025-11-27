@if ($showModal)
    <x-modal wire:model="showModal">

        <!-- ENCABEZADO (Reemplazo de x-modal.header) -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                {{ isset($campus['id']) && $campus['id'] ? 'Editar Sede' : 'Nueva Sede' }}
            </h3>
        </div>

        <!-- CUERPO (Reemplazo de x-modal.body) -->
        <div class="px-6 py-4 space-y-4">
            <div>
                <x-input-label value="Nombre *" />
                <x-text-input wire:model="campus.name" type="text" class="w-full" />
                @error('campus.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-input-label value="Dirección *" />
                <x-text-input wire:model="campus.address" type="text" class="w-full" />
                @error('campus.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-input-label value="Ciudad *" />
                <x-text-input wire:model="campus.city" type="text" class="w-full" />
                @error('campus.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Capacidad *" />
                    <x-text-input wire:model="campus.capacity" type="number" class="w-full" />
                    @error('campus.capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-input-label value="Estado *" />
                    <select wire:model="campus.status" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- PIE (Reemplazo de x-modal.footer) -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
            <x-secondary-button wire:click="$set('showModal', false)">
                Cancelar
            </x-secondary-button>

            <x-primary-button wire:click="save">
                {{ isset($campus['id']) && $campus['id'] ? 'Guardar Cambios' : 'Crear Sede' }}
            </x-primary-button>
        </div>

    </x-modal>
@endif
