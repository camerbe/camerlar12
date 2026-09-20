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
                <p class="text-xs font-semibold text-gray-500 uppercase">En Attente de Validation</p>
                <h3 class="text-2xl font-bold mt-1">7</h3>
                <p class="text-xs text-amber-600 flex items-center gap-1 mt-1">
                    <i data-lucide="clock" class="w-3 h-3"></i> Nécessite révision
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
