<!-- resources/views/components/theme-toggle.blade.php -->
<button
    @click="darkMode = !darkMode"
    class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors h-9 w-9 hover:bg-muted"
    aria-label="Toggle theme"
>
    <x-lucide-moon class="w-5 h-5" x-show="!darkMode" />
    <x-lucide-sun class="w-5 h-5" x-show="darkMode" style="display: none;" />
</button>
