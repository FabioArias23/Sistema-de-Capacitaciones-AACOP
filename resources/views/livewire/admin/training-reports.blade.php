<div
    class="space-y-6 p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen"
    x-data="{
        charts: {},

        init() {
            // Esperar un tick para asegurar que el DOM esté listo
            this.$nextTick(() => {
                this.renderCharts();
            });

            // Opcional: Escuchar cambios de tema para actualizar colores
            window.addEventListener('theme-changed', () => this.renderCharts());
        },

        renderCharts() {
            // Destruir gráficos anteriores si existen para evitar errores de canvas
            Object.values(this.charts).forEach(chart => chart.destroy());

            // Configuración de colores según el tema actual
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            const gridColor = isDark ? '#374151' : '#f3f4f6';

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: textColor }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            };

            // Opciones específicas para gráfico de torta (sin ejes)
            const pieOptions = {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { usePointStyle: true, color: textColor }
                    }
                },
                layout: { padding: 10 }
            };

            // Opciones específicas para gráfico horizontal
            const horizontalOptions = {
                ...commonOptions,
                indexAxis: 'y',
                scales: {
                    x: {
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            };

            // 1. Gráfico de Línea
            if (this.$refs.chartLine) {
                this.charts.line = new Chart(this.$refs.chartLine, {
                    type: 'line',
                    data: @js($enrollmentsChartData),
                    options: {
                        ...commonOptions,
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // 2. Gráfico de Torta
            if (this.$refs.chartPie) {
                this.charts.pie = new Chart(this.$refs.chartPie, {
                    type: 'doughnut',
                    data: @js($categoryChartData),
                    options: pieOptions
                });
            }

            // 3. Gráfico de Barras (Sedes)
            if (this.$refs.chartBar) {
                this.charts.bar = new Chart(this.$refs.chartBar, {
                    type: 'bar',
                    data: @js($departmentChartData),
                    options: {
                        ...commonOptions,
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // 4. Gráfico Horizontal (Notas)
            if (this.$refs.chartHorizontal) {
                this.charts.horizontal = new Chart(this.$refs.chartHorizontal, {
                    type: 'bar',
                    data: @js($gradesChartData),
                    options: horizontalOptions
                });
            }
        }
    }"
>

    <!-- 1. Encabezado -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Reportes y Análisis</h2>
            <p class="text-gray-500 dark:text-gray-400">Visualiza métricas y estadísticas de capacitaciones</p>
        </div>

        <button
            wire:click="export"
            wire:loading.attr="disabled"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50"
        >
            <span wire:loading.remove wire:target="export" class="flex items-center gap-2">
                <x-lucide-download class="w-4 h-4" />
                Exportar Reporte
            </span>
            <span wire:loading wire:target="export" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Generando...
            </span>
        </button>
    </div>

    <!-- 2. Tarjetas de Métricas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Participantes</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalParticipants }}</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-green-600">
                <span>Activos</span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Capacitaciones</p>
            <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $totalTrainings }}</h3>
            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">Totales registradas</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tasa Finalización</p>
            <h3 class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $completionRate }}%</h3>
            <div class="mt-4 flex items-center text-xs font-medium text-green-600">
                <span>Global</span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nota Promedio</p>
            <h3 class="text-3xl font-bold text-amber-500 dark:text-amber-400 mt-2">{{ $averageGrade }}</h3>
            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">Escala 1-10</p>
        </div>
    </div>

    <!-- 3. Fila Central: Gráficos -->
    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Gráfico 1: Línea -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-6">Inscripciones Históricas</h3>
            <!-- wire:ignore es CRUCIAL aquí para que Livewire no borre el canvas al actualizarse -->
            <div class="relative w-full h-64" wire:ignore>
                <canvas x-ref="chartLine"></canvas>
            </div>
        </div>

        <!-- Gráfico 2: Torta -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-6">Capacitaciones por Categoría</h3>
            <div class="relative w-full h-64 flex justify-center" wire:ignore>
                <canvas x-ref="chartPie"></canvas>
            </div>
        </div>
    </div>

    <!-- 4. Fila Inferior: Gráficos -->
    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Gráfico 3: Barras -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-6">Participación por Sede</h3>
            <div class="relative w-full h-64" wire:ignore>
                <canvas x-ref="chartBar"></canvas>
            </div>
        </div>

        <!-- Gráfico 4: Horizontal -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-6">Distribución de Calificaciones</h3>
            <div class="relative w-full h-64" wire:ignore>
                <canvas x-ref="chartHorizontal"></canvas>
            </div>
        </div>
    </div>

    <!-- 5. Resumen Ejecutivo -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Resumen Ejecutivo</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Análisis del periodo actual</p>

        <div class="space-y-4">
            <div>
                <h4 class="text-xs font-bold text-blue-700 dark:text-blue-400 border-l-4 border-blue-500 pl-2 mb-2">Estado del Sistema</h4>
                <ul class="list-disc list-inside text-xs text-gray-600 dark:text-gray-300 space-y-1 ml-1">
                    <li>Total de usuarios registrados: {{ $totalParticipants }}</li>
                    <li>Promedio de calificaciones actual: {{ $averageGrade }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
