@php
    $firstArticle = $articles->first();

    // Description dynamique
    $dynamicDescription = 'Camer.be: Info claire et nette sur le Cameroun et la Diaspora. ';
    $dynamicDescription .= "À la une : {$firstArticle['titre']}";
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';

    // Métadonnées génériques
    $title = 'Actualités Cameroun, Info & Analyse – Politique, Sport, Diaspora | Camer.be';
    $description = $dynamicDescription;
    $image = url('assets/img/camer-logo.png');
    $image_width = 190;
    $image_height = 52;
    $keyword = "actualités cameroun en direct, info cameroun dernière minute, politique cameroun, sport camerounais, lions indomptables, diaspora camerounaise, économie cameroun, Douala, Yaoundé, revue de presse camerounaise, investir au cameroun";
    $canonical = \App\Helpers\Helper::remove_amp_from_url(url()->current());

    // Seulement si ce n'est PAS une vidéo
    if (!$isVideo) {
        $section = $firstArticle['rubrique']['rubrique'] . " / " . $firstArticle['sousrubrique']['sousrubrique'];
        $author = $firstArticle['auteur'];
        $source = $firstArticle['source'];
    }
@endphp

@extends('layouts.amp-master')

@section('content')
    @include('partials.amp-index2')
    @include('partials.amp-video-viralize')
    @include('partials.amp-debat-droit')
    @include('partials.amp-event-pub')
    @include('partials.amp-video')
    @include('partials.amp-archive')
    <div class="material-box">
        @include('partials.amp-taboola')
    </div>
@endsection

