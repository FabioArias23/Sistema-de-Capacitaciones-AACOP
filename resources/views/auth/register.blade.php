<x-guest-layout>
    <!-- Reduje el espaciado vertical a space-y-3 (antes era 5) -->
    <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-xs font-medium text-slate-300 mb-1 pl-1">Nombre</label>
            <div class="relative">
                <!-- Input más compacto: py-2 (antes py-3) -->
                <input id="name" class="block w-full rounded-lg border-0 bg-slate-950/50 py-2 px-4 text-white text-sm shadow-inner ring-1 ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500 focus:bg-slate-950/80 transition-all"
                       type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tu nombre completo" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-medium text-slate-300 mb-1 pl-1">Email</label>
            <div class="relative">
                <input id="email" class="block w-full rounded-lg border-0 bg-slate-950/50 py-2 px-4 text-white text-sm shadow-inner ring-1 ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500 focus:bg-slate-950/80 transition-all"
                       type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@correo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-xs font-medium text-slate-300 mb-1 pl-1">Contraseña</label>
            <div class="relative">
                <input id="password" class="block w-full rounded-lg border-0 bg-slate-950/50 py-2 px-4 text-white text-sm shadow-inner ring-1 ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500 focus:bg-slate-950/80 transition-all"
                       type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirmar Contraseña -->
        <div>
            <label for="password_confirmation" class="block text-xs font-medium text-slate-300 mb-1 pl-1">Confirmar Contraseña</label>
            <div class="relative">
                <input id="password_confirmation" class="block w-full rounded-lg border-0 bg-slate-950/50 py-2 px-4 text-white text-sm shadow-inner ring-1 ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500 focus:bg-slate-950/80 transition-all"
                       type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Botón de Registro -->
        <div class="pt-2">
            <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold rounded-lg text-sm shadow-[0_0_15px_-5px_rgba(59,130,246,0.5)] transform transition-all duration-200 hover:scale-[1.01] hover:shadow-[0_0_20px_-5px_rgba(59,130,246,0.7)] focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                REGISTRARSE
            </button>
        </div>

        <!-- Enlace para volver al Login -->
        <div class="mt-3 text-center border-t border-white/10 pt-3">
            <a class="text-xs text-slate-400 hover:text-blue-400 transition-colors hover:underline underline-offset-4" href="{{ route('login') }}">
                ¿Ya estás registrado?
            </a>
        </div>
    </form>
</x-guest-layout>
