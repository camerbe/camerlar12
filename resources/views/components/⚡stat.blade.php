<?php

use Livewire\Component;

new class extends Component
{
    //
    public $stat;
    public function mount($stat = [])
    {
        $stat = $stat;
    }
};
?>

<div>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Administrateurs</p>
                <h3 class="text-2xl font-bold mt-1">{{$stat['total_admins']}}</h3>
                <p class="text-xs text-emerald-600 flex items-center gap-1 mt-1">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +14% vs hier
                </p>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-green-700 rounded-xl">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Articles Publiés</p>
                <h3 class="text-2xl font-bold mt-1">{{$stat['published_today']}}</h3>
                <p class="text-xs text-gray-500 mt-1">Aujourd'hui</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-xl">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Programmé(s)</p>
                <h3 class="text-2xl font-bold mt-1">{{$stat['scheduled']}}</h3>
                <p class="text-xs text-amber-600 flex items-center gap-1 mt-1">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                </p>
            </div>
            <div class="p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 rounded-xl">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Commentaires</p>
                <h3 class="text-2xl font-bold mt-1">342</h3>
                <p class="text-xs text-camer-red flex items-center gap-1 mt-1">
                    <i data-lucide="shield-alert" class="w-3 h-3"></i> 12 en modération
                </p>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-900/30 text-camer-red rounded-xl">
                <i data-lucide="message-square" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
</div>
