<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Console\Command;

class UpdMediaTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upd-media-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update media table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Updating Articles Media...');
        $articles=ArticleResource::collection(
            Article::with(['countries','rubrique','sousrubrique'])
                ->orderByDesc('dateparution')
                ->limit(100)
                ->get()
        ) ;
        Article::all()->each()->delete();
        //$firstIteration = true;
        foreach ($articles as  $article){
            /*if($firstIteration){
                $article->clearMediaCollection('article');
                $firstIteration=false;
            }*/
            $image=Helper::extractImgSrc($article->image);
            $image=Helper::parseImageUrl($image);
            $article->addMediaFromUrl($image)
                ->preservingOriginal()
                ->withResponsiveImages()
                ->usingName($article->slug)
                ->toMediaCollection('article');
        }
        $this->info('Update done successfully');
    }
}
