<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<header class="sticky top-0 z-50 bg-white dark:bg-dark-surface border-b border-gray-200 dark:border-dark-border shadow-sm">
    <!-- Topbar info -->
    <div class="bg-gray-900 text-white text-xs py-1.5 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span>📅 Mardi 28 Juillet 2026</span>
                <span class="hidden sm:inline-block text-gray-400">|</span>
                <span class="hidden sm:inline-block text-highlight-500 font-semibold">📍 Yaoundé, 28°C</span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="#" class="hover:text-highlight-500 transition">Direct TV</a>
                <span>|</span>
                <a href="#" class="hover:text-highlight-500 transition">Contact</a>
            </div>
        </div>
    </div>

    <!-- Navigation Principale -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="#" class="flex items-center space-x-2 group">
                    <span class="text-3xl font-extrabold font-heading tracking-tight text-brand-500 group-hover:text-brand-600 transition">
                        CAMER<span class="text-accent-500">.BE</span>
                    </span>
                <span class="hidden md:inline-block text-[10px] bg-brand-500 text-white font-bold px-1.5 py-0.5 rounded uppercase">
                        Info 24/7
                    </span>
            </a>

            <!-- Nav Links Desktop -->
            <nav class="hidden lg:flex items-center space-x-8 font-medium text-sm">
                <a href="#" class="text-brand-500 font-bold border-b-2 border-brand-500 py-1">Accueil</a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">Politique</a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">Économie</a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">Société</a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">Sports</a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">Culture</a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">Diaspora</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-4">
                <button aria-label="Rechercher" class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <a href="#" class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-gray-900 bg-highlight-500 hover:bg-highlight-600 rounded-md shadow transition">
                    Abonnez-vous
                </a>
            </div>
        </div>
    </div>
</header>
