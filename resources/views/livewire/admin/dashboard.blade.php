<div
    class="min-h-screen bg-background flex"
    x-data="{ sidebarOpen: true, mobileMenuOpen: false }"
>
    <!-- Sidebar - Desktop -->
    <aside
        class="hidden md:flex flex-col bg-sidebar border-r border-sidebar-border transition-all duration-300"
        :class="sidebarOpen ? 'w-64' : 'w-20'"
    >
        <div class="p-4 border-b border-sidebar-border">
            <div :class="sidebarOpen ? '' : 'flex justify-center'">
                <h1 class="text-2xl font-bold text-primary" x-show="sidebarOpen">AACOP</h1>
                <h1 class="text-2xl font-bold text-primary" x-show="!sidebarOpen">A</h1>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <nav class="flex-1 p-3 space-y-1">
            @foreach ($navItems as $item)
                <a
                    href="{{ route('admin.dashboard', ['section' => $item['id']]) }}"
                    wire:navigate
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors
                        {{ $activeSection === $item['id'] ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}"
                >
                    <x-dynamic-component :component="'lucide-'.$item['icon']" class="w-5 h-5" />
                    <span x-show="sidebarOpen" class="text-sm font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="p-3 border-t border-sidebar-border">
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="w-full flex items-center justify-center gap-3 px-3 py-2.5 rounded-xl text-sidebar-foreground hover:bg-sidebar-accent"
            >
                <x-lucide-panel-left-close class="w-5 h-5" x-show="sidebarOpen" />
                <x-lucide-panel-left-open class="w-5 h-5" x-show="!sidebarOpen" />
            </button>
        </div>
    </aside>

    <!-- Mobile Menu Overlay -->
    <div
        x-show="mobileMenuOpen"
        @click="mobileMenuOpen = false"
        class="md:hidden fixed inset-0 bg-black/50 z-40"
        style="display: none;"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <aside
            @click.stop
            class="w-64 bg-sidebar h-full shadow-xl"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
        >
            <div class="p-4 border-b border-sidebar-border">
                <h1 class="text-2xl font-bold text-primary">AACOP</h1>
            </div>

            <!-- Mobile Navigation -->
            <nav class="flex-1 p-3 space-y-1">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route('admin.dashboard', ['section' => $item['id']]) }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors
                            {{ $activeSection === $item['id'] ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}"
                    >
                        <x-dynamic-component :component="'lucide-'.$item['icon']" class="w-5 h-5" />
                        <span class="text-sm font-medium">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-card border-b border-border sticky top-0 z-30 shadow-sm">
            <div class="px-4 md:px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button
                            @click="mobileMenuOpen = true"
                            class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                        >
                            <x-lucide-menu class="w-5 h-5" />
                        </button>
                        <div>
                            <h1 class="font-heading text-xl font-semibold text-foreground">
                                Panel de Administración
                            </h1>
                            @if(config('app.debug'))
                                <p class="text-red-500 text-xs">Sección Activa: {{ $activeSection }}</p>
                            @endif
                            <p class="text-muted-foreground text-sm">Sistema de Gestión de Capacitaciones AACOP</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-theme-toggle />
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-sm font-medium text-foreground">{{ $user->name }}</p>
                                <p class="text-xs text-muted-foreground">Administrador</p>
                            </div>
                            <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-primary rounded-full">
                                <span class="font-semibold text-primary-foreground">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <button
                            wire:click="logout"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all border bg-background text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-3"
                        >
                            <x-lucide-log-out class="w-4 h-4" />
                            <span class="hidden sm:inline">Salir</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-4 md:p-6 lg:p-8">
            @switch($activeSection)
                @case('dashboard')
                    <livewire:admin.dashboard-overview :key="'dashboard-overview-'.now()->timestamp" />
                    @break
                @case('catalog')
                    <livewire:admin.training-catalog :key="'training-catalog-'.now()->timestamp" />
                    @break
                @case('campus')
                    <livewire:admin.campus-management :key="'campus-management-'.now()->timestamp" />
                    @break
                @case('schedule')
                    <livewire:admin.training-schedule :key="'training-schedule-'.now()->timestamp" />
                    @break
                @case('participants')
                    <livewire:admin.participant-management :key="'participant-management-'.now()->timestamp" />
                    @break
                @case('attendance')
                    <livewire:admin.attendance-management :key="'attendance-management-'.now()->timestamp" />
                    @break
                @case('certificates')
                    <livewire:admin.certificate-management :key="'certificate-management-'.now()->timestamp" />
                    @break
                @case('reports')
                    <livewire:admin.training-reports :key="'reports-'.now()->timestamp" />
                    @break
                @default
                    <livewire:admin.dashboard-overview :key="'dashboard-default-'.now()->timestamp" />
            @endswitch
        </main>
    </div>
</div>
