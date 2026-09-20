<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:livewire="http://www.w3.org/1999/html">--}}
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance

        {{-- =========================================================
        14. GOOGLE ANALYTICS
        CHARGEMENT APRÈS LE CHEMIN CRITIQUE
        ========================================================= --}}

        <script
            async
            src="https://www.googletagmanager.com/gtag/js?id=G-K905N6XENX">
        </script>

        {{-- =========================================================
        15. ADSENSE
        SCRIPT ASYNCHRONE ET NON BLOQUANT
        ========================================================= --}}


    </head>
    <body class="bg-gray-50 text-gray-900 dark:bg-dark-bg dark:text-gray-100 font-sans antialiased transition-colors duration-200">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>
        @livewireScripts
        @fluxScripts
    </body>
</html>
