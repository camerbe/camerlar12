<?php

use Livewire\Component;

new class extends Component
{
    //
    public string $query = '';
    public bool $searchOpen = false;
};
?>

<div>
    <div class="flex items-center space-x-3 sm:space-x-4">
        <!-- Formulaire de recherche Google stylisé avec Tailwind -->
        <form method="get" target="_blank" action="https://www.google.com/search" accept-charset="utf-8" class="flex items-center">
            <!-- Champs cachés requis par votre formulaire d'origine -->
            <input type="hidden" name="ie" value="utf-8">
            <input type="hidden" name="sitesearch" value="camer.be">
            <input type="hidden" name="hl" value="fr">

            <div class="relative flex items-center">
                <!-- Champ de recherche (avec liaison Livewire optionnelle ou brut pour Google Search) -->
                <flux:input.group>
                    <flux:input
                        placeholder="Recherche"
                        type="text"
                        name="q"
                        id="s"
                        wire:model.live.debounce.300ms="query"

                    />
                    <flux:button icon="magnifying-glass" type="submit"></flux:button>
                </flux:input.group>
              <!--  <flux:input
                    type="text"
                    name="q"
                    id="s"
                    wire:model.live.debounce.300ms="query"
                    placeholder="Recherche..."
                    class="w-36 sm:w-48 md:w-64 px-3.5 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                />-->

                <!-- Bouton de soumission (Icône de loupe FontAwesome ou SVG) -->
                <!--<flux:button type="submit" aria-label="Rechercher" class="absolute right-2.5 p-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                    <!-- Utilisation de FontAwesome (comme dans votre code source d'origine)
                    <i data-lucide="search"></i>-->

                    <!-- OU équivalent SVG Tailwind pur si vous préférez vous passer de FontAwesome :
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    -->
                <!-- </flux:button>-->
            </div>
        </form>


    </div>
</div>
