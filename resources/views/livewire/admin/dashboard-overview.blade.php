<div class="space-y-6">
    <div>
        <p class="text-muted-foreground dark:text-gray-400">Resumen general del sistema</p>
    </div>

    <!-- Grid de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($metrics as $metric)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all">
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">{{ $metric['label'] }}</p>
                <div class="flex items-end justify-between">
                    <h3 class="{{ $metric['color'] }} font-bold font-heading text-3xl">
                        {{ $metric['value'] }}
                    </h3>
                    <span class="text-[#00A885] text-sm font-semibold">{{ $metric['trend'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tarjeta de Actividad Reciente -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm transition-colors">
        <h3 class="font-semibold mb-4 font-heading text-lg text-gray-900 dark:text-white">
            Actividad Reciente
        </h3>
        <div class="space-y-3">
            @foreach ($recentActivities as $activity)
                <div class="flex justify-between items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl transition-colors">
                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $activity['text'] }}</p>
                    <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap ml-4">{{ $activity['time'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
