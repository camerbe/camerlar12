@php
    $firstArticle=$articles->first();
    //dd($firstArticle);
    $title=\App\Helpers\Helper::getTitle($firstArticle["countries"]["pays"],$firstArticle["titre"],$firstArticle["countries"]["country"]);
    $description=$firstArticle["chapeau"];
    $image=$firstArticle["image_url"];
    $image_width=$firstArticle["image_width"];
    $image_height=$firstArticle["image_height"];
    $keyword=trim($firstArticle["keyword"]);
    $section=$firstArticle["rubrique"]["rubrique"]." / ".$firstArticle["sousrubrique"]["sousrubrique"];
    $author=$firstArticle["auteur"];
    $source=$firstArticle["source"];
    $canonical= url()->current()
@endphp

@extends('layouts.amp-master')

@section('content')

@endsection
