<div class="space-y-6">
    <!-- Encabezado -->
    <div>
        <h2 class="font-heading text-2xl font-semibold">Gestión de Certificados</h2>
        <p class="text-muted-foreground">Genera, visualiza e imprime certificados de aprobación.</p>
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Sección para Generar Certificados -->
    <div class="bg-card border-2 rounded-2xl p-6">
        <h3 class="font-heading font-semibold">Pendientes de Generación</h3>
        <p class="text-muted-foreground text-sm mb-4">Estudiantes que han aprobado y están listos para recibir su certificado.</p>
        <div class="space-y-3">
            @forelse($pendingEnrollments as $enrollment)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-muted/50 rounded-xl">
                    <div>
                        <p class="font-medium">{{ $enrollment->user->name }}</p>
                        <p class="text-sm text-muted-foreground">{{ $enrollment->trainingSession->training_title }} (Nota: {{ $enrollment->grade }}%)</p>
                    </div>
                    <button wire:click="generateCertificate({{ $enrollment->id }})" class="mt-2 sm:mt-0 inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-primary text-primary-foreground h-9 px-4 py-2">
                        <x-lucide-award class="w-4 h-4 mr-1" />
                        Generar Certificado
                    </button>
                </div>
            @empty
                <p class="text-sm text-muted-foreground text-center py-4">No hay certificados pendientes de generación.</p>
            @endforelse
        </div>
    </div>

    <!-- Sección de Certificados Emitidos -->
    <div class="bg-card border-2 rounded-2xl p-6">
        <h3 class="font-heading font-semibold">Certificados Emitidos</h3>
        <div class="relative mt-4">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <input wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar por estudiante, capacitación o N°..." class="border-input flex h-9 w-full rounded-xl border bg-transparent px-3 py-1 text-sm pl-10">
        </div>
        <div class="rounded-xl border border-border overflow-hidden mt-4">
            <table class="w-full">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">N° Certificado</th>
                        <th class="p-3 text-left text-sm font-semibold">Estudiante</th>
                        <th class="p-3 text-left text-sm font-semibold">Capacitación</th>
                        <th class="p-3 text-left text-sm font-semibold">Fecha</th>
                        <th class="p-3 text-center text-sm font-semibold">Nota</th>
                        <th class="p-3 text-right text-sm font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($certificates as $certificate)
                        <tr class="border-b last:border-b-0">
                            <td class="p-3"><code class="text-xs bg-muted px-2 py-1 rounded">{{ $certificate->certificate_number }}</code></td>
                            <td class="p-3">{{ $certificate->student_name }}</td>
                            <td class="p-3">{{ $certificate->training_title }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($certificate->completion_date)->format('d/m/Y') }}</td>
                            <td class="p-3 text-center"><span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-semibold bg-green-600 text-white">{{ $certificate->grade }}%</span></td>
                            <td class="p-3 text-right">
                                <button wire:click="showPreview({{ $certificate->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium border bg-background h-8 px-3">
                                    <x-lucide-eye class="w-4 h-4 mr-1" /> Ver
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-6 text-center text-muted-foreground">No se encontraron certificados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Previsualización -->
    @if($previewDialogOpen && $previewCertificate)
        <div class="fixed inset-0 bg-black/50 z-50" x-data @click="$wire.set('previewDialogOpen', false)">
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-0 rounded-lg shadow-lg w-full max-w-4xl" @click.stop>
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h3 class="text-gray-900 font-semibold">Vista Previa del Certificado</h3>
                    <div>
                        <button @click="window.print()" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground h-9 px-4 py-2">
                            <x-lucide-printer class="w-4 h-4 mr-2" /> Imprimir
                        </button>
                        <button @click="$wire.set('previewDialogOpen', false)" class="ml-2 inline-flex items-center justify-center rounded-md text-sm font-medium border bg-background h-9 w-9">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                </div>
                <div class="p-8" id="certificate-print">
                    <div class="border-8 border-double border-blue-900 p-12 space-y-8 text-gray-800">
                        <div class="text-center space-y-4">
                            <h1 class="text-blue-900 text-4xl font-bold">CERTIFICADO DE APROBACIÓN</h1>
                            <p class="text-gray-600 text-lg">Otorgado a</p>
                        </div>
                        <div class="text-center space-y-6">
                            <h2 class="text-gray-900 text-5xl border-b-2 border-blue-900 pb-2 inline-block px-8">{{ $previewCertificate->student_name }}</h2>
                            <p class="text-gray-700 text-xl max-w-2xl mx-auto leading-relaxed">Por haber completado satisfactoriamente el programa de capacitación</p>
                            <h3 class="text-blue-900 text-3xl font-semibold">{{ $previewCertificate->training_title }}</h3>
                            <div class="grid grid-cols-2 gap-8 max-w-2xl mx-auto pt-4">
                                <div>
                                    <p class="text-gray-600">Calificación Final</p>
                                    <p class="text-2xl text-blue-900 font-bold">{{ $previewCertificate->grade }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Fecha de Culminación</p>
                                    <p class="text-2xl text-blue-900 font-bold">{{ \Carbon\Carbon::parse($previewCertificate->completion_date)->translatedFormat('d \\de F \\de Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-4">
                            <p class="text-gray-500 text-sm">Certificado N° {{ $previewCertificate->certificate_number }}</p>
                            <p class="text-gray-500 text-xs mt-1">Asociación Argentina de Coaching Ontológico Profesional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
