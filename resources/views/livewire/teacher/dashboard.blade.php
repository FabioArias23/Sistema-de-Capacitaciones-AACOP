<div
    class="min-h-screen bg-gray-50 dark:bg-gray-900 flex transition-colors duration-300"
    x-data="{
        sidebarOpen: true,
        mobileMenuOpen: false,
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        init() {
            if (this.darkMode) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        }
    }"
    x-init="init()"
>
    <!-- 1. SIDEBAR (Barra Lateral Oscura) -->
    <aside class="hidden md:flex flex-col bg-slate-900 border-r border-slate-800 transition-all duration-300"
           :class="sidebarOpen ? 'w-64' : 'w-20'">

        <!-- Logo con Imagen -->
        <div class="p-4 border-b border-slate-800 flex items-center justify-center h-16 bg-slate-900">
            <!-- ESTADO ABIERTO -->
            <div x-show="sidebarOpen" class="transition-opacity duration-300">
                <img
                    src="{{ asset('images/logo-formosa.png') }}"
                    alt="AACOP Formosa"
                    class="h-10 w-auto object-contain"
                >
            </div>
            <!-- ESTADO CERRADO -->
            <div x-show="!sidebarOpen" style="display: none;" class="transition-opacity duration-300">
                <img
                    src="{{ asset('images/logo-formosa.png') }}"
                    alt="AACOP"
                    class="h-8 w-8 object-contain"
                >
            </div>
        </div>

        <!-- Menú de Navegación -->
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            @foreach ($navItems as $item)
                <a
                    href="{{ route('teacher.dashboard', ['section' => $item['id']]) }}"
                    wire:navigate
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors group
                        {{ $activeSection === $item['id']
                            ? 'bg-blue-600 text-white shadow-md'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                >
                    <x-dynamic-component :component="'lucide-'.$item['icon']" class="w-5 h-5" />
                    <span x-show="sidebarOpen" class="text-sm font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Botón colapsar -->
        <div class="p-3 border-t border-slate-800">
            <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <x-lucide-panel-left-close class="w-5 h-5" x-show="sidebarOpen" />
                <x-lucide-panel-left-open class="w-5 h-5" x-show="!sidebarOpen" />
            </button>
        </div>
    </aside>

    <!-- Menú Móvil (Overlay) -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="md:hidden fixed inset-0 bg-black/50 z-40" style="display: none;">
        <aside class="w-64 bg-slate-900 h-full shadow-xl" @click.stop>
            <div class="p-4 border-b border-slate-800 flex justify-center">
                 <img src="{{ asset('images/logo-formosa.png') }}" class="h-10 w-auto">
            </div>
            <nav class="flex-1 p-3 space-y-1">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route('teacher.dashboard', ['section' => $item['id']]) }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors
                            {{ $activeSection === $item['id'] ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <x-dynamic-component :component="'lucide-'.$item['icon']" class="w-5 h-5" />
                        <span class="text-sm font-medium">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>
    </div>

    <!-- 2. CONTENIDO PRINCIPAL -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden relative">

        <!-- HEADER SUPERIOR -->
        <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30 h-16 transition-colors duration-300">
            <div class="px-6 h-full flex items-center justify-between">

                <!-- Títulos (Lado Izquierdo) -->
                <div class="flex items-center gap-4">
                    <!-- Botón móvil -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                        <x-lucide-menu class="w-6 h-6" />
                    </button>

                    <div class="flex flex-col justify-center">
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                            Panel de Docente
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Gestión de Clases y Estudiantes
                        </p>
                    </div>
                </div>

                <!-- Herramientas (Lado Derecho) -->
                <div class="flex items-center gap-4 sm:gap-6">

                    <!-- BOTÓN DE TEMA (Luna/Sol) -->
                    <button
                        @click="toggleTheme()"
                        class="p-2 rounded-lg transition-colors focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-yellow-400"
                        title="Cambiar tema"
                    >
                        <!-- Sol (Visible en Dark Mode) -->
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41-1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>

                        <!-- Luna (Visible en Light Mode) -->
                        <svg x-show="!darkMode" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </button>

                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

                    <!-- Usuario -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase mt-1">Docente</p>
                        </div>

                        <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm border-2 border-transparent dark:border-gray-600">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>

                        <button
                            wire:click="logout"
                            class="ml-2 p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors dark:text-gray-400 dark:hover:bg-red-900/30 dark:hover:text-red-400"
                            title="Cerrar Sesión"
                        >
                            <x-lucide-log-out class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Área de Contenido -->
        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8 text-gray-900 dark:text-gray-100">
            @if ($activeSection === 'dashboard')
                <livewire:teacher.dashboard-overview wire:key="teacher-dashboard" />
            @elseif ($activeSection === 'classes')
                <livewire:teacher.class-list wire:key="teacher-classes" />
            @elseif ($activeSection === 'attendance')
                <livewire:teacher.attendance wire:key="teacher-attendance" />
            @elseif ($activeSection === 'grades')
                <livewire:teacher.grades wire:key="teacher-grades" />
            @endif
        </main>
    </div>
</div>
