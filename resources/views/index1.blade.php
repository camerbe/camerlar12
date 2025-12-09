@php
    //$firstArticle=$article->first();
    $today = now()->format('Y-m-d\TH:i:s+00:00');
    $articleDate = \Carbon\Carbon::parse($article['dateparution'])->format('Y-m-d\TH:i:s+00:00');
    $year = \Carbon\Carbon::parse($article['dateparution'])->year;
    $dynamicDescription = $article['chapeau'];
    $dynamicDescription = mb_substr($dynamicDescription, 0, 155, 'UTF-8') . '…';

    //dd($today);
    $title=\App\Helpers\Helper::getTitle($article["countries"]["pays"],$article["titre"],$article["countries"]["country"]);
    //$title=$title. ' Camer.be';
    $description=$dynamicDescription;
    $image=$article['image_url'];
    $image_width=$article['image_width'];
    $image_height=$article['image_height'];
    //$keyword=array_map('trim', explode(',', $article['keyword']));
    $keyword=$article['keyword'];
    $modified_time=$today;
    $published_time=$articleDate;
    $section=$article["rubrique"]["rubrique"]." / ".$article["sousrubrique"]["sousrubrique"];
    $author=$article["auteur"];
    $source=$article["source"];
    $canonical= url()->current()
@endphp
@extends('layouts.amp-master')
@section('content')
    @include('partials.amp-article')

@endsection
