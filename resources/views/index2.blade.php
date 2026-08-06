@php

    $dynamicDescription = 'Camer.be: Info claire et nette sur le Cameroun et la Diaspora. ';
    $dynamicDescription .="À la une : {$heroArticle['titre']}";
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';

    $title='Actualités Cameroun, Info & Analyse – Politique, Sport, Diaspora | Camer.be';
    $description=$dynamicDescription;
    $image=$heroArticle["image_url"];
    $author=$heroArticle["auteur"];
    $keyword="actualités cameroun en direct, info cameroun dernière minute, politique cameroun, sport camerounais, lions indomptables, diaspora camerounaise, économie cameroun, Douala, Yaoundé, revue de presse camerounaise, investir au cameroun";
    $section=$heroArticle["rubrique"]["rubrique"]." / ".$heroArticle["sousrubrique"]["sousrubrique"];
    $canonical=url()->current();
    $source=$heroArticle["source"];
    $modified_time=$now=now()->format('Y-m-d\TH:i:s+00:00');
    $published_time=\Carbon\Carbon::parse($heroArticle['dateparution'])->format('Y-m-d\TH:i:s+00:00');
    $georegion =$heroArticle['fkpays'];
    $geoplacename=$heroArticle["countries"]["pays"];
@endphp

<x-layouts.app
    :title="$title"
    :description="$description"
    :image="$image"
    :author="$author"
    :keyword="$keyword"
    :section="$section"
    :canonical="$canonical"
    :source="$source"
    :modified_time="$modified_time"
    :published_time="$published_time"
    :georegion="$georegion"
    :geoplacename="$geoplacename"
>
    <div>
        <livewire:rubrique-article
            :rubriqueArticles="$rubriqueArticles"
            :heroArticle="$heroArticle"
        />
    </div>
</x-layouts.app>
