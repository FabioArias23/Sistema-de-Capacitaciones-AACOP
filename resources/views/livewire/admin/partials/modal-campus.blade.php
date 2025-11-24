@if ($campusDialog)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click="$set('campusDialog', false)">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6" @click.stop>

            <h2 class="text-lg font-semibold mb-4 text-gray-900"> {{ $campusForm['id'] ? 'Editar Sede' : 'Nueva Sede' }}
            </h2>

            <div class="space-y-4">

                <div>
                    <label class="text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text"
                        wire:model="campusForm.name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Ciudad *</label>
                    <input type="text"
                        wire:model="campusForm.city"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Dirección *</label>
                    <input type="text"
                        wire:model="campusForm.address"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Capacidad *</label>
                    <input type="number"
                        wire:model="campusForm.capacity"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Estado *</label>
                    <select wire:model="campusForm.status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('campusDialog', false)"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    Cancelar
                </button>

                <button wire:click="saveCampus"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Guardar
                </button>
            </div>

        </div>
    </div>
@endif
