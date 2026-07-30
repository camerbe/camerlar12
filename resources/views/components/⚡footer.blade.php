<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<footer class="bg-gray-900 text-gray-300 border-t border-gray-800 pt-12 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Brand Info -->
            <div class="space-y-4">
                <span class="text-3xl font-extrabold font-heading tracking-tight text-white">
                    CAMER<span class="text-brand-500">.BE</span>
                </span>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Le portail de référence de l'actualité camerounaise et internationale. Retrouvez en direct toutes les informations politiques, économiques et culturelles.
                </p>
            </div>

            <!-- Liens Rapides -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-brand-500 pl-2">Rubriques</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition">Politique Cameroun</a></li>
                    <li><a href="#" class="hover:text-white transition">Économie & Finance</a></li>
                    <li><a href="#" class="hover:text-white transition">Diaspora en Action</a></li>
                    <li><a href="#" class="hover:text-white transition">Culture & Traditions</a></li>
                </ul>
            </div>

            <!-- Informations Légales -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-accent-500 pl-2">Informations</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition">Mentions Légales</a></li>
                    <li><a href="#" class="hover:text-white transition">Politique de Confidentialité</a></li>
                    <li><a href="#" class="hover:text-white transition">Charte Éditoriale</a></li>
                    <li><a href="#" class="hover:text-white transition">Annoncer sur le site</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-highlight-500 pl-2">Newsletter</h4>
                <p class="text-xs text-gray-400 mb-3">Recevez le résumé de l'actualité chaque matin.</p>
                <form class="flex flex-col space-y-2">
                    <input type="email" placeholder="Votre email" class="px-3 py-2 bg-gray-800 border border-gray-700 text-white text-xs rounded focus:outline-none focus:border-brand-500">
                    <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs py-2 rounded transition">
                        S'abonner
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Camer.be - Tous droits réservés.
        </div>
    </div>
</footer>
