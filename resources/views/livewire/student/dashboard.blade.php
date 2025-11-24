<div
    class="min-h-screen bg-background flex"
    x-data="{ sidebarOpen: true, mobileMenuOpen: false }"
>
    <!-- Sidebar -->
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

        <nav class="flex-1 p-3 space-y-1">
            @foreach ($navItems as $item)
                <a
                    href="{{ route('student.dashboard', ['section' => $item['id']]) }}"
                    wire:navigate
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors"
                    :class="'{{ $activeSection }}' === '{{ $item['id'] }}'
                        ? 'bg-sidebar-primary text-sidebar-primary-foreground'
                        : 'text-sidebar-foreground hover:bg-sidebar-accent'"
                >
                    <x-dynamic-component :component="'lucide-'.$item['icon']" class="w-5 h-5" />
                    <span x-show="sidebarOpen" class="text-sm font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="p-3 border-t border-sidebar-border">
            <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center gap-3 px-3 py-2.5 rounded-xl text-sidebar-foreground hover:bg-sidebar-accent">
                <x-lucide-x class="w-5 h-5" x-show="sidebarOpen" />
                <x-lucide-menu class="w-5 h-5" x-show="!sidebarOpen" />
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-card border-b border-border sticky top-0 z-30 shadow-sm">
            <div class="px-4 md:px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="font-heading text-xl font-semibold text-foreground">
                            Portal del Estudiante
                        </h1>
                        <p class="text-muted-foreground text-sm">Mi Centro de Aprendizaje</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-theme-toggle />
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-sm font-medium text-foreground">{{ $user->name }}</p>
                                <p class="text-xs text-muted-foreground">Estudiante</p>
                            </div>
                            <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-primary rounded-full">
                                <span class="font-semibold text-primary-foreground">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <button wire:click="logout" class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border bg-background h-9 px-3">
                            <x-lucide-log-out class="w-4 h-4" />
                            <span class="hidden sm:inline">Salir</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-4 md:p-6 lg:p-8">
            @if ($activeSection === 'dashboard')
                <livewire:student.dashboard-overview wire:key="student-dashboard" />
            @elseif ($activeSection === 'courses')
                <livewire:student.available-courses wire:key="student-courses" />
            @elseif ($activeSection === 'enrollments')
                <livewire:student.enrollments wire:key="student-enrollments" />
            @elseif ($activeSection === 'progress')
                <livewire:student.progress wire:key="student-progress" />
            @endif
        </main>
    </div>
</div>
