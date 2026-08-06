<?php

use Livewire\Component;
use App\Helpers\Helper;
use Carbon\Carbon;

new class extends Component
{
    public array $articles = [];
};
?>

<div>
   @foreach($articles as $article)
       @php
           $publidhedDate=$article['dateparution'];
           $hourToDisplay=Carbon::parse($publidhedDate)->format('H:i');
           $rubrique=$article['rubrique']['rubrique'];
           $titre=$article['titre'];
           $url=Helper::makeUrl(
              $article['rubrique']['rubrique'],
              $article['sousrubrique']['sousrubrique'],
              $article['slug']
          );
       @endphp
        <a href="/{{$url}}" class="hover:underline mr-8">🚨 <span class="font-bold">{{$hourToDisplay}}:</span> {{$titre}}</a>
   @endforeach
</div>
