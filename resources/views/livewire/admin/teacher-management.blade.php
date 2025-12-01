<div
    class="max-w-7xl mx-auto space-y-6"
    x-data="{ openModal: @js($errors->any()) }"
>
    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">
                Docentes
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Administra los docentes habilitados para dictar capacitaciones.
            </p>
        </div>

        <button
            type="button"
            @click="openModal = true"
            class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
        >
            + Nuevo docente
        </button>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session()->has('success'))
        <div class="rounded-md border border-emerald-400 bg-emerald-50 text-emerald-800 dark:border-emerald-500 dark:bg-emerald-900/40 dark:text-emerald-200 px-4 py-2 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Errores --}}
    @if ($errors->any())
        <div class="rounded-md border border-red-400 bg-red-50 text-red-800 dark:border-red-500 dark:bg-red-900/40 dark:text-red-200 px-4 py-2 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Listado de docentes --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-800/80">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">
                        Nombre
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">
                        Email
                    </th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($docentes as $docente)
                    <tr class="bg-white dark:bg-slate-900">
                        <td class="px-4 py-2 text-sm text-slate-900 dark:text-slate-100">
                            {{ $docente->name }}
                        </td>
                        <td class="px-4 py-2 text-sm text-slate-600 dark:text-slate-300">
                            {{ $docente->email }}
                        </td>
                        <td class="px-4 py-2 text-sm text-right space-x-2">
                            <form
                                method="POST"
                                action="{{ route('admin.docentes.destroy', $docente) }}"
                                class="inline"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este docente?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 text-xs font-medium"
                                >
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-slate-900">
                        <td colspan="3" class="px-4 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                            No hay docentes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL: Nuevo docente --}}
    <div
        x-show="openModal"
        x-cloak
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/60"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            @click.away="openModal = false"
            class="w-full max-w-lg mx-4 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="scale-95 translate-y-2"
            x-transition:enter-end="scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="scale-100 translate-y-0"
            x-transition:leave-end="scale-95 translate-y-2"
        >
            {{-- Header modal --}}
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-base font-semibold">
                    Nuevo docente
                </h3>
                <button
                    type="button"
                    @click="openModal = false"
                    class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 text-sm"
                >
                    ✕
                </button>
            </div>

            {{-- Body modal --}}
            <div class="px-6 py-4">
                <form method="POST" action="{{ route('admin.docentes.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-200">
                                Nombre completo
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="w-full rounded-md bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-200">
                                Correo electrónico
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-md bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-200">
                                Contraseña
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="w-full rounded-md bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-200">
                                Confirmar contraseña
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full rounded-md bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                            >
                        </div>
                    </div>

                    {{-- Footer modal --}}
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="openModal = false"
                            class="px-4 py-2 rounded-md bg-slate-200 dark:bg-slate-800 text-sm text-slate-800 dark:text-slate-100 hover:bg-slate-300 dark:hover:bg-slate-700"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                        >
                            Guardar docente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
