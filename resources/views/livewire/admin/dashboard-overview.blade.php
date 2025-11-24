<div class="space-y-6">
    <!-- Título de la sección -->
    <div>

        <p class="text-muted-foreground">Resumen general del sistema</p>
    </div>

    <!-- Grid de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Reemplazamos el .map() de React con un @foreach de Blade --}}
        @foreach ($metrics as $metric)
            <div class="bg-card border border-border rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <p class="text-muted-foreground text-sm mb-2">{{ $metric['label'] }}</p>
                <div class="flex items-end justify-between">
                    {{-- Usamos la clase de color dinámica del array --}}
                    <h3 class="{{ $metric['color'] }} font-bold font-heading text-3xl">
                        {{ $metric['value'] }}
                    </h3>
                    <span class="text-[#00A885] text-sm font-semibold">{{ $metric['trend'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tarjeta de Actividad Reciente -->
    <div class="bg-card border border-border rounded-2xl p-6 shadow-sm">
        <h3 class="font-semibold mb-4 font-heading text-lg">
            Actividad Reciente
        </h3>
        <div class="space-y-3">
            @foreach ($recentActivities as $activity)
                <div class="flex justify-between items-start p-3 bg-muted/50 rounded-xl">
                    <p class="text-sm">{{ $activity['text'] }}</p>
                    <span class="text-xs text-muted-foreground whitespace-nowrap ml-4">{{ $activity['time'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

