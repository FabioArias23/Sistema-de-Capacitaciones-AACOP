<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1 pl-1">Email</label>
            <div class="relative">
                <input id="email" class="block w-full rounded-xl border-0 bg-slate-950/50 py-3 px-4 text-white shadow-inner ring-1 ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500 focus:bg-slate-950/80 transition-all"
                       type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="ejemplo@correo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1 pl-1">Contraseña</label>
            <div class="relative">
                <input id="password" class="block w-full rounded-xl border-0 bg-slate-950/50 py-3 px-4 text-white shadow-inner ring-1 ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500 focus:bg-slate-950/80 transition-all"
                       type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded bg-slate-800 border-slate-600 text-blue-500 shadow-sm focus:ring-blue-500 focus:ring-offset-slate-900 transition-colors" name="remember">
                <span class="ms-2 text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium hover:underline underline-offset-4" href="{{ route('password.request') }}">
                    ¿Olvidaste tu clave?
                </a>
            @endif
        </div>

        <!-- Botón de Login -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold rounded-xl shadow-[0_0_20px_-5px_rgba(59,130,246,0.5)] transform transition-all duration-200 hover:scale-[1.02] hover:shadow-[0_0_25px_-5px_rgba(59,130,246,0.7)] focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                INGRESAR
            </button>
        </div>

        <!-- NUEVA SECCIÓN: Registro -->
        @if (Route::has('register'))
            <div class="mt-6 text-center border-t border-white/10 pt-4">
                <p class="text-sm text-slate-400">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-400 hover:text-blue-300 transition-colors hover:underline decoration-2 underline-offset-4 ml-1">
                        Crear cuenta nueva
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
