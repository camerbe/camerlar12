@foreach($articles as $article)
    @php
        $publidhedDate=$article['dateparution'];
        $hourToDisplay=\Carbon\Carbon::parse($publidhedDate)->format('H:i');
        $rubrique=$article['rubrique']['rubrique'];
        $titre=$article['titre'];
        $url=\App\Helpers\Helper::makeUrl(
           $article['rubrique']['rubrique'],
           $article['sousrubrique']['sousrubrique'],
           $article['slug']
       );
    @endphp
    <a href="/{{$url}}" class="hover:underline mr-8">🚨 <span class="font-bold">{{$hourToDisplay}}:</span> {{$titre}}</a>
@endforeach
