<div class="space-y-6">
    <!-- Título -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Dashboard</h2>
        <p class="text-muted-foreground">Tu progreso académico.</p>
    </div>

    <!-- Grid de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($metrics as $metric)
            <div class="bg-card border-2 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <p class="text-muted-foreground text-sm mb-2">{{ $metric['label'] }}</p>
                <h3 class="{{ $metric['color'] }} font-bold font-heading text-3xl">
                    {{ $metric['value'] }}
                </h3>
            </div>
        @endforeach
    </div>

    <!-- Tarjeta de Próximas Capacitaciones -->
    <div class="bg-card border-2 rounded-2xl p-6 shadow-sm">
        <h3 class="font-semibold mb-4 font-heading text-lg">Próximas Capacitaciones</h3>
        <div class="space-y-3">
            @forelse ($upcomingEnrollments as $enrollment)
                <div class="flex justify-between items-center p-4 bg-muted/50 rounded-xl">
                    <div>
                        <p class="font-medium">{{ $enrollment->trainingSession->training_title }}</p>
                        <p class="text-sm text-muted-foreground">{{ $enrollment->trainingSession->campus_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($enrollment->trainingSession->date)->translatedFormat('d M') }}</p>
                        <p class="text-xs text-muted-foreground">{{ \Carbon\Carbon::parse($enrollment->trainingSession->start_time)->format('H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-muted-foreground text-center py-4">No tienes capacitaciones próximas.</p>
            @endforelse
        </div>
    </div>
</div>
