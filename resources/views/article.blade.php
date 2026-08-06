@php


    $dynamicDescription = mb_substr($oneArticle["chapeau"], 0, 155, 'UTF-8') . '…';
    $geoplacename=$oneArticle["countries"]["pays"];
    $bled=$oneArticle["countries"]["pays"];
    $titre=$oneArticle["titre"];
    $title=\App\Helpers\Helper::getTitle($bled,$titre,"")." | Camer.be";
    $description=$dynamicDescription;
    $image=\App\Helpers\Helper::extractImgSrc($oneArticle["image"]) ;
    $mimeType=\App\Helpers\Helper::getImageMimeType($image) ;
    $arrImgDimension=\App\Helpers\Helper::getImageDimensions($image);
    $author=$oneArticle["auteur"];
    $keyword="actualités cameroun en direct, info cameroun dernière minute, politique cameroun, sport camerounais, lions indomptables, diaspora camerounaise, économie cameroun, Douala, Yaoundé, revue de presse camerounaise, investir au cameroun";
    $section=$oneArticle["rubrique"]["rubrique"]." / ".$oneArticle["sousrubrique"]["sousrubrique"];
    $canonical=url()->current();
    $source=$oneArticle["source"];
    $modified_time=$now=now()->format('Y-m-d\TH:i:s+00:00');
    $published_time=\Carbon\Carbon::parse($oneArticle['dateparution'])->format('Y-m-d\TH:i:s+00:00');
    $georegion =$oneArticle['fkpays'];
    $arrKeyword=explode(',',$oneArticle['keyword']);
    $hashtags=App\Helpers\Helper::nettoyerHashtags($arrKeyword);

@endphp

<x-layouts.app
    :title="$title"
    :description="$description"
    :image="$image"
    :image_width="$arrImgDimension['width']"
    :image_height="$arrImgDimension['height']"
    :image_type="$mimeType"
    :author="$author"
    :keyword="$keyword"
    :section="$section"
    :canonical="$canonical"
    :source="$source"
    :modified_time="$modified_time"
    :published_time="$published_time"
    :georegion="$georegion"
    :geoplacename="$geoplacename"
    :hashtags="$hashtags"

>
    <div>
        <livewire:article

                :oneArticle="$oneArticle"
                :plusLus="$plusLus"
                :sameRubrique="$sameRubrique"
                :debat="$debat"
        />
    </div>

</x-layouts.app>
