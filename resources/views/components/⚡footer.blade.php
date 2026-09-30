<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<footer class="bg-gray-900 text-gray-300 border-t border-gray-800 pt-12 pb-8">
    <div
        x-data="{ show: false }"
        x-on:scroll.window="show = window.scrollY > 600"
    >
        <button
            type="button"
            x-show="show"
            x-transition
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-10 right-6 z-[9999] flex h-12 w-12 items-center justify-center rounded-full bg-red-600 text-white shadow-lg transition hover:bg-red-700"
            aria-label="Retour en haut"
        >
            <flux:icon name="arrow-up" class="h-5 w-5" />
        </button>
    </div>
    <livewire:archives :archives="$archives" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Brand Info -->
            <div class="space-y-4">
                <a href="{{route('home')}}">
                <span class="text-3xl font-extrabold font-heading tracking-tight text-white">
                    CAMER<span class="text-brand-500">.BE</span>
                </span>
                </a>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Le portail de référence de l'actualité camerounaise et internationale. Retrouvez en direct toutes les informations politiques, économiques et culturelles.
                </p>
            </div>

            <!-- Liens Rapides -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-brand-500 pl-2">Rubriques</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{config('app_url')}}/actualites/politique" class="hover:text-white transition">Politique</a></li>
                    <li><a href="{{config('app_url')}}/actualites/economie" class="hover:text-white transition">Économie & Finance</a></li>
                    <li><a href="{{config('app_url')}}/camerounais-du-monde/diaspora" class="hover:text-white transition">Diaspora en Action</a></li>
                    <li><a href="{{config('app_url')}}/culture/art" class="hover:text-white transition">Culture & Traditions</a></li>
                </ul>
            </div>

            <!-- Informations Légales -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-accent-500 pl-2">Informations</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{config('app_url')}}/contact" class="hover:text-white transition">Contact</a></li>
                    <li><a href="{{config('app_url')}}/qui-sommes-nous" class="hover:text-white transition">Politique de Confidentialité</a></li>

                </ul>
            </div>

            <!-- Newsletter -->

        </div>

        <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500">
            &copy; 2005 - {{ date('Y') }} Camer.be - Tous droits réservés. </br>Designed by JPB
        </div>
    </div>
</footer>
