@php
    $isAdmin = auth()->user()->role === 'ADM';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, darkMode: false }" :class="{ 'dark': darkMode }" >
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:livewire="http://www.w3.org/1999/html">--}}
    <head>
        <title>Administration — Camer.be</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

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
    <body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased flex h-screen overflow-hidden">
    <!-- SIDEBAR -->
    <aside
        class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 z-30"
        :class="sidebarOpen ? 'w-64' : 'w-20'"
    >
        <!-- Logo -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-9 h-9 rounded-lg bg-green-700 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    C
                </div>
                <span class="font-bold text-lg tracking-wide dark:text-white" x-show="sidebarOpen">
                    CAMER<span class="text-red-700">.BE</span>
                </span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-green-700/10 text-green-700 font-medium">
                <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Tableau de bord</span>
            </a>

            <a href="{{route('admin.article.create')}}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <div class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.article.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i data-lucide="newspaper" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Articles & Actualités</span>
                </div>

            </a>

            <a @if($isAdmin) href="{{route('admin.pubdimension.create')}}" @endif
                @unless($isAdmin) aria-disabled="true" tabindex="-1" @endunless
               class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.pubdimension.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="folder-tree" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Dimension</span>
            </a>
            <a href="{{route('admin.event.create')}}"
               class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.event.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="component" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Évènement</span>
            </a>
            <a href="{{route('admin.pub.create')}}"
               class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.pub.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="tent-tree" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Publicités</span>
            </a>
            <a href="{{route('admin.rubrique.create')}}"
               class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.rubrique.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="folder-tree" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Rubriques</span>
            </a>
            <a href="{{route('admin.sousrubrique.create')}}"
               class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.sousrubrique.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="folder" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Sous Rubriques</span>
            </a>
            <a href="{{route('admin.pubtype.create')}}"
               class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.pubtype.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="message-square" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Type Publicité</span>
            </a>

            <a href="{{route('admin.video.create')}}" class="flex items-center gap-3 px-3 py-2.5 transition rounded-lg {{ request()->routeIs('admin.video.*')
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i data-lucide="image" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Vidéos</span>
            </a>

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <span x-show="sidebarOpen" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administration</span>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 mt-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Journalistes & Staff</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Audience & Analytics</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Configuration</span>
                </a>
            </div>
        </nav>

        <!-- User Info / Logout -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name={{Auth::user()->nom}}&background=007A5E&color=fff" class="w-9 h-9 rounded-full flex-shrink-0" alt="Avatar">
            <div x-show="sidebarOpen" class="overflow-hidden">
                <p class="text-sm font-semibold truncate">{{Auth::user()->nom}} {{Auth::user()->prenom}}</p>
                <p class="text-xs text-gray-500 truncate">Administrateur</p>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- HEADER -->
        <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6">
            <!-- Search bar -->
            <div class="relative w-72">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <livewire:admin.article.search />
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-4">
                <!-- Nouveau bouton rapide -->
                <a href="{{route('admin.article.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Nouvel Article</span>
                </a>

                <!-- Mode Sombre Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i x-show="!darkMode" data-lucide="moon" class="w-5 h-5"></i>
                    <i x-show="darkMode" data-lucide="sun" class="w-5 h-5"></i>
                </button>

                <!-- Notifications -->
                <livewire:logout/>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">

            <!-- Title & Quick Stats -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Tableau de bord Rédaction</h1>
                    <p class="text-sm text-gray-500">Vue d'ensemble de l'activité sur Camer.be aujourd'hui.</p>
                </div>
                <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
                    <button class="px-3 py-1.5 rounded-md bg-gray-100 dark:bg-gray-700 font-medium">Aujourd'hui</button>
                    <button class="px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-900 dark:hover:text-white">7 jours</button>
                    <button class="px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-900 dark:hover:text-white">30 jours</button>
                </div>
            </div>

            <!-- METRIC CARDS -->
           <livewire:stat :stat="$stat"/>

            <!-- TABLE & SIDE SECTION -->
            <div class="lg:col-span-3">
                {{ $slot }}
            </div>

        </main>

        @livewireScripts
        @fluxScripts
        <script>
            lucide.createIcons();

            // Ré-exécuter Lucide après chaque mise à jour du DOM par Livewire
            document.addEventListener('livewire:navigated', () => {
                lucide.createIcons();
            });

            document.addEventListener('livewire:init', () => {
                Livewire.hook('morph.updated', ({ el, component }) => {
                    lucide.createIcons();
                });
            });
        </script>
        @persist('toast')
            <flux:toast />
        @endpersist
    </body>
</html>
