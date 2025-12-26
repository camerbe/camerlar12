@php
    $firstArticle=$articles->first();
    $dynamicDescription = 'Camer.be: Info claire et nette sur le Cameroun et la Diaspora. ';
    $dynamicDescription .="À la une : {$firstArticle['titre']}";
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';

    //dd($firstArticle);
    //$title=\App\Helpers\Helper::getTitle($firstArticle["countries"]["pays"],$firstArticle["titre"],$firstArticle["countries"]["country"]);
    $title='Actualités Cameroun, Info & Analyse – Politique, Sport, Diaspora | Camer.be';
    $description=$dynamicDescription;
    $image=url('assets/img/camer-logo.png');
    $image_width=190;
    $image_height=52;
    $keyword="actualités cameroun en direct, info cameroun dernière minute, politique cameroun, sport camerounais, lions indomptables, diaspora camerounaise, économie cameroun, Douala, Yaoundé, revue de presse camerounaise, investir au cameroun";
    $section=$firstArticle["rubrique"]["rubrique"]." / ".$firstArticle["sousrubrique"]["sousrubrique"];
    $author=$firstArticle["auteur"];
    $source=$firstArticle["source"];
    $canonical= \App\Helpers\Helper::remove_amp_from_url(url()->current());
    $modified_time=$now=now()->format('Y-m-d\TH:i:s+00:00');
    $published_time=\Carbon\Carbon::parse($firstArticle['dateparution'])->format('Y-m-d\TH:i:s+00:00');
@endphp

@extends('layouts.amp-master')

@section('content')
    @include('partials.amp-index')
    @include('partials.amp-video-viralize')
    @include('partials.amp-debat-droit')
    @include('partials.amp-event-pub')
    @include('partials.amp-video')
    @include('partials.amp-archive')

@endsection
