<?php

namespace App\Console\Commands;

use App\Models\Sousrubrique;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSousRubriqueSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sous-rubrique-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les slugs manquants pour les sous-rubriques';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        //$query = Sousrubrique::all();
        $sousRubriques = Sousrubrique::all();
        //dd($sousRubriques);
        $this->info('Updating slugs of sousrubriques...');
        foreach ($sousRubriques as $sousRubrique){
            $baseSlug = Str::slug($sousRubrique->sousrubrique);
            $slug = $baseSlug;
            //dd($sousRubrique);
            $sousRubrique->update(['slug' => $slug]);
        }
        $this->info('Update done successfully');
    }
}
