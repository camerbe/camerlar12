@php
    //dd($videos);
    $firstVideo= $videos->first();
    //dd($firstVideo);
    $snippet=$firstVideo['youtubeApi']['snippet'] ?? [];
    // Description dynamique
    $dynamicDescription = 'Camer.be: Info claire et nette sur le Cameroun et la Diaspora. ';
    $dynamicDescription .= "À la une : {$firstVideo['titre']}";
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';

    // Métadonnées génériques

    $title = 'Actualités Cameroun, Info & Analyse – Politique, Sport, Vidéo '. $firstVideo['typevideo']. ' | Camer.be';
    $description = $dynamicDescription;
    $image = url('assets/img/camer-logo.png');
    $image_width = 190;
    $image_height = 52;
    $keyword = "actualités cameroun en direct, info cameroun dernière minute, politique cameroun, sport camerounais, lions indomptables, diaspora camerounaise, économie cameroun, Douala, Yaoundé, revue de presse camerounaise, investir au cameroun";
    $canonical = \App\Helpers\Helper::remove_amp_from_url(url()->current());

    $section = "Vidéo";
    $author = $firstVideo["typevideo"];
    $source = $author;
    $isVideo=true;

@endphp

@extends('layouts.amp-master')

@section('content')
    @include('partials.amp-video-index')
    @include('partials.amp-video-viralize')
    @include('partials.amp-debat-droit')
    @include('partials.amp-event-pub')
    @include('partials.amp-video')
    @include('partials.amp-archive')
    <div class="material-box">
        @include('partials.amp-taboola')
    </div>
@endsection

