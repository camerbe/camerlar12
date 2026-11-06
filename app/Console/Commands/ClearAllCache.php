<?php

namespace App\Console\Commands;

use App\Models\Evenement;
use App\Models\Pub;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearAllCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-all-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all application caches (config, route, view, cache)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing all caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        /**
         * Supprime toutes les pubblicités périmées
         */
        Pub::where('endpubdate', '<', now())->delete();
        /**
         * Supprime touts les évènements passés
         */
        Evenement::where('eventdate','<',now())->delete();

        $this->info('All caches have been cleared successfully.');
    }
}
