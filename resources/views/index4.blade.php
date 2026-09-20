@php
    $snippet= $videos[0]['youtubeApi']['snippet'] ?? [];
    //dd($videos[0]);
    $dynamicDescription = 'Camer.be: Info claire et nette sur le Cameroun et la Diaspora. ';
    $dynamicDescription .="À la une : {videos[0]['titre']}";
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';

    $title="Actualités Cameroun, Info & Analyse – Politique, Sport, Vidéo {videos[0]['typevideo'] | Camer.be";
    $description=$dynamicDescription;
    $image=$videos[0]["cover_image"];
    $author=$videos[0]["typevideo"];
    $keyword="actualités cameroun en direct, info cameroun dernière minute, politique cameroun, sport camerounais, lions indomptables, diaspora camerounaise, économie cameroun, Douala, Yaoundé, revue de presse camerounaise, investir au cameroun";
    $section="Vidéo";
    $canonical=url()->current();
    $source=$videos[0]["typevideo"];
    $modified_time=$now=now()->format('Y-m-d\TH:i:s+00:00');
    $published_time=\Carbon\Carbon::parse($snippet['publishedAt'])->format('Y-m-d\TH:i:s+00:00');
    $georegion =$videos[0]["typevideo"]=="Camer"? "BE" : "FR";
    $geoplacename=$videos[0]["typevideo"]=="Camer"? "Belgique" : "France";
    //listItemVideos=$listItemVideos
    //dd($listItemVideos);
    $isVideo=true;

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
    :listItemVideos="$listItemVideos"
    :isVideo="$isVideo"
>
    <div>
        <livewire:video-list
            :listItemVideos="$listItemVideos"
            :videos="$videos"
            :droit="$droit"
            :event="$event"
            :debat="$debat"
            :sopie="$sopie"
            :camer="$camer"
            :skypper="$skypper"
        />
    </div>
</x-layouts.app>
