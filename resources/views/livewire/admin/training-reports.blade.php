<div class="space-y-6">
    <!-- Encabezado y Tarjetas de Métricas (sin cambios) -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Reportes y Análisis</h2>
        <p class="text-muted-foreground">Visualiza métricas y estadísticas de capacitaciones.</p>
    </div>
    <div class="grid md:grid-cols-4 gap-4">
        <div class="bg-card p-6 rounded-2xl border-2"><p class="text-sm text-muted-foreground">Total Participantes</p><h3 class="text-3xl font-bold mt-2">{{ $totalParticipants }}</h3></div>
        <div class="bg-card p-6 rounded-2xl border-2"><p class="text-sm text-muted-foreground">Capacitaciones</p><h3 class="text-3xl font-bold mt-2">{{ $totalTrainings }}</h3></div>
        <div class="bg-card p-6 rounded-2xl border-2"><p class="text-sm text-muted-foreground">Tasa Finalización</p><h3 class="text-3xl font-bold mt-2">{{ $completionRate }}%</h3></div>
        <div class="bg-card p-6 rounded-2xl border-2"><p class="text-sm text-muted-foreground">Nota Promedio</p><h3 class="text-3xl font-bold mt-2">{{ $averageGrade }}%</h3></div>
    </div>

    <!-- Grid de Gráficos con Chart.js y Alpine.js -->
    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Gráfico de Líneas: Inscripciones vs Completadas -->
        <div class="bg-card p-6 rounded-2xl border-2">
            <h3 class="font-semibold mb-4">Inscripciones vs Completadas</h3>
            <div
                x-data="{
                    data: @json($enrollmentsChartData),
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'line',
                            data: this.data,
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    }
                }"
            >
                <canvas x-ref="canvas" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Gráfico Circular: Capacitaciones por Categoría -->
        <div class="bg-card p-6 rounded-2xl border-2">
            <h3 class="font-semibold mb-4">Capacitaciones por Categoría</h3>
            <div
                x-data="{
                    data: @json($categoryChartData),
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'pie',
                            data: this.data,
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    }
                }"
            >
                <canvas x-ref="canvas" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Espacio para el gráfico de departamentos -->
        <div class="bg-card p-6 rounded-2xl border-2">
            <h3 class="font-semibold text-center">Participación por Departamento</h3>
            <p class="text-muted-foreground text-sm text-center mt-4">(Gráfico pendiente)</p>
        </div>
    </div>
</div>
