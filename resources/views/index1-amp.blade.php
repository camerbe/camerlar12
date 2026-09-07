@php
    //$firstArticle=$article->first();

    $today = now()->format('Y-m-d\TH:i:s+00:00');
    $articleDate = \Carbon\Carbon::parse($article['dateparution'])->format('Y-m-d\TH:i:s+00:00');
    $year = \Carbon\Carbon::parse($article['dateparution'])->year;
    $dynamicDescription = $article['chapeau'];
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';
    $image=\App\Helpers\Helper::extractImgSrc($article["image"]) ;
    $mimeType=\App\Helpers\Helper::getImageMimeType($image) ;
    $title=\App\Helpers\Helper::getTitle($article["countries"]["pays"],$article["titre"],$article["countries"]["country"]);
    //$title=$title. ' Camer.be';
    $description=$dynamicDescription;
    $image=$article['image_url'];
    $image_width=$article['image_width'];
    $image_height=$article['image_height'];
    //$keyword=array_map('trim', explode(',', $article['keyword']));
    $arrKeyword=explode(',',$article['keyword']);
    $hashtags=App\Helpers\Helper::nettoyerHashtags($arrKeyword);
    $keyword=$article['keyword'];
    $modified_time=$today;
    $published_time=$articleDate;
    $section=$article["sousrubrique"]["sousrubrique"];
    $rub=$article["rubrique"]["rubrique"];
    $author=$article["auteur"];
    $source=$article["source"];
    $canonical= \App\Helpers\Helper::remove_amp_from_url(url()->current());
    $georegion =$article['fkpays'];
    $geoplacename=$article["countries"]["pays"];
    $isJson4article=true;
    $breadcumbUrl='amp/'.\Illuminate\Support\Str::slug($rub)."/".strtolower($section);
    $wordCount=App\Helpers\Helper::countArticleCharacters($article["info"]);
    $jld=$ldjson;

@endphp
@extends('layouts.amp-master')
@section('content')
    @include('partials.amp-article')
    @include('partials.amp-video-viralize')
    @include('partials.amp-debat-droit')
    @include('partials.amp-event-pub')
    @include('partials.amp-video')
    @include('partials.amp-archive')
@endsection
