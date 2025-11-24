<div>
    <x-modal wire:model="trainingDialog">
        <x-modal.header>
            {{ $trainingForm['id'] ? 'Editar Capacitación' : 'Nueva Capacitación' }}
        </x-modal.header>

        <x-modal.body class="space-y-4">

            <div>
                <x-input-label value="Título" />
                <x-text-input wire:model="trainingForm.titulo" type="text" class="w-full" />
                @error('trainingForm.titulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-input-label value="Descripción" />
                <textarea wire:model="trainingForm.descripcion" class="w-full rounded-lg border-gray-300"></textarea>
                @error('trainingForm.descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

        </x-modal.body>

        <x-modal.footer>
            <x-secondary-button wire:click="$set('trainingDialog', false)">
                Cancelar
            </x-secondary-button>

            <x-primary-button wire:click="saveTraining">
                Guardar
            </x-primary-button>
        </x-modal.footer>
    </x-modal>
</div>
