<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Fondo oscuro base */
            .night-bg {
                background: radial-gradient(ellipse at bottom, #0f172a 0%, #020617 100%);
            }

            /* Nieve */
            .snowflake {
                position: absolute;
                top: -10px;
                background: white;
                border-radius: 50%;
                opacity: 0.8;
                pointer-events: none;
                animation: fall linear infinite;
            }
            @keyframes fall {
                0% { transform: translateY(-10vh) translateX(0); opacity: 0.8; }
                100% { transform: translateY(110vh) translateX(20px); opacity: 0.1; }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased night-bg relative overflow-hidden selection:bg-blue-500 selection:text-white">

        <!-- Nieve -->
        <div id="snow-container" class="fixed inset-0 z-0 pointer-events-none"></div>

        <!-- Contenedor Principal -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 relative z-10">

            <!-- Logo -->
            <div class="mb-8 text-center">
                <a href="/" class="flex flex-col items-center justify-center gap-4 group">
                    <div class="bg-slate-900/50 backdrop-blur-xl p-4 rounded-2xl shadow-[0_0_40px_-10px_rgba(59,130,246,0.5)] ring-1 ring-white/10 transition-all duration-500 group-hover:scale-110 group-hover:ring-blue-500/50">
                        <img
                            src="{{ asset('images/logo-formosa.png') }}"
                            alt="AACOP Formosa"
                            class="w-24 h-auto object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]"
                        >
                    </div>
                    <span class="text-3xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 drop-shadow-sm">
                        Sistema de Gestión de Capacitaciones
                    </span>
                </a>
            </div>

            <!-- TARJETA DEL LOGIN (Glassmorphism) -->
            <div class="w-full sm:max-w-md mt-2 p-8
                        bg-slate-900/60 backdrop-blur-xl
                        border border-white/10 rounded-3xl
                        shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)]
                        relative overflow-hidden group">

                <!-- Efecto de brillo superior -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-50 group-hover:opacity-100 transition-opacity duration-500"></div>

                <!-- Slot del formulario -->
                <div class="relative z-10">
                    {{ $slot }}
                </div>

                <!-- Decoración de fondo sutil dentro de la tarjeta -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <!-- Footer -->
            <div class="mt-10 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    &copy; {{ date('Y') }} AACOP. Todos los derechos reservados.
                </p>
            </div>
        </div>

        <!-- Script Nieve -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('snow-container');
                const snowflakeCount = 40;
                for (let i = 0; i < snowflakeCount; i++) {
                    const snowflake = document.createElement('div');
                    snowflake.classList.add('snowflake');
                    const size = Math.random() * 3 + 2 + 'px';
                    snowflake.style.width = size;
                    snowflake.style.height = size;
                    snowflake.style.left = Math.random() * 100 + 'vw';
                    snowflake.style.animationDuration = Math.random() * 15 + 10 + 's';
                    snowflake.style.animationDelay = Math.random() * 5 + 's';
                    snowflake.style.opacity = Math.random() * 0.4 + 0.1;
                    container.appendChild(snowflake);
                }
            });
        </script>
    </body>
</html>
